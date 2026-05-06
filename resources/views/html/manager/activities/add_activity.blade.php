@extends('templates.manager_app')

@section('title', 'إضافة فعالية جديدة - نظام إدارة الجمعيات')

@push('styles')
    <link rel="stylesheet" href="/assets/css/activities/add_activity.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('manager.activities.index') }}" class="back-btn"><i class="fas fa-arrow-right"></i> العودة إلى
                قائمة الفعاليات</a>
            <div class="form-card">
                <h4 class="form-title">إضافة فعالية جديدة</h4>

                @if (request()->query('error'))
                    <div class="alert alert-danger text-center" role="alert">{{ request()->query('error') }}</div>
                @endif

                <form method="POST" enctype="multipart/form-data" action="{{ route('manager.activities.store') }}">@csrf

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-heading"></i><label class="form-label">عنوان
                                    الفعالية</label></div>
                            <input type="text" class="form-control" name="title" required style="width:95%">
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-calendar-day"></i><label
                                    class="form-label">تاريخ البداية</label></div>
                            <input type="datetime-local" class="form-control" name="start_date" required>
                        </div>
                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-calendar-check"></i><label
                                    class="form-label">تاريخ النهاية</label></div>
                            <input type="datetime-local" class="form-control" name="end_date" required>
                        </div>
                    </div>
                    <div class="form-row">

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-map-marker-alt"></i><label
                                    class="form-label">الموقع</label></div>
                            <input type="text" class="form-control" name="location" required style="width:95%">
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-image"></i><label class="form-label">صورة
                                    الفعالية</label></div>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-align-right"></i><label
                                    class="form-label">الوصف</label></div>
                            <textarea style="height: 140px;" class="form-control" name="description"></textarea>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="input-container d-flex flex-column align-items-center">
                            <div class="input-group-label" style="justify-content: center; align-items: center;"><i
                                    class="fas fa-tag"></i><label class="form-label">نوع الفعالية</label></div>
                            <select id="activity_type" name="activity_type" class="form-control" required
                                style="max-width: fit-content">
                                <option value="">اختر نوع الفعالية</option>
                                <option value="donation">تبرع</option>
                                <option value="volunteer">تطوع</option>
                                <option value="both">كلاهما</option>
                            </select>
                            <div class="input-container" style="align-self:flex-start;">
                                <div class="input-group-label">
                                    <div id="donation-fields" style="display:none;"><i
                                            class="fas fa-hand-holding-usd"></i><label>الهدف المالي</label><input
                                            type="number" name="target_amount" class="form-control"></div>
                                </div>
                            </div>

                            <div id="volunteer-fields" style="display:none;">

                                <div class="row g-3 mt-3">
                                    <div class="col-12 col-md-3">
                                        <div class="input-container" style="min-width:0;">
                                            <div class="input-group-label"><label>عدد المتطوعين المطلوب</label></div><input
                                                type="number" name="required_volunteers" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="input-container" style="min-width:0;">
                                            <div class="input-group-label"><i class="fas fa-birthday-cake"></i><label>العمر
                                                    الأدنى</label></div><input type="number" name="min_age"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="input-container" style="min-width:0;">
                                            <div class="input-group-label"><i class="fas fa-venus-mars"></i><label>الجنس
                                                    المطلوب</label></div><select name="gender_requirement"
                                                class="form-control">
                                                <option value="male">ذكر</option>
                                                <option value="female">أنثى</option>
                                                <option value="both" selected>كلا الجنسين</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="input-container" style="min-width:0;">
                                            <div class="input-group-label"><i class="fas fa-clock"></i><label>عدد الساعات
                                                    الأدنى</label></div><input type="number" name="min_hours"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class='col-12 col-md-3'>
                                        <div class="input-container" style="min-width:0;">
                                            <div class="input-group-label"><i class="fas fa-flag"></i><label>طريقة
                                                    التعيين</label></div><select name="volunteer_mode"
                                                class="form-control">
                                                <option value="auto">الأسبقية للحضور</option>
                                                <option value="manual">اختيار المتطوعين</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <div class="input-container" style="min-width:500px;">
                                            <div class="input-group-label"><i class="fas fa-tools"></i><label>المهارات
                                                    المطلوبة</label></div>
                                            <textarea name="skills_required" class="form-control" style="min-height:100px;"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.getElementById('activity_type').addEventListener('change', function() {
                            let type = this.value;
                            document.getElementById('donation-fields').style.display = (type === 'donation' || type === 'both') ?
                                'block' : 'none';
                            document.getElementById('volunteer-fields').style.display = (type === 'volunteer' || type === 'both') ?
                                'block' : 'none';
                        });
                    </script>

                    <div class="text-center mt-4"><button type="submit" class="btn btn-submit"><i
                                class="fas fa-plus"></i> إضافة الفعالية</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/js/bootstrap.js"></script>
@endpush
