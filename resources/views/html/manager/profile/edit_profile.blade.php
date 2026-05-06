@extends('templates.manager_app')

@section('title', 'تعديل الملف الشخصي')

@push('styles')
    <link rel="stylesheet" href="/assets/css/manager/profile.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('manager.profile') }}" class="back-btn"><i class="fas fa-arrow-right"></i> العودة إلى الملف
                الشخصي</a>


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

            <div class="profile-card">
                <h2 class="profile-title">تعديل الملف الشخصي</h2>

                <form id="profileEditForm" action="{{ route('manager.profile.edit') }}" method="POST">@csrf
                    @method('PUT')

                    <div class="profile-info">
                        <div class="info-item">
                            <div class="info-group">
                                <div class="info-label"><i class="fas fa-user"></i><span>الاسم الكامل</span></div>
                                <input type="text" name="full_name" class="info-value"
                                    value="{{ old('full_name', $manager->full_name) }}" required>
                            </div>

                            <div class="info-group">
                                <div class="info-label"><i class="fas fa-user-tag"></i><span>اسم المستخدم</span></div>
                                <input type="text" name="username" class="info-value"
                                    value="{{ old('username', $manager->username) }}" required>
                            </div>

                            <div class="info-group">
                                <div class="info-label"><i class="fas fa-envelope"></i><span>البريد الإلكتروني</span></div>
                                <input type="email" name="email" class="info-value"
                                    value="{{ old('email', $manager->email) }}" required>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-group">
                                <div class="info-label"><i class="fas fa-phone"></i><span>رقم الهاتف</span></div>
                                <input type="text" name="phone" class="info-value"
                                    value="{{ old('phone', $manager->phone) }}">
                            </div>

                            <div class="info-group">
                                <div class="info-label"><i class="fas fa-lock"></i><span>كلمة المرور الجديدة</span></div>
                                <input type="password" name="password" class="info-value"
                                    placeholder="اتركها فارغة إن لم ترغب بالتغيير">
                            </div>

                            <div class="info-group">
                                <div class="info-label"><i class="fas fa-lock"></i><span>تأكيد كلمة المرور</span></div>
                                <input type="password" name="password_confirmation" class="info-value"
                                    placeholder="أعد كتابة كلمة المرور">
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="edit-btn" data-bs-toggle="modal" data-bs-target="#confirmEditModal"><i
                                class="fas fa-save"></i> حفظ التعديلات</button>
                    </div>

                    <div class="modal fade" id="confirmEditModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="font-family: 'Tajawal', sans-serif;">
                                <div class="modal-header justify-content-center border-0">
                                    <h5 class="modal-title text-primary fw-bold">تأكيد التعديل</h5>
                                </div>
                                <div class="modal-body text-center">
                                    <p class="mb-2">هل أنت متأكد أنك تريد حفظ التعديلات على الملف الشخصي؟</p>
                                    <p class="text-muted small">يرجى التأكد من صحة البيانات قبل المتابعة.</p>
                                </div>
                                <div class="modal-footer justify-content-center border-0">
                                    <button type="button" class="btn btn-outline-secondary px-4"
                                        data-bs-dismiss="modal">إلغاء</button>
                                    <button type="button" class="btn btn-primary px-4"
                                        onclick="document.getElementById('profileEditForm').submit();">تأكيد الحفظ</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
@endpush
