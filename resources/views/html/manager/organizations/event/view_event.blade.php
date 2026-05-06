@extends('templates.manager_app')

@section('title', 'عرض الفعالية')

@push('styles')
    <link rel="stylesheet" href="/assets/css/organizations/events.css">
@endpush

@section('content')
    <div class="main-content">

        <a href="{{ route('manager.organizations.events.index', ['orgId' => $event->organization_id ?? ($event['organization_id'] ?? null)]) }}"
            class="btn-back mb-3">
            <i class="fas fa-arrow-right"></i> العودة للفعاليات
        </a>

        <div class="event-card mt-3">

            <h4 class="event-title mb-4">
                <i class="fas fa-calendar-alt"></i>
                {{ $event->title ?? ($event['title'] ?? '-') }}
            </h4>

            <div class="d-flex flex-wrap gap-4">

                <!-- معلومات -->
                <div class="flex-fill" style="min-width: 280px;">

                    <div class="info-block">
                        <i class="fas fa-align-right"></i>
                        <strong>الوصف:</strong>
                        <span>{{ $event->description ?? '-' }}</span>
                    </div>

                    <div class="info-block">
                        <i class="fas fa-calendar-day"></i>
                        <strong>تاريخ البداية:</strong>
                        <span>{{ $event->start_date ?? '-' }}</span>
                    </div>

                    <div class="info-block">
                        <i class="fas fa-calendar-check"></i>
                        <strong>تاريخ النهاية:</strong>
                        <span>{{ $event->end_date ?? '-' }}</span>
                    </div>

                    <div class="info-block">
                        <i class="fas fa-map-marker-alt"></i>
                        <strong>الموقع:</strong>
                        <span>{{ $event->location ?? '-' }}</span>
                    </div>

                    <div class="info-block">
                        <i class="fas fa-toggle-on"></i>
                        <strong>الحالة:</strong>

                        @php
                            $type = $event->status ?? ($event['status'] ?? '-');
                            $type_map = [
                                'upcoming' => 'قادمة',
                                'ongoing' => 'جارية',
                                'completed' => 'مكتملة',
                                'cancelled' => 'ملغاة',
                            ];
                        @endphp

                        <span>{{ $type_map[$type] ?? '-' }}</span>
                    </div>

                    <div class="info-block">
                        <i class="fas fa-external-link-alt"></i>
                        <strong>رابط الفعالية:</strong>
                        @php $url = $event->external_url ?? null; @endphp
                        @if ($url)
                            <a href="{{ $url }}" target="_blank" class="btn-event-link">
                                <i class="fas fa-link"></i> فتح الرابط
                            </a>
                        @else
                            <span>-</span>
                        @endif
                    </div>
                </div>

                <!-- صورة -->
                @if (!empty($event->image))
                    <div class="text-center flex-fill">
                        <img src="{{ asset('assets/images/organization_events/' . $event->image) }}" alt="صورة الفعالية"
                            class="event-img shadow-sm">
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
