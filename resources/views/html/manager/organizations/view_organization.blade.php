@extends('templates.manager_app')

@section('title', 'عرض الجمعية')

@push('styles')
    <link rel="stylesheet" href="/assets/css/organizations/organizations.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('manager.organizations.index') }}" class="back-btn">
                <i class="fas fa-arrow-right"></i> العودة إلى قائمة الجمعيات
            </a>

            <div class="form-card mt-3">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-4">
                    <div>
                        <h4 class="form-title">
                            <i class="fas fa-building text-primary me-2"></i>
                            {{ $org->name ?? ($org['name'] ?? '') }}
                        </h4>

                        <div class="info-block">
                            <i class="fas fa-align-right text-info"></i>
                            <strong>الوصف:</strong>
                            <span>{{ $org->description ?? ($org['description'] ?? '-') }}</span>
                        </div>

                        <div class="info-block">
                            <i class="fas fa-tag text-success"></i>
                            <strong>النوع:</strong>
                            <span>{{ $org->type ?? ($org['type'] ?? '-') }}</span>
                        </div>

                        @if (!empty($org->website_url ?? ($org['website_url'] ?? '')))
                            <div class="info-block">
                                <i class="fas fa-globe text-warning"></i>
                                <strong>الموقع:</strong>
                                <a href="{{ $org->website_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt"></i> زيارة الموقع
                                </a>
                            </div>
                        @endif

                        <div class="info-block">
                            <i class="fas fa-envelope text-danger"></i>
                            <strong>البريد:</strong>
                            <span>{{ $org->contact_email ?? ($org['contact_email'] ?? '-') }}</span>
                        </div>

                        <div class="info-block">
                            <i class="fas fa-phone text-primary"></i>
                            <strong>الهاتف:</strong>
                            <span>{{ $org->contact_phone ?? ($org['contact_phone'] ?? '-') }}</span>
                        </div>
                    </div>

                    <div>
                        @if (!empty($org->logo ?? ($org['logo'] ?? '')))
                            <img src="{{ asset('assets/images/organizations/' . ($org->logo ?? $org['logo'])) }}"
                                class="org-img-preview" />
                        @endif
                    </div>
                </div>

                <hr />
                <div>
                    <h5 class="mb-3">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        الفعاليات الخاصة بهذه الجمعية
                    </h5>
                    <button class="btn btn-sm btn-outline-success mb-3"
                        onclick="window.location.href='{{ route('manager.organizations.events.create', ['orgId' => $org->id ?? ($org['id'] ?? null)]) }}'">
                        <i class="fas fa-plus"></i> إضافة فعالية
                    </button>

                    @if (!empty($org->events) && count($org->events) > 0)
                        <ul class="event-list">
                            @foreach ($org->events as $ev)
                                <li>
                                    <i class="fas fa-chevron-left text-muted me-2"></i>
                                    {{ $ev->title ?? $ev['title'] }}
                                    <a href="{{ route('manager.organizations.events.show', $ev->id ?? $ev['id']) }}"
                                        class="btn btn-sm btn-outline-info ms-2">
                                        <i class="fas fa-eye"></i> عرض
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted">لا توجد فعاليات حالياً.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
