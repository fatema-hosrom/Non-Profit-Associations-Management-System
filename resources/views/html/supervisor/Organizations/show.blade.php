@extends('templates.supervisor_app')

@section('title', 'تفاصيل الجمعية - المشرف')

@push('styles')
    <link rel="stylesheet" href="/assets/css/supervisor/organization.css">
@endpush

@section('content')
    <div class="main-content py-3">
        <div class="container">
            <a href="{{ route('supervisor.organizations.index') }}" class="btn btn-light border rounded-pill mb-3 px-3">
                <i class="fas fa-arrow-right ms-1"></i> العودة إلى الجمعيات
            </a>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="row align-items-center g-4">
                        <div class="col-md-3 text-center">
                        @if (!empty($org->logo ?? ($org['logo'] ?? null)))
                            <img src="{{ asset('assets/images/organizations/' . ($org->logo ?? $org['logo'])) }}"
                                class="org-img-preview rounded-4 border" alt="شعار الجمعية">
                        @else
                            <span class="text-muted"><i class="fas fa-image fa-4x"></i></span>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h2 class="text-primary fw-bold mb-3">{{ $org->name ?? $org['name'] }}</h2>
                        <div class="badge bg-primary-subtle text-primary border border-primary-subtle mb-3">
                            <i class="fas fa-user-tie ms-1"></i>
                            {{ $org->manager->full_name ?? ($org['manager']['full_name'] ?? '-') }}
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <i class="fas fa-tag ms-1 text-secondary"></i>
                                    <strong>النوع:</strong>
                                    @php
                                        $type = $org->type ?? $org['type'];
                                        $type_map = ['local' => 'محلية', 'external' => 'خارجية'];
                                    @endphp
                                    <span>{{ $type_map[$type] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <i class="fas fa-envelope ms-1 text-secondary"></i>
                                    <strong>البريد الإلكتروني:</strong>
                                    <span>{{ $org->contact_email ?? ($org['contact_email'] ?? '-') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <i class="fas fa-phone ms-1 text-secondary"></i>
                                    <strong>الهاتف:</strong>
                                    <span>{{ $org->contact_phone ?? ($org['contact_phone'] ?? '-') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <i class="fas fa-external-link-alt ms-1 text-secondary"></i>
                                    <strong>الموقع الإلكتروني:</strong>
                                    @php $url = $org->website_url ?? ($org['website_url'] ?? null); @endphp
                            @if ($url)
                                        <a href="{{ $url }}" target="_blank" class="ms-1 text-decoration-none">
                                            <i class="fas fa-link ms-1"></i> فتح الرابط
                                        </a>
                            @else
                                        <span>-</span>
                            @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-0">

                <div class="p-4 p-lg-5">
                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle ms-1"></i> وصف الجمعية</h5>
                    <div class="p-3 rounded-3 bg-light border mb-4">
                        {{ $org->description ?? ($org['description'] ?? '-') }}
                    </div>

                    <h5 class="fw-bold text-primary mb-3"><i class="fas fa-calendar-alt ms-1"></i> فعاليات الجمعية</h5>
                    @if (!empty($org->events) && count($org->events) > 0)
                        <div class="row g-3">
                            @foreach ($org->events as $ev)
                                <div class="col-12 col-lg-6">
                                    <div class="border rounded-3 p-3 h-100 bg-white">
                                        <div class="fw-bold mb-2"><i class="fas fa-bolt ms-1 text-warning"></i> {{ $ev->title ?? $ev['title'] }}</div>
                                        <div class="small text-muted mb-1"><i class="fas fa-calendar-day ms-1"></i> البداية:
                                            {{ $ev->start_date ?? $ev['start_date'] }}</div>
                                        <div class="small text-muted mb-3"><i class="fas fa-calendar-check ms-1"></i> النهاية:
                                            {{ $ev->end_date ?? $ev['end_date'] }}</div>
                                        <a href="{{ route('supervisor.organizations.events.show', $ev->id ?? $ev['id']) }}"
                                            class="btn btn-outline-info btn-sm rounded-pill px-3">
                                            <i class="fas fa-eye ms-1"></i> عرض الفعالية
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                    @else
                        <div class="text-muted">لا توجد فعاليات لهذه الجمعية.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
@endpush
