@extends('templates.supervisor_app')

@section('title', 'قائمة الجمعيات - المشرف')

@push('styles')
    <link rel="stylesheet" href="/assets/css/supervisor/organization.css">
@endpush

@section('content')
    <div class="main-content py-3">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                <div>
                    <h3 class="mb-1 fw-bold text-primary"><i class="fas fa-building ms-2"></i> قائمة الجمعيات</h3>
                    <p class="text-muted mb-0">استعراض الجمعيات المسجلة ومتابعة فعالياتها.</p>
                </div>
                <div class="badge bg-light text-dark border px-3 py-2">
                    عدد الجمعيات: {{ ($organizations ?? collect())->count() }}
                </div>
            </div>

            <div class="row g-4">
                @forelse ($organizations ?? collect() as $org)
                    <div class="col-12 col-lg-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if (!empty($org->logo ?? ($org['logo'] ?? null)))
                                        <img src="{{ asset('assets/images/organizations/' . ($org->logo ?? $org['logo'])) }}"
                                            class="rounded-3 border" style="width:72px;height:72px;object-fit:cover;"
                                            alt="شعار الجمعية">
                                    @else
                                        <div class="rounded-3 border d-flex align-items-center justify-content-center text-muted"
                                            style="width:72px;height:72px;">
                                            <i class="fas fa-image fa-lg"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-1 fw-bold">{{ $org->name ?? $org['name'] }}</h5>
                                        <div class="text-muted small mb-1">
                                            النوع: {{ $org->type ?? $org['type'] ?? '-' }}
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            <i class="fas fa-user-tie ms-1"></i>
                                            {{ $org->manager->full_name ?? ($org['manager']['full_name'] ?? '-') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 pt-2 border-top">
                                    <a href="{{ route('supervisor.organizations.show', $org->id ?? $org['id']) }}"
                                        class="btn btn-primary btn-sm rounded-pill px-3">
                                        <i class="fas fa-eye ms-1"></i> عرض التفاصيل
                                    </a>
                                    <a href="{{ route('supervisor.organizations.events.index', $org->id ?? $org['id']) }}"
                                        class="btn btn-outline-info btn-sm rounded-pill px-3">
                                        <i class="fas fa-calendar-alt ms-1"></i> فعاليات الجمعية
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body py-5 text-center text-muted">
                                <i class="fas fa-info-circle mb-2" style="font-size:2rem;"></i>
                                <p class="mb-0">لا توجد جمعيات حالياً.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
@endpush
