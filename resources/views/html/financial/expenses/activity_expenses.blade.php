@extends('templates.financial_app')

@section('title', 'مصروفات "' . $activity->title . '"')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/financial/activity-expenses.css') }}">
@endpush

@section('content')
    <div class="container mx-auto px-4 py-6">

        {{-- العنوان --}}
        <h1 class="text-2xl font-bold mb-6 flex items-center gap-2 text-blue-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-3-3v6m-9 3h18a2 2 0 002-2V7a2 2 0 00-2-2H3a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            مصروفات الفعالية: {{ $activity->title }}
        </h1>

        {{-- إضافة مصروف + فلتر البحث --}}
        <div class="mb-6 flex flex-wrap justify-between gap-2 items-center">

            <form method="GET" class="flex gap-2 flex-1 min-w-[200px]">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث عن وصف..."
                        class="border p-2 pl-10 rounded w-full">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                    </svg>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19l-4 2v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    فلتر
                </button>
            </form>

            <a href="{{ route('financial.expenses.activity.create', $activity->id) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                إضافة مصروف
            </a>

        </div>

        {{-- الجدول --}}
        <div class="bg-white rounded-xl shadow overflow-hidden border">
            <table class="w-full table-fixed">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="px-4 py-3 text-right w-1/4">الوصف</th>
                        <th class="px-4 py-3 text-center w-24">رقم الإيصال</th>
                        <th class="px-4 py-3 text-center w-20">المبلغ</th>
                        <th class="px-4 py-3 text-center w-28">التاريخ</th>
                        <th class="px-4 py-3 text-center w-32">الملف</th>
                        <th class="px-4 py-3 text-center w-36">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($expenses as $exp)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-700 truncate">{{ $exp->description }}</td>
                            <td class="px-4 py-3 text-center font-mono text-blue-700">
                                {{ $exp->receipt_number ?? 'غير محدد' }}</td>
                            <td class="px-4 py-3 text-center font-semibold text-green-700">
                                {{ number_format($exp->amount, 2) }}</td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $exp->expense_date }}</td>
                            <td class="px-4 py-3 text-center">
                                @if ($exp->receipt)
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ asset($exp->receipt) }}" download
                                            class="text-green-600 hover:text-green-800 flex items-center gap-1"
                                            title="تحميل الملف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                </path>
                                            </svg>
                                        </a>
                                        <button type="button"
                                            onclick="openDeleteConfirm('{{ route('financial.expenses.activity.delete-receipt', [$activity->id, $exp->id]) }}', '{{ $exp->description }}')"
                                            class="text-red-600 hover:text-red-800 flex items-center gap-1"
                                            title="حذف الملف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">لا يوجد ملف</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 flex justify-center gap-3">
                                {{-- تعديل --}}
                                <a href="{{ route('financial.expenses.activity.edit', [$activity->id, $exp->id]) }}"
                                    class="flex items-center gap-1 text-yellow-600 hover:text-yellow-800">
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
                            <td colspan="5" class="py-6 text-center text-gray-500">
                                لا توجد مصاريف
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">{{ $expenses->links() }}</div>

        {{-- مجموع المصاريف --}}
        <div class="mt-2 text-right">
            مجموع المصاريف: <strong class="text-green-700">{{ number_format($totalAmount, 2) }}</strong>
        </div>

    </div>

    {{-- Modal for Deletion Confirmation --}}
    <div id="deleteConfirmModal" class="delete-confirm-modal" aria-hidden="true" style="display:none;">
        <div class="delete-confirm-backdrop"></div>
        <div class="delete-confirm-box" role="dialog" aria-modal="true">
            <h3>تأكيد حذف الملف</h3>
            <p id="delete-confirm-message">هل تريد حذف الملف من "<span id="delete-item-name"></span>"؟</p>
            <div class="delete-confirm-actions">
                <button type="button" id="delete-cancel" class="btn btn-secondary">إلغاء</button>
                <form id="delete-receipt-form" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                <button type="button" id="delete-confirm" class="btn btn-danger">تأكيد الحذف</button>
            </div>
        </div>
    </div>

    <script>
        let currentDeleteUrl = null;
        const modal = document.getElementById('deleteConfirmModal');
        const messageEl = document.getElementById('delete-confirm-message');
        const itemNameEl = document.getElementById('delete-item-name');
        const btnConfirm = document.getElementById('delete-confirm');
        const btnCancel = document.getElementById('delete-cancel');
        const deleteForm = document.getElementById('delete-receipt-form');

        function openDeleteConfirm(deleteUrl, itemName) {
            currentDeleteUrl = deleteUrl;
            itemNameEl.textContent = itemName;
            messageEl.innerHTML =
                `هل أنت متأكد أنك تريد حذف الملف من "<strong>${itemName}</strong>"؟ لا يمكن التراجع عن هذا الإجراء.`;
            modal.classList.add('show');
        }

        function closeDeleteConfirm() {
            currentDeleteUrl = null;
            modal.classList.remove('show');
        }

        btnCancel.addEventListener('click', function() {
            closeDeleteConfirm();
        });

        btnConfirm.addEventListener('click', function() {
            if (currentDeleteUrl) {
                deleteForm.action = currentDeleteUrl;
                deleteForm.submit();
            }
        });

        // إغلاق Modal عند الضغط على الخلفية
        modal.addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('delete-confirm-backdrop')) {
                closeDeleteConfirm();
            }
        });

        // إغلاق باستخدام مفتاح Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                closeDeleteConfirm();
            }
        });
    </script>
@endsection
