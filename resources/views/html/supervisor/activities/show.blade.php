@extends('templates.supervisor_app')

@section('title', 'تفاصيل الفعالية - المشرف')

@push('styles')
    <link href="/assets/css/supervisor/activities.css" rel="stylesheet">
@endpush

@section('content')
    <div class="main-content py-3">
        <div class="container">
            <a href="{{ route('supervisor.activities.index') }}" class="btn btn-light border rounded-pill mb-3 px-3">
                <i class="fas fa-arrow-right ms-1"></i> العودة للقائمة
            </a>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <h4 class="fw-bold text-primary mb-4"><i class="fas fa-bolt ms-1"></i> تفاصيل الفعالية</h4>
                <div class="d-flex align-items-center gap-4 mb-4 flex-wrap">
                    @if (!empty($activity->image ?? ($activity['image'] ?? null)))
                        <img src="{{ asset('assets/images/activities/' . ($activity->image ?? $activity['image'])) }}"
                            class="activity-img-preview rounded-4 border" alt="صورة الفعالية">
                    @else
                        <span class="text-muted"><i class="fas fa-image fa-3x"></i></span>
                    @endif
                    <div>
                        <h4 class="fw-bold mb-1">{{ $activity->title ?? $activity['title'] }}</h4>
                        <div class="text-muted mb-2">{{ $activity->activity_type ?? $activity['activity_type'] }}</div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="fas fa-user-tie ms-1"></i>
                            {{ $activity->manager->full_name ?? ($activity['manager']['full_name'] ?? '-') }}</span>
                    </div>
                </div>
                <div class="mb-3 p-3 rounded-3 bg-light border"><strong class="d-block mb-1">الوصف:</strong>
                    <p class="mb-0">{{ $activity->description ?? $activity['description'] }}</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle ms-1"></i> معلومات الفعالية</h6>
                        <div class="p-3 rounded-3 bg-light border mb-2"><i class="fas fa-map-marker-alt ms-1 text-secondary"></i> الموقع:
                            <span>{{ $activity->location ?? $activity['location'] }}</span>
                        </div>
                        <div class="p-3 rounded-3 bg-light border mb-2"><i class="fas fa-calendar-day ms-1 text-secondary"></i> البداية:
                            <span>{{ $activity->start_date ?? $activity['start_date'] }}</span>
                        </div>
                        <div class="p-3 rounded-3 bg-light border mb-2"><i class="fas fa-calendar-check ms-1 text-secondary"></i> النهاية:
                            <span>{{ $activity->end_date ?? $activity['end_date'] }}</span>
                        </div>
                        <div class="p-3 rounded-3 bg-light border mb-2"><i class="fas fa-toggle-on ms-1 text-secondary"></i> الحالة:
                            <span>{{ $activity->status ?? $activity['status'] }}</span>
                        </div>
                        <div class="p-3 rounded-3 bg-light border"><i class="fas fa-eye ms-1 text-secondary"></i> النشر:
                            <span>{{ $activity->is_published ?? $activity['is_published'] ? 'منشور' : 'غير منشور' }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-users ms-1"></i> متطلبات التطوع</h6>
                        @php $volunteer = $activity->volunteerRequirements ?? ($activity['volunteerRequirements'] ?? null); @endphp
                        @if ($volunteer)
                            <div class="border rounded-3 overflow-hidden">
                                <div class="d-flex justify-content-between px-3 py-2 border-bottom bg-light"><span>العدد المطلوب</span><strong>{{ $volunteer->required_volunteers ?? $volunteer['required_volunteers'] }}</strong></div>
                                <div class="d-flex justify-content-between px-3 py-2 border-bottom"><span>عدد المتطوعين الحالي</span><strong>{{ $volunteer->volunteers_count ?? $volunteer['volunteers_count'] }}</strong></div>
                                <div class="d-flex justify-content-between px-3 py-2 border-bottom bg-light"><span>نوع التطوع</span><strong>{{ $volunteer->volunteer_mode ?? $volunteer['volunteer_mode'] }}</strong></div>
                                <div class="d-flex justify-content-between px-3 py-2 border-bottom"><span>الحد الأدنى للساعات</span><strong>{{ $volunteer->min_hours ?? $volunteer['min_hours'] }}</strong></div>
                                <div class="d-flex justify-content-between px-3 py-2 border-bottom bg-light"><span>العمر الأدنى</span><strong>{{ $volunteer->min_age ?? $volunteer['min_age'] }}</strong></div>
                                <div class="d-flex justify-content-between px-3 py-2 border-bottom"><span>الجنس المطلوب</span><strong>{{ $volunteer->gender_requirement ?? $volunteer['gender_requirement'] }}</strong></div>
                                <div class="d-flex justify-content-between px-3 py-2 bg-light"><span>المهارات المطلوبة</span><strong>{{ $volunteer->skills_required ?? $volunteer['skills_required'] }}</strong></div>
                            </div>
                        @else
                            <div class="text-muted">لا توجد متطلبات تطوع لهذه الفعالية.</div>
                        @endif
                    </div>
                </div>

                <h6 class="fw-bold text-primary mt-4 mb-3"><i class="fas fa-hand-holding-heart ms-1"></i> إعدادات التبرع</h6>
                @php $donation = $activity->donationSettings ?? ($activity['donationSettings'] ?? null); @endphp
                @if ($donation)
                    <div class="row g-3">
                        <div class="col-md-4"><div class="p-3 rounded-3 bg-light border h-100"><div class="text-muted small">الهدف</div><div class="fw-bold">{{ number_format($donation->target_amount ?? $donation['target_amount'], 2) }} $</div></div></div>
                        <div class="col-md-4"><div class="p-3 rounded-3 bg-light border h-100"><div class="text-muted small">المحصّل</div><div class="fw-bold">{{ number_format($donation->collected_amount ?? $donation['collected_amount'], 2) }} $</div></div></div>
                        <div class="col-md-4"><div class="p-3 rounded-3 bg-light border h-100"><div class="text-muted small">حالة التبرع</div><div class="fw-bold">{{ ($donation->donation_status ?? $donation['donation_status']) == 'active' ? 'مفعّل' : 'غير مفعّل' }}</div></div></div>
                    </div>
                @else
                    <div class="text-muted">لا توجد إعدادات تبرع لهذه الفعالية.</div>
                @endif
            </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
@endpush
