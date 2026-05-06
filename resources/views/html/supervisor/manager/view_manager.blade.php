@extends('templates.supervisor_app')

@section('title', 'عرض المدير')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/supervisor/view-manager.css') }}">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('supervisor.managers.index') }}" class="back-btn"><i class="fas fa-arrow-right"></i> العودة إلى
                قائمة المدراء</a>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="profile-card">
                        <div class="profile-header">
                            <h2 class="profile-name">{{ $manager['full_name'] }}</h2>
                            <div class="profile-role">
                                <span
                                    class="status-badge {{ $manager['status'] == 'active' ? 'status-active' : 'status-inactive' }}">
                                    <i
                                        class="fas {{ $manager['status'] == 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $manager['status'] == 'active' ? 'مفعل' : 'معطل' }}
                                </span>
                            </div>
                        </div>

                        <div class="info-section">
                            <h3 class="info-title">المعلومات الشخصية</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                                    <div class="info-text">
                                        <div class="info-label">البريد الإلكتروني</div>
                                        <div class="info-value">{{ $manager['email'] }}</div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon"><i class="fas fa-phone"></i></div>
                                    <div class="info-text">
                                        <div class="info-label">رقم الهاتف</div>
                                        <div class="info-value">{{ $manager['phone'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-section">
                            <h3 class="info-title">معلومات الحساب</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-icon"><i class="fas fa-user"></i></div>
                                    <div class="info-text">
                                        <div class="info-label">اسم المستخدم</div>
                                        <div class="info-value">{{ $manager['username'] }}</div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon"><i class="fas fa-user-tag"></i></div>
                                    <div class="info-text">
                                        <div class="info-label">الدور</div>
                                        <div class="info-value">
                                            @php
                                                $role_name = match ($manager['manager_type']) {
                                                    'financial' => 'مدير مالي',
                                                    'activities' => 'مدير أنشطة',
                                                    default => 'غير محدد',
                                                };
                                            @endphp
                                            <span class="manager-role">{{ $role_name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon"><i class="fas fa-calendar"></i></div>
                                    <div class="info-text">
                                        <div class="info-label">تاريخ التسجيل</div>
                                        <div class="info-value">
                                            {{ \Carbon\Carbon::parse($manager['created_at'])->format('Y-m-d') }}</div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon"><i class="fas fa-info-circle"></i></div>
                                    <div class="info-text">
                                        <div class="info-label">الحالة</div>
                                        <div class="info-value">
                                            <span
                                                class="status-badge {{ $manager['status'] == 'active' ? 'status-active' : 'status-inactive' }}">
                                                <i
                                                    class="fas {{ $manager['status'] == 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                                {{ $manager['status'] == 'active' ? 'مفعل' : 'معطل' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
