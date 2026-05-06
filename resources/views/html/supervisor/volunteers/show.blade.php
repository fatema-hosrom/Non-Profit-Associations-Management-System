@extends('templates.supervisor_app')

@section('title', 'تفاصيل المتطوع')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/supervisor/volunteers.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="page-header">
            <h1>تفاصيل طلب المتطوع</h1>
        </div>
        <div class="card">
            @php $status = $volunteer->status ?? 'pending'; @endphp
            <div class="volunteer-header">
                <div class="volunteer-name">{{ $volunteer->name }}</div>
                <div class="status {{ $status }}">
                    {{ $status === 'pending' ? 'معلقة' : ($status === 'accepted' ? 'مقبولة' : 'مرفوضة') }}</div>
            </div>
            <div class="info-grid">
                <div class="info-item"><label><i class="fa fa-envelope"></i> البريد
                        الإلكتروني</label><span>{{ $volunteer->email }}</span></div>
                <div class="info-item"><label><i class="fa fa-phone"></i> رقم
                        الهاتف</label><span>{{ $volunteer->phone }}</span></div>
                <div class="info-item"><label><i class="fa fa-venus-mars"></i>
                        الجنس</label><span>{{ ucfirst($volunteer->gender) }}</span></div>
                <div class="info-item"><label><i class="fa fa-calendar"></i> العمر</label><span>{{ $volunteer->age }}</span>
                </div>
                <div class="info-item"><label><i class="fa fa-flag"></i>
                        الجنسية</label><span>{{ $volunteer->nationality }}</span></div>
                <div class="info-item"><label><i class="fa fa-location-dot"></i>
                        العنوان</label><span>{{ $volunteer->address }}</span></div>
                <div class="info-item"><label><i class="fa fa-lightbulb"></i>
                        المهارات</label><span>{{ $volunteer->skills ?? 'غير محددة' }}</span></div>
                <div class="info-item"><label><i class="fa fa-briefcase"></i>
                        الخبرة</label><span>{{ $volunteer->experience ?? 'غير محددة' }}</span></div>
                <div class="info-item"><label><i class="fa fa-graduation-cap"></i> المستوى
                        التعليمي</label><span>{{ $volunteer->education_level ?? 'غير محدد' }}</span></div>
                <div class="info-item"><label><i class="fa fa-clock"></i>
                        التوفر</label><span>{{ $volunteer->availability ?? 'غير محدد' }}</span></div>
                <div class="info-item"><label><i class="fa fa-star"></i> الأدوار
                        المفضلة</label><span>{{ $volunteer->preferred_roles ?? 'غير محددة' }}</span></div>
                <div class="info-item"><label><i class="fa fa-language"></i>
                        اللغات</label><span>{{ $volunteer->languages ?? 'غير محددة' }}</span></div>
                <div class="info-item"><label><i class="fa fa-phone-square-alt"></i> جهة
                        الطوارئ</label><span>{{ $volunteer->emergency_contact ?? 'غير محددة' }}</span></div>
            </div>
            <div class="actions">
                <form method="POST" action="{{ route('supervisor.volunteers.updateStatus', $volunteer->id) }}">@csrf
                    @method('PATCH')<input type="hidden" name="status" value="accepted"><button class="btn-accept"
                        @if ($status === 'accepted') disabled @endif>قبول الطلب</button></form>
                <form method="POST" action="{{ route('supervisor.volunteers.updateStatus', $volunteer->id) }}">@csrf
                    @method('PATCH')<input type="hidden" name="status" value="rejected"><button class="btn-reject"
                        @if ($status === 'rejected') disabled @endif>رفض الطلب</button></form>
            </div>
            <a href="{{ route('supervisor.volunteers.index') }}" class="back-link">العودة إلى قائمة المتطوعين</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
@endpush
