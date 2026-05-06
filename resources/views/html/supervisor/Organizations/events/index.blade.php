@extends('templates.supervisor_app')

@section('title', 'فعاليات الجمعية')

@push('styles')
    <link rel="stylesheet" href="/assets/css/organizations/events.css">
@endpush

@section('content')
    <div class="main-content py-3">
        <div class="container">
            <a href="{{ route('supervisor.organizations.index') }}" class="btn btn-light border rounded-pill mb-3 px-3">
                <i class="fas fa-arrow-right ms-1"></i> العودة إلى الجمعيات
            </a>

            <div class="card border-0 shadow-sm rounded-4 mt-2">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                    <h5 class="page-title mb-0 fw-bold"><i class="fas fa-calendar-alt text-primary me-2"></i> فعاليات:
                        {{ $org->name ?? ($org['name'] ?? '') }}</h5>
                        <span class="badge bg-light text-dark border">عدد الفعاليات: {{ count($events ?? []) }}</span>
                </div>
                <div class="table-responsive">
                    @if (!empty($events) && count($events) > 0)
                        <table class="table align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> #</th>
                                    <th><i class="fas fa-heading"></i> العنوان</th>
                                    <th><i class="fas fa-calendar-day"></i> البداية</th>
                                    <th><i class="fas fa-calendar-check"></i> النهاية</th>
                                    <th><i class="fas fa-cogs"></i> الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $index => $ev)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="event-title-cell fw-semibold">{{ $ev->title ?? $ev['title'] }}</td>
                                        <td>{{ $ev->start_date ?? $ev['start_date'] }}</td>
                                        <td>{{ $ev->end_date ?? ($ev['end_date'] ?? '-') }}</td>
                                        <td>
                                                <a href="{{ route('supervisor.organizations.events.show', $ev->id ?? $ev['id']) }}"
                                                    class="btn btn-outline-info btn-sm rounded-pill px-3"><i class="fas fa-eye ms-1"></i>
                                                    عرض</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-muted text-center py-4">لا توجد فعاليات لهذه الجمعية.</div>
                    @endif
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
