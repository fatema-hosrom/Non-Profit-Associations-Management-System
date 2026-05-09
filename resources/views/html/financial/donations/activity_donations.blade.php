@extends('templates.financial_app')

@section('title', 'تبرعات "' . $activity->title . '"')

@section('content')
    <div class="container mx-auto px-4 py-6">

        {{-- Title --}}
        <h1 class="text-2xl font-bold mb-6 flex items-center gap-2 text-blue-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                       3-.895 3-2-1.343-2-3-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v8m-4-4h8" />
            </svg>

            تبرعات الفعالية: {{ $activity->title }}
        </h1>


        {{-- Add Button --}}
        <div class="mb-6">
            <a href="{{ route('financial.donations.activity.create', $activity->id) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2 w-fit">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>

                إضافة تبرع
            </a>
        </div>


        {{-- Search Filter --}}
        <form method="GET" class="mb-6 flex flex-wrap gap-2 items-center">

            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث عن متبرع..."
                    class="border p-2 pl-10 rounded w-full">

                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14
                           0 7 7 0 0114 0z" />

                </svg>
            </div>


            <div class="relative">
                <select name="type" class="border p-2 pr-8 rounded">

                    <option value="">كل الأنواع</option>

                    <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>
                        نقدي
                    </option>

                    <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>
                        أونلاين
                    </option>

                    <option value="check" {{ request('type') == 'check' ? 'selected' : '' }}>
                        شيك
                    </option>

                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>
                        أخرى
                    </option>

                </select>
            </div>


            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-1">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0
                           011 1v2a1 1 0 01-.293.707L14
                           13.414V19l-4 2v-7.586L3.293
                           6.707A1 1 0 013 6V4z" />

                </svg>

                فلتر
            </button>

        </form>


        {{-- Table --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">

            <table class="w-full table-auto border-collapse">

                <thead>

                    <tr class="bg-gray-100 text-gray-700">

                        <th class="border px-4 py-2">المتبرع</th>

                        <th class="border px-4 py-2 flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16
                                       8 8 0 000 16zm1-11H9v2H7v2h2v2h2v-2h2v-2h-2V7z" />
                            </svg>
                            المبلغ
                        </th>

                        <th class="border px-4 py-2">النوع</th>

                        <th class="border px-4 py-2 flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9
                                       8h10M5 21h14a2 2 0
                                       002-2V7a2 2 0
                                       00-2-2H5a2 2 0
                                       00-2 2v12a2 2 0
                                       002 2z" />

                            </svg>
                            التاريخ
                        </th>

                        <th class="border px-4 py-2">إجراءات</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($donations as $don)
                        <tr class="hover:bg-gray-50">

                            <td class="border px-4 py-2">
                                {{ $don->donor->name ?? 'Unknown' }}
                            </td>

                            <td class="border px-4 py-2 text-center font-semibold text-green-700">
                                {{ number_format($don->amount, 2) }}
                            </td>

                            <td class="border px-4 py-2 text-center">
                                {{ $don->donation_type }}
                            </td>

                            <td class="border px-4 py-2 text-center">
                                {{ $don->date->format('Y-m-d') }}
                            </td>

                            <td class="border px-4 py-2 flex gap-3 justify-center">

                                <a href="{{ route('financial.donations.activity.edit', [$activity->id, $don->id]) }}"
                                    class="text-yellow-600 hover:text-yellow-700 flex items-center gap-1">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5h2m-1 0v14m-4-4h8" />

                                    </svg>

                                    تعديل

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="border px-4 py-4 text-center text-gray-500">

                                لا توجد تبرعات

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="mt-4">
            {{ $donations->links() }}
        </div>


        {{-- Total Donations --}}
        <div class="mt-4 flex items-center gap-2 text-lg">

            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">

                <path d="M10 18a8 8 0 100-16
                         8 8 0 000 16zm1-11H9v2H7v2h2v2h2v-2h2v-2h-2V7z" />

            </svg>

            مجموع التبرعات:

            <strong class="text-green-700">
                {{ number_format($totalAmount, 2) }}
            </strong>

        </div>

    </div>
@endsection
