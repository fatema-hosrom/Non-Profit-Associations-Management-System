@extends('templates.financial_app')

@section('title', 'تعديل مصروف')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/financial/edit-expense.css') }}">
@endpush

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-4xl">

        <div class="form-card mb-6">
            <div class="form-header">
                <h1 class="text-2xl font-bold mb-2 flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    تعديل مصروف
                </h1>
                <p class="text-lg opacity-90">الفعالية: {{ $activity->title }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('financial.expenses.activity.update', [$activity->id, $expense->id]) }}"
            enctype="multipart/form-data" onsubmit="return confirm('هل أنت متأكد من تعديل هذا المصروف؟');">
            @csrf
            @method('PUT')

            <div class="form-card">
                <div class="form-body">
                    <div class="form-grid">
                        @php
                            $receiptNumber = old('receipt_number', $expense->receipt_number);
                        @endphp
                        <div class="form-group">
                            <label>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                رقم الإيصال (غير قابل للتعديل)
                            </label>
                            <input type="text" value="{{ $receiptNumber }}" class="form-control" readonly>
                            <input type="hidden" name="receipt_number" value="{{ $receiptNumber }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                الوصف
                            </label>
                            <input type="text" name="description" value="{{ old('description', $expense->description) }}"
                                class="form-control" required placeholder="وصف المصروف">
                        </div>

                        <div class="form-group">
                            <label>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                                المبلغ ($)
                            </label>
                            <input type="number" step="0.01" name="amount"
                                value="{{ old('amount', $expense->amount) }}" class="form-control" required
                                placeholder="0.00">
                        </div>

                        <div class="form-group">
                            <label>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                تاريخ المصروف
                            </label>
                            <input type="date" name="expense_date"
                                value="{{ old('expense_date', $expense->expense_date) }}" class="form-control" required>
                        </div>

                        <div class="form-group file-upload">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <div class="file-upload-label">انقر لاختيار ملف الإيصال الجديد</div>
                            <div class="text-xs text-gray-500 mt-1">يدعم الصور والملفات (JPG, PNG, PDF)</div>
                            @if ($expense->receipt)
                                <div class="text-sm text-green-600 mt-2">الإيصال الحالي: موجود</div>
                            @endif
                            <input type="file" id="receipt" name="receipt" accept="image/*,.pdf">
                            <div id="file-name" class="text-sm text-blue-600 mt-2 font-medium"></div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="button" onclick="openEditConfirm()" class="btn-submit mx-auto" id="submit-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            تعديل
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    {{-- Modal for Edit Confirmation --}}
    <div id="editConfirmModal" class="edit-confirm-modal" aria-hidden="true">
        <div class="edit-confirm-backdrop"></div>
        <div class="edit-confirm-box" role="dialog" aria-modal="true">
            <h3>تأكيد التعديل</h3>
            <p id="edit-confirm-message">هل أنت متأكد أنك تريد تعديل هذا المصروف؟</p>
            <div class="edit-confirm-actions">
                <button type="button" id="edit-cancel" class="btn btn-secondary">إلغاء</button>
                <button type="button" id="edit-confirm" class="btn btn-primary">تأكيد التعديل</button>
            </div>
        </div>
    </div>

    <script>
        const editForm = document.querySelector('form');
        const editModal = document.getElementById('editConfirmModal');
        const editBtnCancel = document.getElementById('edit-cancel');
        const editBtnConfirm = document.getElementById('edit-confirm');

        function openEditConfirm() {
            editModal.classList.add('show');
        }

        function closeEditConfirm() {
            editModal.classList.remove('show');
        }

        editBtnCancel.addEventListener('click', function() {
            closeEditConfirm();
        });

        editBtnConfirm.addEventListener('click', function() {
            editForm.submit();
        });

        // إغلاق Modal عند الضغط على الخلفية
        editModal.addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('edit-confirm-backdrop')) {
                closeEditConfirm();
            }
        });

        // إغلاق باستخدام مفتاح Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && editModal.classList.contains('show')) {
                closeEditConfirm();
            }
        });

        // فتح حقل الملف عند النقر على الـ div
        document.querySelector('.file-upload').addEventListener('click', function(e) {
            if (e.target.tagName !== 'INPUT') {
                document.getElementById('receipt').click();
            }
        });

        // عرض اسم الملف المختار
        document.getElementById('receipt').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('file-name').textContent = fileName ? 'تم اختيار: ' + fileName : '';
        });
    </script>
@endsection
