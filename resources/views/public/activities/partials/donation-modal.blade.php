<div id="donationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/70 backdrop-blur-sm p-4">
    <div class="w-full max-w-3xl bg-white rounded-3xl overflow-hidden shadow-[0_30px_80px_-20px_rgba(0,0,0,0.55)] border border-white/70">
        <div class="flex items-center justify-between px-7 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 via-white to-emerald-50">
            <div>
                <h3 class="text-2xl font-extrabold text-gray-900">تبرع بالبطاقة للفعالية</h3>
                <p class="text-sm text-gray-600">أدخل بيانات البطاقة والمبلغ لإتمام التبرع بشكل آمن.</p>
            </div>
            <button id="closeDonationModal" class="w-10 h-10 rounded-full bg-white border border-gray-200 text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition">
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>

        <form action="{{ route('public.activities.payment.process', $activity->id) }}" method="POST" class="grid gap-6 px-7 py-7 bg-white">
            @csrf

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">اسم حامل البطاقة</label>
                    <input type="text" name="card_holder_name" value="{{ old('card_holder_name') }}" required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2">
                    @error('card_holder_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">رقم البطاقة</label>
                    <input type="text" name="card_number" value="{{ old('card_number') }}" maxlength="19" required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2">
                    @error('card_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ الانتهاء (MM/YY)</label>
                    <input type="text" name="card_expiry" value="{{ old('card_expiry') }}" maxlength="5" required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2">
                    @error('card_expiry')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">CVV</label>
                    <input type="text" name="card_cvv" value="{{ old('card_cvv') }}" maxlength="4" required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2">
                    @error('card_cvv')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">المبلغ ($)</label>
                    <input type="number" step="0.01" min="1" name="amount" value="{{ old('amount') }}" required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2">
                    @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ملاحظات</label>
                    <input type="text" name="notes" value="{{ old('notes') }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2">
                    @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                <input type="hidden" name="anonymous" value="0">
                <input id="anonymousDonation" type="checkbox" name="anonymous" value="1" class="h-4 w-4 text-indigo-600 rounded">
                <label for="anonymousDonation" class="text-sm text-gray-700">أريد أن يكون هذا التبرع مجهولاً</label>
            </div>

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between pt-2">
                <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl px-8 py-3 font-bold shadow-lg shadow-green-200 transition">
                    إكمال الدفع
                </button>
                <div class="flex gap-3">
                    <button type="button" id="fillTestData" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl px-6 py-3 font-semibold transition">
                        ملء بيانات الاختبار
                    </button>
                    <button type="button" id="cancelDonationModal" class="w-full md:w-auto bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300 rounded-xl px-6 py-3 font-semibold transition">
                        إلغاء
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
(() => {
    const fillTestDataButton = document.getElementById('fillTestData');
    if (!fillTestDataButton) return;

    fillTestDataButton.addEventListener('click', function() {
        // ملء بيانات البطاقة الاختبارية (Visa)
        document.querySelector('input[name="card_holder_name"]').value = 'Test User';
        document.querySelector('input[name="card_number"]').value = '4111 1111 1111 1111';
        document.querySelector('input[name="card_expiry"]').value = '12/30';
        document.querySelector('input[name="card_cvv"]').value = '123';
        document.querySelector('input[name="email"]').value = 'test@example.com';
        document.querySelector('input[name="name"]').value = 'مستخدم اختبار';
        document.querySelector('input[name="phone"]').value = '0501234567';
        document.querySelector('input[name="amount"]').value = '100.00';
        document.querySelector('input[name="notes"]').value = 'تبرع اختبار';
    });
})();
</script>
