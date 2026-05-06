@extends('templates.financial_app')

@section('title', 'إضافة تبرع')

@section('content')
    <div class="container mx-auto max-w-5xl px-4 py-2">

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">

            {{-- العنوان --}}
            <div class="flex items-center justify-between mb-6 border-b pb-4">
                <h1 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v8m-4-4h8" />
                    </svg>
                    إضافة تبرع لفعالية: {{ $activity->title }}
                </h1>
            </div>


            {{-- رسالة نجاح --}}
            @if (session('success'))
                <div id="flash-success"
                    class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9
                                 10.586 7.707 9.293a1 1 0 10-1.414
                                 1.414L9 13.414l4.707-4.707z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>

                <script>
                    setTimeout(function() {
                        document.getElementById('flash-success').style.display = 'none';
                    }, 4000);
                </script>
            @endif


            {{-- الأخطاء --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form method="POST" action="{{ route('financial.donations.activity.store', $activity->id) }}">
                @csrf

                {{-- اختيار المتبرع --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">المتبرع</label>

                    <select name="donor_id" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- اختر متبرع --</option>

                        @foreach ($donors as $donor)
                            <option value="{{ $donor->id }}" {{ old('donor_id') == $donor->id ? 'selected' : '' }}>
                                {{ $donor->name }}
                            </option>
                        @endforeach
                    </select>

                    <p class="text-sm text-gray-500 mt-1">
                        أو أدخل بيانات متبرع جديد
                    </p>
                </div>


                {{-- بيانات متبرع جديد --}}
                <div id="new-donor-fields" class="bg-gray-50 border rounded-lg p-6 mb-6">

                    <h2 class="text-lg font-semibold text-gray-700 mb-4">
                        بيانات متبرع جديد
                    </h2>

                    <div class="grid md:grid-cols-2 gap-4">

                        <input type="text" name="donor_name" value="{{ old('donor_name') }}" placeholder="اسم المتبرع"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">

                        <input type="email" name="donor_email" value="{{ old('donor_email') }}"
                            placeholder="البريد الإلكتروني"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">

                        <input type="text" name="donor_phone" value="{{ old('donor_phone') }}" placeholder="الهاتف"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">

                        <textarea name="donor_address" placeholder="العنوان"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 md:col-span-2">{{ old('donor_address') }}</textarea>

                    </div>
                </div>


                {{-- بيانات التبرع --}}
                <div class="border-t pt-6">

                    <h2 class="text-lg font-semibold text-gray-700 mb-4">
                        تفاصيل التبرع
                    </h2>

                    <div class="grid md:grid-cols-3 gap-4">

                        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}"
                            placeholder="المبلغ" required
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">

                        <select name="donation_type" required
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">

                            <option value="">اختر النوع</option>
                            <option value="cash" {{ old('donation_type') == 'cash' ? 'selected' : '' }}>نقدي</option>
                            <option value="online" {{ old('donation_type') == 'online' ? 'selected' : '' }}>أونلاين
                            </option>
                            <option value="check" {{ old('donation_type') == 'check' ? 'selected' : '' }}>شيك</option>
                            <option value="other" {{ old('donation_type') == 'other' ? 'selected' : '' }}>أخرى</option>

                        </select>

                        <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">

                        <textarea name="notes" placeholder="ملاحظات"
                            class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 md:col-span-2">{{ old('notes') }}</textarea>

                    </div>

                </div>


                {{-- زر الحفظ --}}
                <div class="mt-8 flex justify-end">

                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700
                    hover:from-blue-700 hover:to-blue-800
                    text-white px-8 py-3 rounded-lg font-semibold shadow-md
                    transition">

                        حفظ التبرع

                    </button>

                </div>

            </form>

        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var select = document.querySelector('select[name=donor_id]');
            var fields = document.getElementById('new-donor-fields');

            function toggleFields() {
                fields.style.display = select.value ? 'none' : 'block';
            }

            select.addEventListener('change', toggleFields);
            toggleFields();

        });
    </script>

@endsection
