@extends('templates.supervisor_app')

@section('title', 'تفاصيل فعالية الجمعية - المشرف')

@push('styles')
    <link rel="stylesheet" href="/assets/css/supervisor/event.css">
@endpush

@section('content')
    <div class="main-content py-3">
        <div class="container">
            <a href="{{ route('supervisor.organizations.show', $event->organization_id ?? $event['organization_id']) }}"
                class="btn btn-light border rounded-pill mb-3 px-3">
                <i class="fas fa-arrow-right ms-1"></i> العودة للجمعية
            </a>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <h4 class="fw-bold text-primary mb-4"><i class="fas fa-calendar-alt ms-1"></i> تفاصيل فعالية الجمعية</h4>
                <div class="d-flex align-items-center gap-4 mb-4 flex-wrap">
                    @if (!empty($event->image ?? ($event['image'] ?? null)))
                        <img src="{{ asset('assets/images/organization_events/' . ($event->image ?? $event['image'])) }}"
                            class="event-img-preview rounded-4 border" alt="صورة الفعالية">
                    @else
                        <span class="text-muted"><i class="fas fa-image fa-3x"></i></span>
                    @endif
                    <div>
                        <h4 class="fw-bold mb-1">{{ $event->title ?? $event['title'] }}</h4>
                        <div class="text-muted mb-2">{{ $event->type ?? $event['type'] }}</div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                            <i class="fas fa-user-tie ms-1"></i>
                            {{ $event->organization->manager->full_name ?? ($event['organization']['manager']['full_name'] ?? '-') }}
                        </span>
                    </div>
                </div>
                <div class="mb-3 p-3 rounded-3 bg-light border"><strong class="d-block mb-1">الوصف:</strong>
                    <p class="mb-0">{{ $event->description ?? $event['description'] }}</p>
                </div>
                <div class="row mt-4 g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-light border mb-2"><i class="fas fa-map-marker-alt ms-1 text-secondary"></i> <strong>الموقع:</strong>
                            <span>{{ $event->location ?? $event['location'] ?? '-' }}</span>
                        </div>
                        <div class="p-3 rounded-3 bg-light border mb-2"><i class="fas fa-calendar-day ms-1 text-secondary"></i> <strong>البداية:</strong>
                            <span>{{ $event->start_date ?? $event['start_date'] }}</span>
                        </div>
                        <div class="p-3 rounded-3 bg-light border"><i class="fas fa-calendar-check ms-1 text-secondary"></i> <strong>النهاية:</strong>
                            <span>{{ $event->end_date ?? ($event['end_date'] ?? '-') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-light border mb-2">
                            <i class="fas fa-external-link-alt ms-1 text-secondary"></i>
                            <strong>رابط الفعالية:</strong>
                            @php $url = $event->external_url ?? ($event['external_url'] ?? null); @endphp
                            @if ($url)
                                <a href="{{ $url }}" target="_blank" class="text-decoration-none ms-1">
                                    <i class="fas fa-link ms-1"></i> فتح الرابط
                                </a>
                            @else
                                <span>-</span>
                            @endif
                        </div>

                        <div class="p-3 rounded-3 bg-light border"><i class="fas fa-toggle-on ms-1 text-secondary"></i><strong> الحالة:</strong>
                            <span>@php
                                $type = $event->status ?? $event['status'];
                                $type_mape = [
                                    'upcoming' => 'قادمة',
                                    'ongoing' => 'جارية',
                                    'completed' => 'مكتملة',
                                    'cancelled' => 'ملغاة',
                                ];
                                echo $type_mape[$type] ?? '-';
                            @endphp</span>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
@endpush
