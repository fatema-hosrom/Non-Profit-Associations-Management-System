@extends('templates.supervisor_app')

@section('title', 'تعيين الدور')

@push('styles')
    <link rel="stylesheet" href="/assets/css/supervisor/assign_role.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('supervisor.managers.index') }}" class="back-btn"><i class="fas fa-arrow-right"></i> العودة إلى
                قائمة المدراء</a>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="form-card">
                        <h2 class="form-title">تحديد أدوار المدراء</h2>

                        @if (session('success'))
                            <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>
                                {{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('supervisor.assignRole') }}" id="assignRoleForm">@csrf
                            <div class="form-group">
                                <div class="input-group-label"><i class="fas fa-user-tie"></i><label class="form-label">اختر
                                        المدير</label></div>
                                <select class="form-control" name="manager_id" id="manager_id" required>
                                    <option value="">اختر المدير</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager['id'] ?? ($manager->id ?? '') }}">
                                            {{ $manager['full_name'] ?? ($manager->full_name ?? '') }}
                                            ({{ ($manager['manager_type'] ?? ($manager->manager_type ?? '')) == 'financial' ? 'مدير مالي' : (($manager['manager_type'] ?? ($manager->manager_type ?? '')) == 'activities' ? 'مدير فعاليات' : 'غير محدد') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="input-group-label"><i class="fas fa-user-shield"></i><label
                                        class="form-label">نوع المدير</label></div>
                                <select class="form-control" name="manager_type" id="manager_type" required>
                                    <option value="">اختر نوع المدير</option>
                                    <option value="financial" {{ old('manager_type') == 'financial' ? 'selected' : '' }}>
                                        مدير مالي</option>
                                    <option value="activities" {{ old('manager_type') == 'activities' ? 'selected' : '' }}>
                                        مدير فعاليات</option>
                                </select>
                            </div>

                            <div class="text-center"><button type="submit" class="btn btn-submit"><i
                                        class="fas fa-save"></i> حفظ التغييرات</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal تأكيد التعديل -->
    <div class="modal fade confirmation-modal" id="confirmationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تأكيد تحديث الدور</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">هل أنت متأكد من تحديث دور المدير؟</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" onclick="submitForm()">تأكيد</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="/assets/js/bootstrap.js"></script>
    <script>
        function confirmSubmit() {
            if (!document.getElementById('manager_id').value || !document.getElementById('manager_type').value) {
                alert('الرجاء اختيار المدير ونوع المدير');
                return;
            }
            var modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            modal.show();
        }

        function submitForm() {
            document.getElementById('assignRoleForm').submit();
        }
    </script>
@endpush
