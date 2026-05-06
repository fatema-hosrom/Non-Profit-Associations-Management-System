@extends('public.template_layouts.app')

@section('title', 'المشاركات السابقة')

@section('content')
    <div class="past-activities-container">
        <div class="page-header">
            <h1>المشاركات السابقة</h1>
            <p>عرض الفعاليات التي شاركت فيها سابقاً</p>
        </div>

        @if ($activities->count() === 0)
            <div class="empty-state">
                <div class="empty-icon">🏆</div>
                <h2>لم تشارك في أي فعاليات حتى الآن</h2>
                <p>المشاركات المنتهية ستظهر هنا بعد انتهاء الفعالية</p>
            </div>
        @else
            <div class="activities-grid">
                @foreach ($activities as $participation)
                    <div class="activity-card">
                        <div class="activity-image">
                            @if ($participation->activity->image && file_exists(public_path('assets/images/activities/' . $participation->activity->image)))
                                <img src="{{ asset('assets/images/activities/' . $participation->activity->image) }}"
                                    alt="{{ $participation->activity->title }}">
                            @else
                                <img src="{{ asset('assets/images/default-event.png') }}"
                                    alt="{{ $participation->activity->title }}">
                            @endif
                            <div class="completion-badge">✓ مكتمل</div>
                        </div>

                        <div class="activity-content">
                            <h3>{{ $participation->activity->title }}</h3>
                            <p class="activity-desc">{{ Str::limit($participation->activity->description, 100) }}</p>

                            <div class="activity-meta">
                                <div class="meta-item">
                                    <span class="label">تاريخ البدء:</span>
                                    <span class="value">{{ $participation->activity->start_date->format('Y-m-d') }}</span>
                                </div>

                                <div class="meta-item">
                                    <span class="label">تاريخ الانتهاء:</span>
                                    <span class="value">{{ $participation->activity->end_date->format('Y-m-d') }}</span>
                                </div>

                                <div class="meta-item">
                                    <span class="label">انضممت في:</span>
                                    <span class="value">{{ $participation->joined_at->format('Y-m-d') }}</span>
                                </div>

                                <div class="meta-item">
                                    <span class="label">المكان:</span>
                                    <span class="value">{{ $participation->activity->location }}</span>
                                </div>
                            </div>

                            <div class="activity-action">
                                <a href="{{ route('public.activities.sahem.show', $participation->activity->id) }}"
                                    class="btn-view-details">عرض التفاصيل</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/volunteer/past-activities.css') }}">
@endpush
