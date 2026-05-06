@extends('templates.supervisor_app')

@section('title', 'إضافة مدير جديد')

@push('styles')
    <link rel="stylesheet" href="/assets/css/supervisor/add_manager.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('supervisor.managers.index') }}" class="back-btn"><i class="fas fa-arrow-right"></i> العودة إلى
                قائمة المدراء</a>

            <div class="form-card">
                <h2 class="form-title">إضافة مدير جديد</h2>

                @if (session('success'))
                    <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('supervisor.managers.add') }}">@csrf
                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-user"></i><label class="form-label">الاسم
                                    الكامل</label></div>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-user-tag"></i><label class="form-label">اسم
                                    المستخدم</label></div>
                            <input type="text" class="form-control" name="username" required>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-envelope"></i><label class="form-label">البريد
                                    الإلكتروني</label></div>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-phone"></i><label class="form-label">رقم
                                    الهاتف</label></div>
                            <input type="tel" class="form-control" name="phone">
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-key"></i><label class="form-label">كلمة
                                    المرور</label></div>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-user-shield"></i><label class="form-label">نوع
                                    المدير</label></div>
                            <select class="form-control" name="manager_type" required>
                                <option value="">اختر نوع المدير</option>
                                <option value="financial"
                                    {{ isset($manager_type) && $manager_type == 'financial' ? 'selected' : '' }}>مدير مالي
                                </option>
                                <option value="activities"
                                    {{ isset($manager_type) && $manager_type == 'activities' ? 'selected' : '' }}>مدير أنشطة
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="text-center mt-4"><button type="submit" class="btn btn-submit"><i class="fas fa-plus"></i>
                            إضافة المدير</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/js/bootstrap.js"></script>
@endpush
