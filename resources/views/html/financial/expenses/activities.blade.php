@extends('templates.financial_app')

@section('title', 'الفعاليات - المصاريف')

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- Title --}}
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2 text-blue-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-3-3v6m-9 3h18a2 2 0 002-2V7a2 2 0 00-2-2H3a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        الفعاليات التي تحتوي مصاريف
    </h1>

    {{-- Search Filter + Add --}}
    <div class="mb-6 flex flex-wrap justify-between gap-2 items-center">

        <form method="GET" class="flex gap-2 flex-1 min-w-[200px]">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث..."
                    class="border p-2 pl-10 rounded w-full">

                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19l-4 2v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                بحث
            </button>
        </form>

        <a href="{{ route('financial.expenses.create') }}"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4"/>
            </svg>
            إضافة مصروف
        </a>

    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden border">

        <table class="w-full table-fixed">
            <thead>
                <tr class="bg-gray-100 text-gray-700 text-sm">
                    <th class="px-4 py-3 text-right w-1/4">العنوان</th>
                    <th class="px-4 py-3 text-right">الوصف</th>
                    <th class="px-4 py-3 text-center w-44">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y">

                @forelse($activities as $act)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-semibold truncate">{{ $act->title }}</td>
                    <td class="px-4 py-3 text-gray-600 truncate">{{ $act->description }}</td>
                    <td class="px-4 py-3 flex justify-center gap-3">

                        {{-- View Expenses --}}
                        <a href="{{ route('financial.expenses.activity.show', $act->id) }}"
                            class="flex items-center gap-1 text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0
                                    8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            عرض
                        </a>

                        {{-- Add Expense --}}
                        <a href="{{ route('financial.expenses.activity.create', $act->id) }}"
                            class="flex items-center gap-1 text-green-600 hover:text-green-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"/>
                            </svg>
                            إضافة
                        </a>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-6 text-center text-gray-500">
                        لا توجد فعاليات
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

    </div>

    {{-- Pagination --}}
    <div class="mt-4">{{ $activities->links() }}</div>

</div>
@endsection
