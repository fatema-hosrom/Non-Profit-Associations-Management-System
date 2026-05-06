@extends('public.template_layouts.app')

@section('title', 'الفعاليات المتاحة')

@section('content')
    <div class="available-activities-container">
        <!-- رسائل -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="page-header">
            <h1>الفعاليات المتاحة</h1>
            <p>استعرض الفعاليات القادمة وقدم طلب تطوع</p>
        </div>

        @if ($activities->count() === 0)
            <div class="empty-state">
                <div class="empty-icon">🎯</div>
                <h2>لا توجد فعاليات متاحة حالياً</h2>
                <p>سيتم إضافة فعاليات جديدة قريباً</p>
            </div>
        @else
            <div class="activities-grid">
                @foreach ($activities as $activity)
                    <div class="activity-card">
                        <div class="activity-image">
                            @if ($activity->image && file_exists(public_path('assets/images/activities/' . $activity->image)))
                                <img src="{{ asset('assets/images/activities/' . $activity->image) }}"
                                    alt="{{ $activity->title }}">
                            @else
                                <img src="{{ asset('assets/images/default-event.png') }}"
                                    alt="{{ $activity->title }}">
                            @endif
                        </div>

                        <div class="activity-content">
                            <h3>{{ $activity->title }}</h3>
                            <p class="activity-desc">{{ Str::limit($activity->description, 100) }}</p>

                            <div class="activity-details">
                                <div class="detail">
                                    <span class="icon">📍</span>
                                    <span>{{ $activity->location }}</span>
                                </div>

                                <div class="detail">
                                    <span class="icon">📅</span>
                                    <span>{{ $activity->start_date->format('Y-m-d') }}</span>
                                </div>

                                @if ($activity->volunteerRequirements)
                                    <div class="detail">
                                        <span class="icon">👥</span>
                                        <span>{{ $activity->volunteerRequirements->required_volunteers }} متطوع</span>
                                    </div>
                                @endif
                            </div>

                            <div class="activity-action">
                                <a href="{{ route('public.activities.sahem.show', $activity->id) }}"
                                    class="btn-view">عرض التفاصيل</a>
                                <form method="POST" action="{{ route('volunteer.request-volunteer', $activity->id) }}"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-volunteer">تطوع الآن</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/volunteer/available-activities.css') }}">
@endpush
