@extends('templates.manager_app')

@section('title', 'فعاليات الجمعية')

@push('styles')
    <link rel="stylesheet" href="/assets/css/organizations/events.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('manager.organizations.index') }}" class="back-btn">
                <i class="fas fa-arrow-right"></i> العودة إلى الجمعيات
            </a>

            <div class="card mt-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5 class="page-title">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        فعاليات: {{ $org->name ?? ($org['name'] ?? '') }}
                    </h5>
                    <button class="add-btn"
                        onclick="window.location.href='{{ route('manager.organizations.events.create', ['orgId' => $org->id ?? ($org['id'] ?? null)]) }}'">
                        <i class="fas fa-plus"></i> إضافة فعالية
                    </button>
                </div>


                <?php if (session('success')): ?>
                <div id="successMessage" style="text-align: center; margin-bottom: 20px;">
                    <span style="color: #28a745; font-size: 30px;">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo e(session('success')); ?>
                    </span>
                </div>
                <script>
                    setTimeout(function() {
                        var successMessage = document.getElementById('successMessage');
                        if (successMessage) {
                            successMessage.style.display = 'none';
                        }
                    }, 7000);
                </script>
                <?php endif; ?>

                <div class="mt-4 table-responsive">
                    @if (!empty($events) && count($events) > 0)
                        <table class="table table-bordered align-middle text-center">
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
                                        <td class="event-title-cell">{{ $ev->title ?? $ev['title'] }}</td>
                                        <td>{{ $ev->start_date ?? $ev['start_date'] }}</td>
                                        <td>{{ $ev->end_date ?? ($ev['end_date'] ?? '-') }}</td>
                                        <td>
                                            <div class="d-flex flex-column gap-2">
                                                <a href="{{ route('manager.organizations.events.show', $ev->id ?? $ev['id']) }}"
                                                    class="btn btn-outline-info btn-sm w-100">
                                                    <i class="fas fa-eye"></i> عرض
                                                </a>
                                                <a href="{{ route('manager.organizations.events.edit', $ev->id ?? $ev['id']) }}"
                                                    class="btn btn-outline-primary btn-sm w-100">
                                                    <i class="fas fa-edit"></i> تعديل
                                                </a>
                                                <button class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $ev->id ?? $ev['id'] }}">
                                                    <i class="fas fa-trash-alt"></i> حذف
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-muted">لا توجد فعاليات لهذه الجمعية.</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- نوافذ التأكيد خارج الجدول -->
    @foreach ($events as $ev)
        <div class="modal fade" id="deleteModal{{ $ev->id ?? $ev['id'] }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h5 class="modal-title mx-auto">تأكيد الحذف</h5>
                    </div>
                    <div class="modal-body text-center">
                        هل أنت متأكد من حذف الفعالية ؟
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <form method="POST"
                            action="{{ route('manager.organizations.events.destroy', $ev->id ?? $ev['id']) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">حذف</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
