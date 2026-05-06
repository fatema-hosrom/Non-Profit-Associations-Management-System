@extends('templates.supervisor_app')

@section('title', 'تعديل المدير')

@push('styles')
    <link rel="stylesheet" href="/assets/css/supervisor/edit_manager.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('supervisor.managers.index') }}" class="back-btn"><i class="fas fa-arrow-right"></i> العودة إلى
                قائمة المدراء</a>

            <div class="form-card">
                <h2 class="form-title">تعديل بيانات المدير</h2>

                @if (session('success'))
                    <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST"
                    action="{{ route('supervisor.managers.edit', $manager['id'] ?? ($manager->id ?? null)) }}">@csrf
                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-user"></i><label class="form-label">الاسم
                                    الكامل</label></div>
                            <input type="text" class="form-control" name="full_name" required
                                value="{{ $manager['full_name'] ?? ($manager->full_name ?? '') }}">
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-user-tag"></i><label class="form-label">اسم
                                    المستخدم</label></div>
                            <input type="text" class="form-control" name="username" required
                                value="{{ $manager['username'] ?? ($manager->username ?? '') }}">
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-envelope"></i><label class="form-label">البريد
                                    الإلكتروني</label></div>
                            <input type="email" class="form-control" name="email" required
                                value="{{ $manager['email'] ?? ($manager->email ?? '') }}">
                        </div>


                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-phone"></i><label class="form-label">رقم
                                    الهاتف</label></div>
                            <input type="tel" class="form-control" name="phone"
                                value="{{ $manager['phone'] ?? ($manager->phone ?? '') }}">
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-key"></i><label class="form-label">كلمة المرور
                                    الجديدة</label></div>
                            <input type="password" class="form-control" name="new_password"
                                placeholder="اتركها فارغة إذا لم ترد تغييرها">
                        </div>


                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-toggle-on"></i><label
                                    class="form-label">الحالة</label></div>
                            <select class="form-control" name="status" required>
                                <option value="active"
                                    {{ ($manager['status'] ?? ($manager->status ?? '')) == 'active' ? 'selected' : '' }}>نشط
                                </option>
                                <option value="inactive"
                                    {{ ($manager['status'] ?? ($manager->status ?? '')) == 'inactive' ? 'selected' : '' }}>
                                    غير نشط</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-center mt-4"><button type="submit" class="btn btn-submit"><i class="fas fa-save"></i>
                            حفظ التغييرات</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/js/bootstrap.js"></script>
@endpush
