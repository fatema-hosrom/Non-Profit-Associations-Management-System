@extends('templates.manager_app')

@section('title', 'تفاصيل الفعالية')

@push('styles')
    <link rel="stylesheet" href="/assets/css/activities/activities.css">
    <link rel="stylesheet" href="/assets/css/activities/view_activity.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h5 class="page-title mb-0">تفاصيل الفعالية</h5>
                    <a href="{{ route('manager.activities.index') }}" class="back-btn"><i class="fas fa-arrow-right"></i>
                        العودة للقائمة</a>
                </div>
            </div>

            @if (!empty($activity))
                <div class="activity-details-card">
                    <div class="activity-title-section">
                        <h6 class="activity-title">{{ $activity->title }}</h6>
                    </div>

                    <div class="activity-image-container">
                        @if (!empty($activity->image))
                            <img src="{{ asset('assets/images/activities/' . $activity->image) }}" alt="صورة الفعالية"
                                class="activity-image">
                        @else
                            <div style="background:#f8f9fa; padding:50px; border-radius:10px; color:#6c757d;">
                                <i class="fas fa-image" style="font-size:3rem; margin-bottom:10px;"></i>
                                <p>لا توجد صورة للفعالية</p>
                            </div>
                        @endif
                    </div>

                    <div class="details-grid">
                        <div class="detail-row">
                            <div class="detail-icon"><i class="fas fa-tag"></i></div>
                            <div class="detail-content">
                                <div class="detail-label">نوع الفعالية</div>
                                <div class="detail-value">
                                    @if ($activity->activity_type === 'donation')
                                        تبرع
                                    @elseif($activity->activity_type === 'volunteer')
                                        تطوع
                                    @else
                                        كلاهما
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="detail-content">
                                <div class="detail-label">الموقع</div>
                                <div class="detail-value">{{ $activity->location }}</div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="fas fa-clock"></i></div>
                            <div class="detail-content">
                                <div class="detail-label">حالة الفعالية</div>
                                <div class="detail-value">
                                    @php $today = date('Y-m-d'); @endphp
                                    @if ($today < $activity->start_date)
                                        <span class="activity-status status-upcoming">قادمة</span>
                                    @elseif($today >= $activity->start_date && $today <= $activity->end_date)
                                        <span class="activity-status status-active">نشطة</span>
                                    @else
                                        <span class="activity-status status-completed">منتهية</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="dates-row">
                            <div class="date-item">
                                <div class="date-icon"><i class="fas fa-calendar-day"></i></div>
                                <div class="detail-content">
                                    <div class="detail-label">تاريخ البداية</div>
                                    <div class="detail-value">{{ $activity->start_date }}</div>
                                </div>
                            </div>
                            <div class="date-item">
                                <div class="date-icon"><i class="fas fa-calendar-check"></i></div>
                                <div class="detail-content">
                                    <div class="detail-label">تاريخ النهاية</div>
                                    <div class="detail-value">{{ $activity->end_date }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (!empty($activity->description))
                        <div class="description-section">
                            <div class="detail-label">وصف الفعالية</div>
                            <div class="description-content">{!! nl2br(e($activity->description)) !!}</div>
                        </div>
                    @endif

                    @if ($activity->activity_type == 'donation' || $activity->activity_type == 'both')
                        <hr>
                        <h6><i class="fas fa-hand-holding-usd"></i> بيانات التبرع</h6>
                        <p><strong>الهدف المالي:</strong> {{ optional($activity->donationSettings)->target_amount }} $</p>
                        <p><strong>المبلغ المحصل:</strong> {{ optional($activity->donationSettings)->collected_amount }} $
                        </p>
                        @php
                            $status = optional($activity->donationSettings)->donation_status;
                        @endphp
                        <p><strong>حالة التبرع:</strong>
                            @if ($status === 'open')
                                <span class="badge bg-success">مفتوحة</span>
                            @elseif($status === 'completed')
                                <span class="badge bg-primary">مكتملة</span>
                            @else
                                <span class="badge bg-secondary">مغلقة</span>
                            @endif
                        </p>
                    @endif

                    @if ($activity->activity_type == 'volunteer' || $activity->activity_type == 'both')
                        <hr>
                        <h6><i class="fas fa-users"></i> بيانات التطوع</h6>
                        <p><strong>عدد المتطوعين المطلوب:</strong>
                            {{ optional($activity->volunteerRequirements)->required_volunteers }}</p>
                        <p><strong>عدد المتطوعين الحالي:</strong>
                            {{ optional($activity->volunteerRequirements)->volunteers_count }}</p>
                        <p><strong>العمر الأدنى:</strong> {{ optional($activity->volunteerRequirements)->min_age }}</p>
                        <p><strong>الجنس المطلوب:</strong>
                            {{ ['male' => 'ذكر', 'female' => 'انثى', 'both' => 'كلا الجنسين'][optional($activity->volunteerRequirements)->gender_requirement ?? 'both'] }}
                        </p>
                        <p><strong>طريقة اختيار المتطوعين:</strong>
                            @php $mode = optional($activity->volunteerRequirements)->volunteer_mode; @endphp
                            @if ($mode === 'manual')
                                الأسبقية للحضور
                            @elseif($mode === 'auto')
                                اختيار المتطوعين
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>المهارات المطلوبة:</strong>
                            {{ optional($activity->volunteerRequirements)->skills_required }}</p>
                        <p><strong>عدد الساعات الأدنى:</strong> {{ optional($activity->volunteerRequirements)->min_hours }}
                        </p>
                    @endif
                </div>
            @else
                <div class="text-center text-muted">
                    <i class="fas fa-exclamation-triangle mb-3" style="font-size:3rem;"></i>
                    <h4>الفعالية غير موجودة</h4>
                    <p>عذراً، الفعالية المطلوبة غير موجودة أو تم حذفها.</p>
                    <a href="{{ route('manager.activities.index') }}" class="back-btn"><i class="fas fa-arrow-right"></i>
                        العودة للقائمة</a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
@endpush
