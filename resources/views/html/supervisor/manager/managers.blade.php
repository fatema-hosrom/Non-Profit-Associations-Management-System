@extends('templates.supervisor_app')

@section('title', 'قائمة المدراء')

@push('styles')
    <link rel="stylesheet" href="/assets/css/supervisor/managers.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h2 class="page-title mb-0">إدارة المدراء</h2>
                    <button class="btn add-btn" onclick="window.location.href='{{ route('supervisor.managers.add') }}'"><i
                            class="fas fa-plus"></i> إضافة مدير جديد</button>
                </div>
            </div>

            @if (session('success'))
                <div style="text-align: center; margin-bottom: 15px;" class="alert alert-success flash-message"
                    role="alert" id="flash-success">
                    <span style="color: #28a745; font-size: 15px;"><i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger flash-message" role="alert" id="flash-error"><strong>خطأ:</strong>
                    {{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger flash-message" role="alert" id="flash-errors"><strong>تحذير:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                @foreach ($managers as $manager)
                    <div class="manager-card">
                        <div class="manager-header">
                            <h4 class="manager-name"><i class="fas fa-user-circle"></i> {{ $manager->full_name }}</h4>
                            <span class="manager-role"><i class="fas fa-user-tie"></i>
                                @php
                                    $type = $manager->manager_type ?? '';
                                    $role_name =
                                        $type === 'financial'
                                            ? 'مدير مالي'
                                            : ($type === 'activities'
                                                ? 'مدير أنشطة'
                                                : 'غير محدد');
                                @endphp
                                {{ $role_name }}
                            </span>
                        </div>
                        <div class="manager-info"><i class="fas fa-envelope"></i> {{ $manager->email }}</div>
                        <div class="manager-info"><i class="fas fa-user"></i> {{ $manager->username }}</div>
                        @php $st = $manager->status ?? 'inactive'; @endphp
                        <div class="manager-info"><i class="fas fa-clock"></i>
                            <span class="status-badge {{ $st == 'active' ? 'status-active' : 'status-inactive' }}"><i
                                    class="fas {{ $st == 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                {{ $st == 'active' ? 'مفعل' : 'معطل' }}</span>
                        </div>
                        <div class="action-buttons">
                            <button class="btn action-btn view-btn"
                                onclick="window.location.href='{{ route('supervisor.managers.show', $manager->id) }}'"><i
                                    class="fas fa-eye"></i> عرض</button>
                            <button class="btn action-btn edit-btn"
                                onclick="window.location.href='{{ route('supervisor.managers.edit', $manager->id) }}'"><i
                                    class="fas fa-edit"></i> تعديل</button>
                            <form id="delete-manager-form-{{ $manager->id }}"
                                action="{{ route('supervisor.managers.destroy', $manager->id) }}" method="POST"
                                style="display:inline;">@csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-delete-manager"
                                    data-form-id="delete-manager-form-{{ $manager->id }}"
                                    data-name="{{ $manager->full_name }}">حذف</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="deleteConfirmModal" class="delete-confirm-modal" aria-hidden="true" style="display:none;">
                <div class="delete-confirm-backdrop"></div>
                <div class="delete-confirm-box" role="dialog" aria-modal="true">
                    <h3>تأكيد الحذف</h3>
                    <p id="delete-confirm-message">هل تريد حذف العنصر؟</p>
                    <div class="delete-confirm-actions">
                        <button type="button" id="delete-cancel" class="btn btn-secondary">إلغاء</button>
                        <button type="button" id="delete-confirm" class="btn btn-danger">تأكيد الحذف</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentForm = null;
            const modal = document.getElementById('deleteConfirmModal');
            const message = document.getElementById('delete-confirm-message');
            const btnConfirm = document.getElementById('delete-confirm');
            const btnCancel = document.getElementById('delete-cancel');

            document.querySelectorAll('.btn-delete-manager').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const formId = this.dataset.formId;
                    const name = this.dataset.name || 'هذا العنصر';
                    currentForm = document.getElementById(formId);
                    message.textContent = `هل أنت متأكد أنك تريد حذف "${name}"؟`;
                    modal.style.display = 'flex';
                });
            });

            btnCancel.addEventListener('click', function() {
                currentForm = null;
                modal.style.display = 'none';
            });
            btnConfirm.addEventListener('click', function() {
                if (currentForm) {
                    currentForm.submit();
                }
            });
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    currentForm = null;
                    modal.style.display = 'none';
                }
            });
        });

        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(el => {
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);
    </script>

    <script src="/assets/js/supervisor/managers.js"></script>
    <script src="/assets/js/bootstrap.js"></script>
@endpush
