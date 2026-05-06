@extends('templates.manager_app')

@section('title', 'تعديل الفعالية - نظام إدارة الجمعيات')

@push('styles')
    <link rel="stylesheet" href="/assets/css/activities/edit_activity.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('manager.activities.index') }}" class="back-btn"><i class="fas fa-arrow-right"></i> العودة إلى
                قائمة الفعاليات</a>
            <div class="form-card">
                <h4 class="form-title">تعديل الفعالية</h4>

                @if (session('error'))
                    <div class="alert alert-danger text-center" role="alert">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success text-center" role="alert">{{ session('success') }}</div>
                @endif

                <form method="POST" enctype="multipart/form-data"
                    action="{{ route('manager.activities.update', $activity->id) }}">@csrf @method('PUT')

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-heading"></i><label class="form-label">عنوان
                                    الفعالية</label></div>
                            <input type="text" class="form-control" name="title" value="{{ $activity->title }}"
                                required>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-calendar-day"></i><label
                                    class="form-label">تاريخ البداية</label></div>
                            <input type="datetime-local" class="form-control" name="start_date"
                                value="{{ date('Y-m-d\TH:i', strtotime($activity->start_date)) }}" required>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-calendar-check"></i><label
                                    class="form-label">تاريخ النهاية</label></div>
                            <input type="datetime-local" class="form-control" name="end_date"
                                value="{{ date('Y-m-d\TH:i', strtotime($activity->end_date)) }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-map-marker-alt"></i><label
                                    class="form-label">الموقع</label></div>
                            <input type="text" class="form-control" name="location" value="{{ $activity->location }}"
                                required>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-image"></i><label class="form-label">صورة
                                    الفعالية</label></div>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            @if ($activity->image)
                                <div class="mt-2"><label>الصورة الحالية:</label><br><img
                                        src="{{ asset('assets/images/activities/' . $activity->image) }}"
                                        style="max-width:200px; border:1px solid #ccc; padding:5px;"></div>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label"><i class="fas fa-align-right"></i><label
                                    class="form-label">الوصف</label></div>
                            <textarea class="form-control" name="description" style="height:140px;">{{ $activity->description }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-container d-flex flex-column align-items-center">
                            <div class="input-group-label"><i class="fas fa-tag"></i><label class="form-label">نوع
                                    الفعالية</label></div>
                            <select id="activity_type" name="activity_type" class="form-control" required
                                style="max-width:fit-content">
                                <option value="donation" {{ $activity->activity_type == 'donation' ? 'selected' : '' }}>
                                    تبرع</option>
                                <option value="volunteer" {{ $activity->activity_type == 'volunteer' ? 'selected' : '' }}>
                                    تطوع</option>
                                <option value="both" {{ $activity->activity_type == 'both' ? 'selected' : '' }}>كلاهما
                                </option>
                            </select>

                            <div id="donation-fields" style="display:none; align-self:flex-start;"><i
                                    class="fas fa-hand-holding-usd"></i><label>الهدف المالي</label><input type="number"
                                    name="target_amount" class="form-control"
                                    value="{{ optional($activity->donationSettings)->target_amount }}"></div>

                            <div id="volunteer-fields" style="display:none;">
                                <div class="row g-3 mt-3">
                                    <div class="col-12 col-md-3"><label>عدد المتطوعين المطلوب</label><input type="number"
                                            name="required_volunteers" class="form-control"
                                            value="{{ optional($activity->volunteerRequirements)->required_volunteers }}">
                                    </div>
                                    <div class="col-12 col-md-3"><label>العمر الأدنى</label><input type="number"
                                            name="min_age" class="form-control"
                                            value="{{ optional($activity->volunteerRequirements)->min_age }}"></div>
                                    <div class="col-12 col-md-3"><label>الجنس المطلوب</label><select
                                            name="gender_requirement" class="form-control">
                                            <option value="male"
                                                {{ (optional($activity->volunteerRequirements)->gender_requirement ?? '') == 'male' ? 'selected' : '' }}>
                                                ذكر</option>
                                            <option value="female"
                                                {{ (optional($activity->volunteerRequirements)->gender_requirement ?? '') == 'female' ? 'selected' : '' }}>
                                                أنثى</option>
                                            <option value="both"
                                                {{ (optional($activity->volunteerRequirements)->gender_requirement ?? '') == 'both' ? 'selected' : '' }}>
                                                كلا الجنسين</option>
                                        </select></div>
                                    <div class="col-12 col-md-3"><label>عدد الساعات الأدنى</label><input type="number"
                                            name="min_hours" class="form-control"
                                            value="{{ optional($activity->volunteerRequirements)->min_hours }}"></div>
                                    <div class="col-12 col-md-3">
                                        <div class="input-group-label"><i class="fas fa-flag"></i><label>طريقة
                                                التعيين</label></div><select name="volunteer_mode" class="form-control">
                                            <option value="auto"
                                                {{ (optional($activity->volunteerRequirements)->volunteer_mode ?? '') == 'manual' ? 'selected' : '' }}>
                                                الأسبقية للحضور</option>
                                            <option value="manual"
                                                {{ (optional($activity->volunteerRequirements)->volunteer_mode ?? '') == 'auto' ? 'selected' : '' }}>
                                                اختيار المتطوعين</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3" style="min-width:500px;"><label>المهارات المطلوبة</label>
                                        <textarea name="skills_required" class="form-control" style="min-height:100px;">{{ optional($activity->volunteerRequirements)->skills_required }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function toggleFields(type) {
                            document.getElementById('donation-fields').style.display = (type === 'donation' || type === 'both') ? 'block' :
                                'none';
                            document.getElementById('volunteer-fields').style.display = (type === 'volunteer' || type === 'both') ?
                                'block' : 'none';
                        }
                        let typeSelect = document.getElementById('activity_type');
                        toggleFields(typeSelect.value);
                        typeSelect.addEventListener('change', function() {
                            toggleFields(this.value);
                        });
                    </script>

                    <div class="text-center mt-4"><button type="submit" class="btn btn-submit"><i
                                class="fas fa-save"></i> حفظ التغييرات</button></div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/js/bootstrap.js"></script>
@endpush
