@extends('templates.manager_app')

@section('title', 'إضافة فعالية للجمعية')

@push('styles')
    <link rel="stylesheet" href="/assets/css/organizations/events.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('manager.organizations.events.index', ['orgId' => $org->id ?? ($org['id'] ?? null)]) }}"
                class="back-btn">
                <i class="fas fa-arrow-right"></i> العودة للفعاليات
            </a>

            <div class="form-card mt-3">
                <h4 class="form-title">
                    <i class="fas fa-plus-circle text-success me-2"></i>
                    إضافة فعالية جديدة لـ {{ $org->name ?? ($org['name'] ?? '') }}
                </h4>

                <form method="POST" enctype="multipart/form-data"
                    action="{{ route('manager.organizations.events.store', ['orgId' => $org->id ?? ($org['id'] ?? null)]) }}">
                    @csrf

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-heading"></i>
                                <label class="form-label">عنوان الفعالية</label>
                            </div>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-calendar-day"></i>
                                <label class="form-label">تاريخ البداية</label>
                            </div>
                            <input type="datetime-local" name="start_date" class="form-control" required>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-calendar-check"></i>
                                <label class="form-label">تاريخ النهاية</label>
                            </div>
                            <input type="datetime-local" name="end_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-map-marker-alt"></i>
                                <label class="form-label">الموقع</label>
                            </div>
                            <input type="text" name="location" class="form-control">
                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-image"></i>
                                <label class="form-label">صورة الفعالية</label>
                            </div>
                            <input type="file" name="image" accept="image/*" class="form-control">
                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-tasks"></i>
                                <label class="form-label"> الحالة</label>
                            </div>
                            <select name="status" class="form-control" required>
                                <option value="upcoming">قادمة</option>
                                <option value="ongoing">جارية</option>
                                <option value="completed">مكتملة</option>
                            </select>
                        </div>

                        <div class= "input-container">
                            <div class="input-group-label">
                                <i class="fas fa-link"></i>
                                <label class="form-label">رابط الفعالية</label>
                            </div>
                            <input type="url" name="external_url" class="form-control">
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-align-right"></i>
                                <label class="form-label">الوصف</label>
                            </div>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                        </div>


                    </div>
            </div>


            <div class="text-center mt-4">
                <button class="btn btn-submit" type="submit">
                    <i class="fas fa-save"></i> حفظ
                </button>
            </div>
            </form>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
