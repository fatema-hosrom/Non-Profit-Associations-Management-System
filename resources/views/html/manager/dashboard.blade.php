@extends('templates.manager_app')

@section('title', 'لوحة تحكم المدير')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/financial/dashboard.css') }}">
@endpush

@section('content')
    @php
        $managerId = $manager->id ?? ($manager['id'] ?? null);
        $activeActivitiesCount = \App\Models\OrganizationActivity::where('manager_id', $managerId)->where('status', 'active')->count();
        $draftActivitiesCount = \App\Models\OrganizationActivity::where('manager_id', $managerId)->where('status', 'draft')->count();
        $closedActivitiesCount = \App\Models\OrganizationActivity::where('manager_id', $managerId)->where('status', 'closed')->count();
        $organizationsCount = \App\Models\Organization::where('created_by', $managerId)->count();
        $volunteersCount = \App\Models\Volunteer::count();
        $activeRatio = ($activitiesCount ?? 0) > 0 ? ($activeActivitiesCount / $activitiesCount) * 100 : 0;
    @endphp

    <div class="dash">
        <div class="dash-header">
            <h1 class="dash-header__title">لوحة تحكم مدير الفعاليات</h1>
            <p class="dash-header__sub">مرحباً، <strong>{{ $manager['full_name'] ?? ($manager->full_name ?? '') }}</strong> — نظرة عامة على فعالياتك</p>
        </div>

        <div class="net-banner">
            <div class="net-banner__left">
                <div class="net-banner__eyebrow">إجمالي الفعاليات الخاصة بك</div>
                <div class="net-banner__amount">{{ $activitiesCount ?? 0 }}</div>
                <span class="net-badge {{ $activeActivitiesCount > 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-chart-line"></i>
                    {{ number_format($activeRatio, 1) }}% فعاليات نشطة
                </span>
            </div>
            <div class="net-banner__right">
                <div class="net-stat">
                    <div class="net-stat__label">فعاليات نشطة</div>
                    <div class="net-stat__val g">{{ $activeActivitiesCount }}</div>
                </div>
                <div class="net-stat">
                    <div class="net-stat__label">فعاليات مسودة</div>
                    <div class="net-stat__val">{{ $draftActivitiesCount }}</div>
                </div>
                <div class="net-stat">
                    <div class="net-stat__label">فعاليات مغلقة</div>
                    <div class="net-stat__val r">{{ $closedActivitiesCount }}</div>
                </div>
            </div>
        </div>

        <div class="stats-row">
            <div class="scard blue">
                <div class="scard__icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="scard__label">كل الفعاليات</div>
                <div class="scard__val">{{ $activitiesCount ?? 0 }}</div>
                <div class="scard__sub">فعالية أنشأتها أنت</div>
            </div>
            <div class="scard green">
                <div class="scard__icon"><i class="fas fa-bullhorn"></i></div>
                <div class="scard__label">فعاليات نشطة</div>
                <div class="scard__val">{{ $activeActivitiesCount }}</div>
                <div class="scard__sub">متاحة حالياً للجمهور</div>
            </div>
            <div class="scard amber">
                <div class="scard__icon"><i class="fas fa-building"></i></div>
                <div class="scard__label">الجمعيات التي أنشأتها</div>
                <div class="scard__val">{{ $organizationsCount }}</div>
                <div class="scard__sub">جمعية مسجلة باسمك</div>
            </div>
            <div class="scard red">
                <div class="scard__icon"><i class="fas fa-users"></i></div>
                <div class="scard__label">إجمالي المتطوعين</div>
                <div class="scard__val">{{ $volunteersCount }}</div>
                <div class="scard__sub">متطوع على المنصة</div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-head">
                <span class="section-head__bar"></span>
                <h2>الوصول السريع</h2>
            </div>
            <div class="actions-grid">
                <a href="{{ route('manager.activities.index') }}" class="action-tile blue">
                    <div class="action-tile__ico"><i class="fas fa-list-check"></i></div>إدارة الفعاليات
                </a>
                <a href="{{ route('manager.activities.add') }}" class="action-tile green">
                    <div class="action-tile__ico"><i class="fas fa-plus"></i></div>إضافة فعالية
                </a>
                <a href="{{ route('manager.activity_volunteers.index') }}" class="action-tile amber">
                    <div class="action-tile__ico"><i class="fas fa-user-check"></i></div>إدارة متطوعي الفعاليات
                </a>
                <a href="{{ route('manager.organizations.index') }}" class="action-tile red">
                    <div class="action-tile__ico"><i class="fas fa-building"></i></div>إدارة الجمعيات
                </a>
            </div>
        </div>

        <div class="tables-grid">
            <div class="tcard">
                <div class="tcard__head">
                    <h3><span class="tcard__dot g"></span> آخر الفعاليات المضافة</h3>
                    <a href="{{ route('manager.activities.index') }}" class="tcard__all">عرض الكل <i
                            class="fas fa-chevron-left" style="font-size:0.55rem"></i></a>
                </div>
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>العنوان</th>
                            <th>النوع</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_activities as $activity)
                            @php
                                $type = $activity->activity_type ?? ($activity['activity_type'] ?? '');
                                $status = $activity->status ?? ($activity['status'] ?? 'draft');
                            @endphp
                            <tr>
                                <td>
                                    <div class="cell-name">
                                        <div class="ava">{{ mb_substr($activity->title ?? ($activity['title'] ?? 'ف'), 0, 1) }}</div>
                                        <span>{{ \Illuminate\Support\Str::limit($activity->title ?? ($activity['title'] ?? ''), 24) }}</span>
                                    </div>
                                </td>
                                <td><span
                                        class="tag">{{ $type === 'donation' ? 'تبرع' : ($type === 'volunteer' ? 'تطوع' : ($type === 'both' ? 'شاملة' : 'عادية')) }}</span>
                                </td>
                                <td><span
                                        class="tag {{ $status === 'closed' ? 'r' : '' }}">{{ $status === 'active' ? 'نشطة' : ($status === 'closed' ? 'مغلقة' : 'مسودة') }}</span>
                                </td>
                                <td><span class="dt">{{ \Carbon\Carbon::parse($activity->created_at)->format('d/m/Y') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="4"><i class="fas fa-inbox"></i> لا توجد فعاليات حديثة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="tcard">
                <div class="tcard__head">
                    <h3><span class="tcard__dot r"></span> مؤشرات سريعة</h3>
                </div>
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>المؤشر</th>
                            <th>القيمة</th>
                            <th>الوصف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>الفعاليات النشطة</td>
                            <td><span class="amt g">{{ $activeActivitiesCount }}</span></td>
                            <td><span class="dt">قيد التنفيذ حاليًا</span></td>
                        </tr>
                        <tr>
                            <td>الفعاليات المسودة</td>
                            <td><span class="amt">{{ $draftActivitiesCount }}</span></td>
                            <td><span class="dt">تحتاج استكمال قبل النشر</span></td>
                        </tr>
                        <tr>
                            <td>الفعاليات المغلقة</td>
                            <td><span class="amt r">{{ $closedActivitiesCount }}</span></td>
                            <td><span class="dt">اكتمل تنفيذها</span></td>
                        </tr>
                        <tr>
                            <td>الجمعيات الخاصة بك</td>
                            <td><span class="amt">{{ $organizationsCount }}</span></td>
                            <td><span class="dt">منظمات تدير فعالياتها</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/js/bootstrap.js"></script>
@endpush
