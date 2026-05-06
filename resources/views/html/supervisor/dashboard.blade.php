@extends('templates.supervisor_app')

@section('title', 'لوحة تحكم المشرف')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/financial/dashboard.css') }}">
@endpush

@section('content')
    @php
        $financialManagersCount = \App\Models\Manager::where('manager_type', 'financial')->count();
        $activityManagersCount = \App\Models\Manager::where('manager_type', 'activities')->count();
        $activeManagersCount = \App\Models\Manager::where('status', 'active')->count();
        $inactiveManagersCount = \App\Models\Manager::where('status', 'inactive')->count();
        $activeActivitiesCount = \App\Models\OrganizationActivity::where('status', 'active')->count();
        $organizationsCount = \App\Models\Organization::count();
        $totalManagers = max((int) ($managersCount ?? 0), 1);
        $activityManagersPct = ($activityManagersCount / $totalManagers) * 100;
    @endphp

    <div class="dash">
        <div class="dash-header">
            <h1 class="dash-header__title">لوحة تحكم المشرف</h1>
            <p class="dash-header__sub">نظرة موحّدة على المدراء والأنشطة والجمعيات</p>
        </div>

        <div class="net-banner">
            <div class="net-banner__left">
                <div class="net-banner__eyebrow">إجمالي المدراء في النظام</div>
                <div class="net-banner__amount">{{ $managersCount ?? 0 }}</div>
                <span class="net-badge {{ $inactiveManagersCount > 0 ? 'negative' : 'positive' }}">
                    <i class="fas fa-user-shield"></i>
                    {{ number_format($activityManagersPct, 1) }}% مدراء فعاليات
                </span>
            </div>
            <div class="net-banner__right">
                <div class="net-stat">
                    <div class="net-stat__label">المدراء النشطون</div>
                    <div class="net-stat__val g">{{ $activeManagersCount }}</div>
                </div>
                <div class="net-stat">
                    <div class="net-stat__label">المدراء غير النشطين</div>
                    <div class="net-stat__val r">{{ $inactiveManagersCount }}</div>
                </div>
                <div class="net-stat">
                    <div class="net-stat__label">الأنشطة النشطة</div>
                    <div class="net-stat__val">{{ $activeActivitiesCount }}</div>
                </div>
            </div>
        </div>

        <div class="stats-row">
            <div class="scard blue">
                <div class="scard__icon"><i class="fas fa-users-cog"></i></div>
                <div class="scard__label">إجمالي المدراء</div>
                <div class="scard__val">{{ $managersCount ?? 0 }}</div>
                <div class="scard__sub">مدير مسجّل في المنصة</div>
            </div>
            <div class="scard green">
                <div class="scard__icon"><i class="fas fa-user-tie"></i></div>
                <div class="scard__label">مدراء ماليون</div>
                <div class="scard__val">{{ $financialManagersCount }}</div>
                <div class="scard__sub">مسؤولون عن التبرعات والمصاريف</div>
            </div>
            <div class="scard amber">
                <div class="scard__icon"><i class="fas fa-calendar-check"></i></div>
                <div class="scard__label">مدراء فعاليات</div>
                <div class="scard__val">{{ $activityManagersCount }}</div>
                <div class="scard__sub">مسؤولون عن إدارة الأنشطة</div>
            </div>
            <div class="scard red">
                <div class="scard__icon"><i class="fas fa-building"></i></div>
                <div class="scard__label">عدد الجمعيات</div>
                <div class="scard__val">{{ $organizationsCount }}</div>
                <div class="scard__sub">جمعية مسجلة بالنظام</div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-head">
                <span class="section-head__bar"></span>
                <h2>الوصول السريع</h2>
            </div>
            <div class="actions-grid">
                <a href="{{ route('supervisor.managers.index') }}" class="action-tile blue">
                    <div class="action-tile__ico"><i class="fas fa-users"></i></div>إدارة المدراء
                </a>
                <a href="{{ route('supervisor.activities.index') }}" class="action-tile green">
                    <div class="action-tile__ico"><i class="fas fa-list-check"></i></div>إدارة الأنشطة
                </a>
                <a href="{{ route('supervisor.organizations.index') }}" class="action-tile amber">
                    <div class="action-tile__ico"><i class="fas fa-building"></i></div>إدارة الجمعيات
                </a>
                <a href="{{ route('supervisor.volunteers.index') }}" class="action-tile red">
                    <div class="action-tile__ico"><i class="fas fa-user-check"></i></div>طلبات المتطوعين
                </a>
            </div>
        </div>

        <div class="tables-grid">
            <div class="tcard">
                <div class="tcard__head">
                    <h3><span class="tcard__dot g"></span> آخر المدراء المسجلين</h3>
                    <a href="{{ route('supervisor.managers.index') }}" class="tcard__all">عرض الكل <i
                            class="fas fa-chevron-left" style="font-size:0.55rem"></i></a>
                </div>
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>اسم المستخدم</th>
                            <th>نوع المدير</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentManagers as $manager)
                            <tr>
                                <td>
                                    <div class="cell-name">
                                        <div class="ava">{{ mb_substr($manager->full_name ?? 'م', 0, 1) }}</div>
                                        <span>{{ $manager->full_name ?? 'غير معروف' }}</span>
                                    </div>
                                </td>
                                <td><span class="tag">{{ $manager->username ?? '-' }}</span></td>
                                <td>
                                    <span class="tag {{ ($manager->manager_type ?? '') === 'financial' ? '' : 'r' }}">
                                        {{ ($manager->manager_type ?? '') === 'financial' ? 'مالي' : 'فعاليات' }}
                                    </span>
                                </td>
                                <td><span class="dt">{{ optional($manager->created_at)->format('d/m/Y') }}</span></td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="4"><i class="fas fa-inbox"></i> لا يوجد مدراء حديثون</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="tcard">
                <div class="tcard__head">
                    <h3><span class="tcard__dot r"></span> مؤشرات النظام</h3>
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
                            <td>المدراء النشطون</td>
                            <td><span class="amt g">{{ $activeManagersCount }}</span></td>
                            <td><span class="dt">مدير بحالة active</span></td>
                        </tr>
                        <tr>
                            <td>المدراء غير النشطين</td>
                            <td><span class="amt r">{{ $inactiveManagersCount }}</span></td>
                            <td><span class="dt">مدير بحالة inactive</span></td>
                        </tr>
                        <tr>
                            <td>الأنشطة النشطة</td>
                            <td><span class="amt">{{ $activeActivitiesCount }}</span></td>
                            <td><span class="dt">نشاط جارٍ حالياً</span></td>
                        </tr>
                        <tr>
                            <td>عدد الجمعيات</td>
                            <td><span class="amt">{{ $organizationsCount }}</span></td>
                            <td><span class="dt">جمعية منشورة على النظام</span></td>
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
