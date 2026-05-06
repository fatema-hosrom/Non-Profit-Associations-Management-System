<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\OrganizationActivity;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCPDF;

class PaymentController extends Controller
{
    public function processPayment(Request $request, $activityId)
    {
        $activity = OrganizationActivity::with('donationSettings')->findOrFail($activityId);
        $donationSettings = $activity->donationSettings;

        if (!$donationSettings) {
            return redirect()->back()->with('error', 'هذه الفعالية لا تدعم التبرع حالياً.');
        }

        if ($donationSettings->collected_amount >= $donationSettings->target_amount) {
            return redirect()->back()->with('error', 'الحملة مكتملة الآن، ولا يمكن قبول تبرعات إضافية.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:1',
            'card_holder_name' => 'required|string|max:255',
            'card_number' => ['required', 'regex:/^[0-9 ]{16,19}$/'],
            'card_expiry' => ['required', 'regex:/^(0[1-9]|1[0-2])\/(\d{2})$/'],
            'card_cvv' => 'required|digits_between:3,4',
            'notes' => 'nullable|string|max:1000',
            'anonymous' => 'nullable|boolean',
        ]);

        $expiry = $request->input('card_expiry');
        [$month, $year] = explode('/', $expiry);
        $expiryDate = Carbon::createFromFormat('m/y', $expiry)->endOfMonth();

        if ($expiryDate->lt(now())) {
            return redirect()->back()->withInput()->with('error', 'تاريخ انتهاء البطاقة غير صالح.');
        }

        $remaining = max(0, $donationSettings->target_amount - $donationSettings->collected_amount);
        if ($request->amount > $remaining && $remaining > 0) {
            return redirect()->back()->withInput()->with('error', 'المبلغ يتجاوز المتبقي المطلوب لهذه الحملة.');
        }

        $cardNumber = preg_replace('/\s+/', '', $request->card_number);
        if (!preg_match('/^[0-9]{16}$/', $cardNumber)) {
            return redirect()->back()->withInput()->with('error', 'رقم البطاقة غير صالح.');
        }

        $cardBrand = $this->detectCardBrand($cardNumber);
        $last4 = substr($cardNumber, -4);

        DB::beginTransaction();
        try {
            $donor = Donor::firstOrCreate([
                'email' => $request->email,
            ], [
                'name' => $request->name,
                'phone' => $request->phone,
            ]);

            $donation = Donation::create([
                'donor_id' => $donor->id,
                'activity_id' => $activity->id,
                'amount' => $request->amount,
                'donation_type' => 'online',
                'date' => now(),
                'notes' => $request->notes,
                'created_by' => null,
            ]);

            $payment = Payment::create([
                'donation_id' => $donation->id,
                'amount' => $request->amount,
                'currency' => 'SAR',
                'status' => 'completed',
                'payment_method' => 'card',
                'transaction_reference' => 'CARD-' . strtoupper(uniqid()),
                'card_brand' => $cardBrand,
                'card_last4' => $last4,
                'response_message' => 'تمت المعالجة بنجاح عبر النموذج الداخلي.',
            ]);

            $donationSettings->increment('collected_amount', $request->amount);

            DB::commit();

            return redirect()->route('public.activities.sahem.show', ['id' => $activity->id, 'donate' => 0])
                ->with('success', 'تمت عملية الدفع بنجاح، وتم تسجيل التبرع. شكراً لدعمك.')
                ->with('receipt_payment_id', $payment->id)
                ->with('auto_download_receipt', true);
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء معالجة الدفع. حاول مرة أخرى.');
        }
    }

    public function downloadReceipt($paymentId)
    {
        $payment = Payment::with(['donation.donor', 'donation.activity'])->findOrFail($paymentId);

        if ($payment->status !== 'completed') {
            return redirect()->back()->with('error', 'لا يمكن تنزيل إيصال لعملية غير مكتملة.');
        }

        $donation = $payment->donation;
        $donor = $donation?->donor;
        $activity = $donation?->activity;

        $html = '
        <h1 style="text-align:center;">إيصال تبرع</h1>
        <hr>
        <p><strong>رقم العملية:</strong> ' . e($payment->transaction_reference ?? ('PAY-' . $payment->id)) . '</p>
        <p><strong>اسم المتبرع:</strong> ' . e($donor->name ?? 'غير متوفر') . '</p>
        <p><strong>البريد الإلكتروني:</strong> ' . e($donor->email ?? 'غير متوفر') . '</p>
        <p><strong>الفعالية:</strong> ' . e($activity->title ?? 'غير متوفرة') . '</p>
        <p><strong>المبلغ:</strong> ' . e(number_format((float) $payment->amount, 2)) . ' ' . e($payment->currency) . '</p>
        <p><strong>طريقة الدفع:</strong> بطاقة ' . e($payment->card_brand ?? '') . ' ****' . e($payment->card_last4 ?? '') . '</p>
        <p><strong>الحالة:</strong> ' . e($payment->status) . '</p>
        <p><strong>تاريخ العملية:</strong> ' . e($payment->created_at?->format('Y-m-d H:i') ?? now()->format('Y-m-d H:i')) . '</p>
        <p><strong>رسالة المعالجة:</strong> ' . e($payment->response_message ?? 'تمت العملية بنجاح') . '</p>
        <hr>
        <p style="text-align:center;">شكراً لدعمك.</p>
        ';

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(config('app.name', 'SAHAM'));
        $pdf->SetAuthor(config('app.name', 'SAHAM'));
        $pdf->SetTitle('Donation Receipt');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setRTL(true);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();
        $pdf->SetFont('aealarabiya', '', 12);
        $pdf->writeHTML($html, true, false, true, false, '');

        $fileName = 'donation_receipt_' . $payment->id . '.pdf';

        return response($pdf->Output($fileName, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function detectCardBrand(string $cardNumber): string
    {
        if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $cardNumber)) {
            return 'Visa';
        }

        if (preg_match('/^5[1-5][0-9]{14}$/', $cardNumber)) {
            return 'MasterCard';
        }

        if (preg_match('/^3[47][0-9]{13}$/', $cardNumber)) {
            return 'American Express';
        }

        return 'Card';
    }
}
