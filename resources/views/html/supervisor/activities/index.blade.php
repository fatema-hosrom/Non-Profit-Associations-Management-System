@extends('templates.supervisor_app')

@section('title', 'قائمة الفعاليات - المشرف')

@push('styles')
    <link href="/assets/css/supervisor/activities.css" rel="stylesheet">
@endpush

@section('content')
    {{-- لا تستخدم class main-content هنا: الـ layout يضعه على <main> فقط، وإلا يتكرر margin-right ويحدث سكرول أفقي --}}
    <div class="container py-3 supervisor-activities-page">

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <h3 class="page-header mb-1 fw-bold text-primary"><i class="fas fa-calendar-check ms-2"></i> قائمة الفعاليات</h3>
                <p class="text-muted mb-0">متابعة جميع الفعاليات المنشأة تحت إشراف المدراء.</p>
            </div>
            <div class="badge bg-light text-dark border px-3 py-2">
                عدد الفعاليات: {{ ($activities ?? collect())->count() }}
            </div>
        </div>

        <div class="row g-4">

            @forelse ($activities ?? collect() as $activity)
                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if (!empty($activity->image ?? ($activity['image'] ?? null)))
                                <img class="rounded-3 border" style="width:84px;height:84px;object-fit:cover;"
                                    src="{{ asset('assets/images/activities/' . ($activity->image ?? $activity['image'])) }}"
                                    alt="صورة الفعالية">
                            @else
                                <div class="rounded-3 border d-flex align-items-center justify-content-center text-muted"
                                    style="width:84px;height:84px;">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif

                            <div>
                                <h5 class="fw-bold text-primary mb-1">{{ $activity->title ?? $activity['title'] }}</h5>

                                @php $type = $activity->activity_type ?? $activity['activity_type']; @endphp
                                <div class="text-muted mb-2"><i class="fas fa-layer-group"></i>
                                    {{ $type === 'donation' ? 'تبرع' : ($type === 'volunteer' ? 'تطوع' : ($type === 'both' ? 'شاملة' : 'عادية')) }}
                                </div>

                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="fas fa-user-tie ms-1"></i>
                                    {{ $activity->manager->full_name ?? ($activity['manager']['full_name'] ?? '-') }}</span>
                            </div>
                        </div>

                        <div class="pt-3 border-top">
                            <a href="{{ route('supervisor.activities.show', $activity->id ?? $activity['id']) }}"
                                class="btn btn-primary btn-sm rounded-pill px-3"><i class="fas fa-eye ms-1"></i> عرض التفاصيل</a>
                        </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-info-circle mb-3" style="font-size: 2rem;"></i>
                    <p class="fs-5">لا توجد فعاليات حالياً.</p>
                </div>
            @endforelse

        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
@endpush
