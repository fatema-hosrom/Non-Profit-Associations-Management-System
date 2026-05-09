@extends('public.template_layouts.app')

@section('title', 'لوحة تحكم المتطوع')

@section('content')
    <div class="volunteer-dashboard">
        <!-- Success Messages
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif -->

        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1>مرحباً {{ $volunteer->name }}</h1>
                <p>لوحة تحكم المتطوع</p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="statistics-grid">
            <div class="stat-card">
                <div class="stat-icon pending">▼</div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['pending_requests'] }}</span>
                    <span class="stat-label">طلبات قيد الانتظار</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon approved">✓</div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['approved_activities'] }}</span>
                    <span class="stat-label">فعاليات مقبولة</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon completed">★</div>
                <div class="stat-info">
                    <span class="stat-value">{{ $stats['completed_activities'] }}</span>
                    <span class="stat-label">فعاليات منجزة</span>
                </div>
            </div>
        </div>

        <!-- Main Options -->
        <div class="dashboard-menu">
            <a href="{{ route('volunteer.profile') }}" class="menu-card">
                <div class="menu-icon">👤</div>
                <div class="menu-title">الملف الشخصي</div>
                <div class="menu-desc">عرض وتعديل بياناتك الشخصية</div>
            </a>

            <a href="{{ route('volunteer.available-activities') }}" class="menu-card">
                <div class="menu-icon">🎯</div>
                <div class="menu-title">فعاليات متاحة</div>
                <div class="menu-desc">استعرض الفعاليات وقدم طلب تطوع</div>
            </a>

            <a href="{{ route('volunteer.my-requests') }}" class="menu-card">
                <div class="menu-icon">📋</div>
                <div class="menu-title">طلباتي</div>
                <div class="menu-desc">عرض جميع طلبات التطوع الخاصة بك</div>
            </a>

            <a href="{{ route('volunteer.past-activities') }}" class="menu-card">
                <div class="menu-icon">🏆</div>
                <div class="menu-title">المشاركات السابقة</div>
                <div class="menu-desc">عرض الفعاليات التي شاركت فيها</div>
            </a>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/volunteer/dashboard.css') }}">
@endpush
