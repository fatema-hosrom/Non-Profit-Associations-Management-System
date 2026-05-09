@extends('templates.financial_app')

@section('title', 'تعديل تبرع')

@section('content')

    <div class="container mx-auto max-w-5xl px-4 py-8">

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">

            {{-- Title --}}
            <div class="flex items-center justify-between mb-6 border-b pb-4">
                <h1 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 0v14m-4-4h8" />
                    </svg>
                    تعديل التبرع للفعالية: {{ $activity->title }}
                </h1>
            </div>


            {{-- Errors --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form method="POST"
                action="{{ route('financial.donations.activity.update', [$activity->id, $donation->id]) }}">

                @csrf
                @method('PUT')


                {{-- Donation Details --}}
                <div class="mb-8">

                    <h2 class="text-lg font-semibold text-gray-700 mb-4">
                        تفاصيل التبرع
                    </h2>

                    <div class="grid md:grid-cols-2 gap-4">

                        {{-- Donor --}}
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">
                                المتبرع
                            </label>

                            <select name="donor_id" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">

                                <option value="">-- اختر متبرع --</option>

                                @foreach ($donors as $donor)
                                    <option value="{{ $donor->id }}"
                                        {{ old('donor_id', $donation->donor_id) == $donor->id ? 'selected' : '' }}>
                                        {{ $donor->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>


                        {{-- Amount --}}
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">
                                المبلغ
                            </label>

                            <input type="number" step="0.01" name="amount"
                                value="{{ old('amount', $donation->amount) }}" required
                                class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">
                        </div>


                        {{-- Type --}}
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">
                                النوع
                            </label>

                            <select name="donation_type" required
                                class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">

                                <option value="cash"
                                    {{ old('donation_type', $donation->donation_type) == 'cash' ? 'selected' : '' }}>
                                    نقدي
                                </option>

                                <option value="online"
                                    {{ old('donation_type', $donation->donation_type) == 'online' ? 'selected' : '' }}>
                                    أونلاين
                                </option>

                                <option value="check"
                                    {{ old('donation_type', $donation->donation_type) == 'check' ? 'selected' : '' }}>
                                    شيك
                                </option>

                                <option value="other"
                                    {{ old('donation_type', $donation->donation_type) == 'other' ? 'selected' : '' }}>
                                    أخرى
                                </option>

                            </select>
                        </div>


                        {{-- Date --}}
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">
                                التاريخ
                            </label>

                            <input type="date" name="date" value="{{ old('date', $donation->date->format('Y-m-d')) }}"
                                required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">
                        </div>


                        {{-- Notes --}}
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-medium mb-1">
                                ملاحظات
                            </label>

                            <textarea name="notes" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500" rows="3">{{ old('notes', $donation->notes) }}</textarea>
                        </div>

                    </div>

                </div>


                {{-- Correction Reason --}}
                <div class="border-t pt-6">

                    <h2 class="text-lg font-semibold text-gray-700 mb-4">
                        معلومات التعديل
                    </h2>

                    <div>

                        <label class="block text-gray-700 font-medium mb-1">
                            سبب التعديل (اختياري)
                        </label>

                        <textarea name="correction_reason" rows="3" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">{{ old('correction_reason') }}</textarea>

                        <p class="text-sm text-gray-500 mt-1">
                            إذا تم تغيير المبلغ سيتم تسجيل السبب في سجل التصحيحات.
                        </p>

                    </div>

                </div>


                {{-- Update Button --}}
                <div class="mt-8 flex justify-end">

                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700
                    hover:from-blue-700 hover:to-blue-800
                    text-white px-8 py-3 rounded-lg
                    font-semibold shadow-md transition">

                        تحديث التبرع

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
