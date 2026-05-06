@extends('templates.manager_app')

@section('title', 'قائمة الجمعيات')

@push('styles')
    <link rel="stylesheet" href="/assets/css/organizations/organizations.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h6 class="page-title mb-0">قائمة الجمعيات</h6>
                    <button class="btn add-btn" onclick="window.location.href='{{ route('manager.organizations.add') }}'">
                        <i class="fas fa-plus"></i>
                        إضافة جمعية
                    </button>
                </div>
            </div>

            @if (session('success'))
                <div id="successMessage" class="text-center mb-3">
                    <span class="text-success fs-5">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            <div class="row">
                @forelse ($organizations ?? collect() as $org)
                    <div class="col-12 mb-4">
                        <div class="card p-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="org-info">
                                    <div class="org-name mb-2">
                                        <i class="fas fa-building text-primary me-2"></i>
                                        <strong>{{ $org->name ?? $org['name'] }}</strong>
                                    </div>
                                    <div class="org-type text-muted">
                                        <i class="fas fa-tag me-2"></i>
                                        {{ $org->type ?? $org['type'] }}
                                    </div>
                                </div>
                                <div class="btn-group mt-3 mt-md-0" role="group">
                                    <a href="{{ route('manager.organizations.show', $org->id ?? $org['id']) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> عرض
                                    </a>
                                    <a href="{{ route('manager.organizations.edit', $org->id ?? $org['id']) }}"
                                        class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $org['id'] }}">
                                        <i class="fas fa-trash-alt"></i> حذف
                                    </button>
                                    <a href="{{ route('manager.organizations.events.index', ['orgId' => $org->id ?? $org['id']]) }}"
                                        class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-calendar-alt"></i> الفعاليات
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- نافذة التأكيد -->
                    <div class="modal fade" id="deleteModal{{ $org['id'] }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                    <h5 class="modal-title mx-auto">تأكيد الحذف</h5>
                                </div>
                                <div class="modal-body text-center">
                                    هل أنت متأكد من حذف الجمعية "{{ $org->name ?? $org['name'] }}"؟
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                    <form method="POST"
                                        action="{{ route('manager.organizations.destroy', $org->id ?? $org['id']) }}">
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
                        لا توجد جمعيات حالياً.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
