@extends('templates.supervisor_app')

@section('title', 'طلبات المتطوعين - المشرف')

@push('styles')
    <link rel="stylesheet" href="/assets/css/supervisor/volunteers.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container py-5">

            <div class="mb-4 text-end">
                <h2 class="fw-bold text-primary page-title">طلبات المتطوعين</h2>
            </div>
            <div class="row mb-4 text-center g-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">طلبات جديدة</h6>
                                <h3 class="fw-bold text-primary mb-0">{{ $counts['pending'] }}</h3>
                            </div>
                            <div class="text-primary fs-2"><i class="fas fa-hourglass-half"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">طلبات مقبولة</h6>
                                <h3 class="fw-bold text-success mb-0">{{ $counts['accepted'] }}</h3>
                            </div>
                            <div class="text-success fs-2"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">طلبات مرفوضة</h6>
                                <h3 class="fw-bold text-danger mb-0">{{ $counts['rejected'] }}</h3>
                            </div>
                            <div class="text-danger fs-2"><i class="fas fa-times-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-start gap-2 mb-5 flex-wrap">
                <a href="{{ route('supervisor.volunteers.index', ['status' => 'pending']) }}"
                    class="btn filter-btn {{ request('status') == 'pending' ? 'btn-primary active' : 'btn-outline-primary' }}">جديدة</a>
                <a href="{{ route('supervisor.volunteers.index', ['status' => 'accepted']) }}"
                    class="btn filter-btn {{ request('status') == 'accepted' ? 'btn-success active' : 'btn-outline-success' }}">مقبولة</a>
                <a href="{{ route('supervisor.volunteers.index', ['status' => 'rejected']) }}"
                    class="btn filter-btn {{ request('status') == 'rejected' ? 'btn-danger active' : 'btn-outline-danger' }}">مرفوضة</a>
            </div>

            <div class="row g-4">
                @forelse ($volunteers as $volunteer)
                    <div class="col-lg-4 col-md-6">
                        <div class="card volunteer-card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                            <span
                                class="status-badge {{ $volunteer->status == 'pending' ? 'bg-warning text-dark' : ($volunteer->status == 'accepted' ? 'bg-success text-white' : 'bg-danger text-white') }}">{{ ucfirst($volunteer->status) }}</span>
                            <h5 class="fw-bold text-primary mb-3">{{ $volunteer->name }}</h5>
                            <p class="text-muted mb-1"><i class="fas fa-envelope me-2"></i>{{ $volunteer->email }}</p>
                            <p class="text-muted mb-3"><i class="fas fa-phone me-2"></i>{{ $volunteer->phone }}</p>
                            <a href="{{ route('supervisor.volunteers.show', $volunteer->id) }}"
                                class="btn btn-primary w-100 rounded-pill">عرض التفاصيل</a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="fas fa-user-clock fa-3x mb-3"></i>
                        <p class="fw-semibold">لا توجد طلبات حالياً</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">{{ $volunteers->links() }}</div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
