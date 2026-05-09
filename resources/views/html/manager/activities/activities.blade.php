@extends('templates.manager_app')

@section('title', 'قائمة فعاليات المؤسسة')

@push('styles')
    <link rel="stylesheet" href="/assets/css/activities/activities.css">
    <link rel="stylesheet" href="{{ asset('assets/css/manager/activities.css') }}">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h6 class="page-title mb-0">قائمة فعاليات المؤسسة</h6>
                    <button class="btn add-btn" onclick="window.location.href='{{ route('manager.activities.add') }}'">
                        <i class="fas fa-plus"></i>
                        إضافة فعالية
                    </button>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show flash-message" role="alert" data-seconds="7">
                    <div>
                        <i class="fas fa-check-circle ms-1"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show flash-message" role="alert" data-seconds="7">
                    <div>
                        <i class="fas fa-exclamation-triangle ms-1"></i>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Search Field -->
            <div class="row mb-4">
                <div class="col-12">
                    <form method="GET" action="{{ route('manager.activities.index') }}" class="d-flex">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2"
                            placeholder="البحث في الفعاليات...">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </form>
                </div>
            </div>

            @forelse ($activities ?? collect() as $activity)
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="activity-card">
                            <div class="activity-header">
                                <h4 class="activity-title">
                                    <span class="activity-img">
                                        @if (!empty($activity->image ?? ($activity['image'] ?? null)))
                                            <img src="{{ asset('assets/images/activities/' . ($activity->image ?? $activity['image'])) }}"
                                                alt="{{ $activity->title ?? $activity['title'] }}"
                                                style="width:100%;height:100%;border-radius:8px;object-fit:cover;">
                                        @else
                                            <span class="text-muted" style="font-size:1.5rem;">لا يوجد</span>
                                        @endif
                                    </span>
                                    {{ $activity->title ?? $activity['title'] }}
                                    @php
                                        $hasResults = \App\Models\ActivityResult::where(
                                            'activity_id',
                                            $activity->id ?? $activity['id'],
                                        )->exists();
                                    @endphp
                                    @if ($hasResults)
                                        <span class="results-badge">
                                            <i class="fas fa-chart-line"></i> لها نتائج
                                        </span>
                                    @endif
                                    @php
                                        $status = $activity->status ?? ($activity['status'] ?? 'draft');
                                        $statusText = match ($status) {
                                            'draft' => 'مسودة',
                                            'active' => 'نشطة',
                                            'closed' => 'مغلقة',
                                            default => 'مسودة',
                                        };
                                        $statusClass = match ($status) {
                                            'draft' => 'status-draft',
                                            'active' => 'status-active',
                                            'closed' => 'status-closed',
                                            default => 'status-draft',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}" style="margin-right: 10px;">
                                        <i class="fas fa-circle"></i> {{ $statusText }}
                                    </span>
                                </h4>
                                <span class="activity-type">
                                    <i class="fas fa-tag"></i>
                                    @php $type = $activity->activity_type ?? $activity['activity_type']; @endphp
                                    {{ $type === 'donation' ? 'تبرع' : ($type === 'volunteer' ? 'تطوع' : ($type === 'both' ? 'شاملة' : 'عادية')) }}
                                </span>
                            </div>
                            <div class="activity-info">
                                <i class="fas fa-calendar-day"></i>
                                <span>تاريخ البداية: {{ $activity->start_date ?? $activity['start_date'] }}</span>
                            </div>
                            <div class="activity-info">
                                <i class="fas fa-calendar-check"></i>
                                <span>تاريخ النهاية: {{ $activity->end_date ?? $activity['end_date'] }}</span>
                            </div>


                            <div class="action-buttons">
                                <button class="btn action-btn view-btn"
                                    onclick="window.location.href='{{ route('manager.activities.view', $activity->id ?? $activity['id']) }}'">
                                    <i class="fas fa-eye"></i> عرض التفاصيل
                                </button>
                                <button class="btn action-btn"
                                    style="background-color: #0d6efd; color: white; border: none;"
                                    onclick="window.location.href='{{ route('manager.activities.results.view', $activity->id ?? $activity['id']) }}'">
                                    <i class="fas fa-chart-line"></i> إدارة النتائج
                                </button>
                                <button class="btn action-btn edit-btn"
                                    onclick="window.location.href='{{ route('manager.activities.edit', $activity->id ?? $activity['id']) }}'">
                                    <i class="fas fa-edit"></i> تعديل
                                </button>
                                <button class="btn action-btn delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $activity['id'] }}">
                                    <i class="fas fa-trash"></i> حذف
                                </button>

                                {{-- Activity Status Dropdown --}}
                                <div class="dropdown" style="display:inline-block;">
                                    <button class="btn action-btn status-btn dropdown-toggle"
                                        style="background-color: #f5cf27; color: white; border: none;" type="button"
                                        id="statusDropdown{{ $activity->id ?? $activity['id'] }}" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fas fa-exchange-alt"></i> تغيير الحالة
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="statusDropdown{{ $activity->id ?? $activity['id'] }}">
                                        <li>
                                            <form method="POST"
                                                action="{{ route('manager.activities.changeStatus', $activity->id ?? $activity['id']) }}"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="draft">
                                                <button type="submit"
                                                    class="dropdown-item {{ $status === 'draft' ? 'active' : '' }}">
                                                    <i class="fas fa-circle text-secondary"></i> مسودة
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST"
                                                action="{{ route('manager.activities.changeStatus', $activity->id ?? $activity['id']) }}"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit"
                                                    class="dropdown-item {{ $status === 'active' ? 'active' : '' }}">
                                                    <i class="fas fa-circle text-success"></i> نشطة
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST"
                                                action="{{ route('manager.activities.changeStatus', $activity->id ?? $activity['id']) }}"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="closed">
                                                <button type="submit"
                                                    class="dropdown-item {{ $status === 'closed' ? 'active' : '' }}">
                                                    <i class="fas fa-circle text-danger"></i> مغلقة
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                                <form method="POST"
                                    action="{{ route('manager.activities.togglePublish', $activity->id ?? $activity['id']) }}"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit"
                                        class="btn {{ $activity->is_published ?? false ? 'btn-danger' : 'btn-success' }}"
                                        style="margin-right:10px;">
                                        <i class="fas fa-bullhorn"></i>
                                        {{ $activity->is_published ? 'إيقاف الإعلان' : 'إعلان عن الفعالية' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Activity Delete Modal --}}
                <div class="modal fade" id="deleteModal{{ $activity['id'] }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    style="margin:0;"></button>
                                <h5 class="modal-title" style="margin: auto;">تأكيد الحذف</h5>
                            </div>
                            <div class="modal-body">هل أنت متأكد من حذف الفعالية "{{ $activity['title'] }}"؟</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <form method="POST"
                                    action="{{ route('manager.activities.destroy', $activity->id ?? $activity['id']) }}"
                                    style="display:inline;margin:0;padding:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">حذف</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-12 text-center text-muted">
                    <i class="fas fa-info-circle mb-2" style="font-size: 2rem;"></i>
                    <p>لا توجد فعاليات حالياً.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/js/bootstrap.js"></script>
    <script>
        document.querySelectorAll('.flash-message').forEach(function(message) {
            var seconds = Number(message.dataset.seconds || 7);

            var timer = setInterval(function() {
                seconds -= 1;

                if (seconds <= 0) {
                    clearInterval(timer);
                    message.classList.remove('show');
                    setTimeout(function() {
                        message.remove();
                    }, 300);
                }
            }, 1000);
        });
    </script>
@endpush
