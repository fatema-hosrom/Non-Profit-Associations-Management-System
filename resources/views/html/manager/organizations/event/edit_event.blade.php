@extends('templates.manager_app')

@section('title', 'تعديل فعالية')

@push('styles')
    <link rel="stylesheet" href="/assets/css/organizations/events.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('manager.organizations.events.index', ['orgId' => $event->organization_id ?? ($event['organization_id'] ?? null)]) }}"
                class="back-btn">
                <i class="fas fa-arrow-right"></i> العودة للفعاليات
            </a>

            <div class="form-card mt-3">
                <h4 class="form-title">
                    <i class="fas fa-edit text-primary me-2"></i>
                    تعديل الفعالية
                </h4>

                <form method="POST" enctype="multipart/form-data"
                    action="{{ route('manager.organizations.events.update', $event->id ?? ($event['id'] ?? null)) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-heading"></i>
                                <label class="form-label">عنوان الفعالية</label>
                            </div>
                            <input type="text" name="title" class="form-control"
                                value="{{ $event->title ?? ($event['title'] ?? '') }}" required>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-calendar-day"></i>
                                <label class="form-label">تاريخ البداية</label>
                            </div>
                            <input type="datetime-local" name="start_date" class="form-control"
                                value="{{ date('Y-m-d\TH:i', strtotime($event->start_date ?? ($event['start_date'] ?? now()))) }}"
                                required>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-calendar-check"></i>
                                <label class="form-label">تاريخ النهاية</label>
                            </div>
                            <input type="datetime-local" name="end_date" class="form-control"
                                value="{{ isset($event->end_date) ? date('Y-m-d\TH:i', strtotime($event->end_date)) : '' }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-map-marker-alt"></i>
                                <label class="form-label">الموقع</label>
                            </div>
                            <input type="text" name="location" class="form-control"
                                value="{{ $event->location ?? ($event['location'] ?? '') }}">
                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-image"></i>
                                <label class="form-label">صورة الفعالية</label>
                            </div>
                            <input type="file" name="image" accept="image/*" class="form-control">
                            @if (!empty($event->image ?? ($event['image'] ?? '')))
                                <div class="mt-2">
                                    <img src="{{ asset('assets/images/organization_events/' . ($event->image ?? $event['image'])) }}"
                                        class="event-img-preview" />
                                </div>
                            @endif
                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-tasks"></i>
                                <label class="form-label">الحالة</label>
                            </div>
                            <select name="status" class="form-control" required>
                                <option value="upcoming"
                                    {{ ($event->status ?? ($event['status'] ?? '')) == 'upcoming' ? 'selected' : '' }}>
                                    قادمة</option>
                                <option value="ongoing"
                                    {{ ($event->status ?? ($event['status'] ?? '')) == 'ongoing' ? 'selected' : '' }}>
                                    جارية</option>
                                <option value="completed"
                                    {{ ($event->status ?? ($event['status'] ?? '')) == 'completed' ? 'selected' : '' }}>
                                    مكتملة</option>
                            </select>

                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-link"></i>
                                <label class="form-label">رابط الفعالية</label>
                            </div>
                            <input type="url" name="external_url" class="form-control"
                                value="{{ $event->external_url ?? ($event['external_url'] ?? '') }}">
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-align-right"></i>
                                <label class="form-label">الوصف</label>
                            </div>
                            <textarea name="description" class="form-control" rows="4">{{ $event->description ?? ($event['description'] ?? '') }}</textarea>
                        </div>



                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-submit" type="submit">
                            <i class="fas fa-save"></i> حفظ التغييرات
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
