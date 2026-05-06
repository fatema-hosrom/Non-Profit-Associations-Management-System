@extends('templates.supervisor_app')

@section('title', 'الملف الشخصي')

@push('styles')
    <link rel="stylesheet" href="../../assets/css/supervisor/profile.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('supervisor.dashboard') }}" class="back-btn"><i class="fas fa-arrow-right"></i> العودة إلى لوحة
                التحكم</a>


            @if (session('success'))
                <div style="text-align: center; margin-bottom: 15px;" class="alert alert-success flash-message" role="alert"
                    id="flash-success">
                    <span style="color: #28a745; font-size: 15px;"><i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}</span>
                </div>
            @endif



            <div class="profile-card">
                <h2 class="profile-title">الملف الشخصي</h2>
                <div class="profile-info">
                    <div class="info-item">
                        <div class="info-group">
                            <div class="info-label"><i class="fas fa-user"></i><span>الاسم الكامل</span></div>
                            <div class="info-value">{{ $supervisor->full_name ?? '' }}</div>
                        </div>
                        <div class="info-group">
                            <div class="info-label"><i class="fas fa-user-tag"></i><span>اسم المستخدم</span></div>
                            <div class="info-value">{{ $supervisor['username'] ?? ($supervisor->username ?? '') }}</div>
                        </div>
                        <div class="info-group">
                            <div class="info-label"><i class="fas fa-envelope"></i><span>البريد الإلكتروني</span></div>
                            <div class="info-value">{{ $supervisor['email'] ?? ($supervisor->email ?? '') }}</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-group">
                            <div class="info-label"><i class="fas fa-phone"></i><span>رقم الهاتف</span></div>
                            <div class="info-value">{{ $supervisor['phone'] ?? ($supervisor->phone ?? '') }}</div>
                        </div>
                        <div class="info-group">
                            <div class="info-label"><i class="fas fa-calendar-alt"></i><span>تاريخ الانضمام</span></div>
                            <div class="info-value">
                                {{ isset($supervisor['created_at']) ? date('Y/m/d', strtotime($supervisor['created_at'])) : (isset($supervisor->created_at) ? $supervisor->created_at->format('Y/m/d') : '') }}
                            </div>
                        </div>
                        <div class="info-group">
                            <div class="info-label"><i class="fas fa-clock"></i><span>آخر تحديث</span></div>
                            <div class="info-value">
                                {{ isset($supervisor['updated_at']) ? date('Y/m/d', strtotime($supervisor['updated_at'])) : (isset($supervisor->updated_at) ? $supervisor->updated_at->format('Y/m/d') : '') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4"><a href="{{ route('supervisor.profile.edit') }}" class="edit-btn"><i
                            class="fas fa-edit"></i> تعديل الملف الشخصي</a></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/js/bootstrap.js"></script>
    @if (session('success'))
        <script>
            setTimeout(function() {
                var successMessage = document.getElementById('successMessage');
                if (successMessage) {
                    successMessage.style.display = 'none';
                }
            }, 7000);
        </script>
    @endif
@endpush
