@extends('templates.financial_app')

@section('title', 'لوحة التحكم المالية')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/financial/dashboard.css') }}">
@endpush

@section('content')
    <div class="dash">

        <div class="dash-header">
            <h1 class="dash-header__title">لوحة التحكم المالية</h1>
            <p class="dash-header__sub">مرحباً، <strong>{{ $financial_manager->full_name }}</strong> — نظرة عامة على الأداء المالي</p>
        </div>

        @php
            $net = $totalDonationsAmount - $totalExpensesAmount;
            $total = $totalDonationsAmount + $totalExpensesAmount;
            $pct = $total > 0 ? abs($net / $total) * 100 : 0;
        @endphp

        <div class="net-banner">
            <div class="net-banner__left">
                <div class="net-banner__eyebrow">صافي الرصيد الكلي</div>
                <div class="net-banner__amount"><sup>$</sup>{{ number_format(abs($net), 2) }}</div>
                <span class="net-badge {{ $net >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $net >= 0 ? 'up' : 'down' }}"></i>
                    {{ number_format($pct, 1) }}% {{ $net >= 0 ? 'فائض' : 'عجز' }}
                </span>
            </div>
            <div class="net-banner__right">
                <div class="net-stat">
                    <div class="net-stat__label">إجمالي التبرعات</div>
                    <div class="net-stat__val g">${{ number_format($totalDonationsAmount, 2) }}</div>
                </div>
                <div class="net-stat">
                    <div class="net-stat__label">إجمالي المصاريف</div>
                    <div class="net-stat__val r">${{ number_format($totalExpensesAmount, 2) }}</div>
                </div>
                <div class="net-stat">
                    <div class="net-stat__label">الأنشطة النشطة</div>
                    <div class="net-stat__val">{{ $activeActivities }}</div>
                </div>
            </div>
        </div>

        <div class="stats-row">
            <div class="scard blue">
                <div class="scard__icon"><i class="fas fa-hand-holding-dollar"></i></div>
                <div class="scard__label">إجمالي التبرعات</div>
                <div class="scard__val">${{ number_format($totalDonationsAmount, 2) }}</div>
                <div class="scard__sub">{{ $totalDonationsCount }} معاملة · متوسط
                    <b>${{ number_format($totalDonationsAmount / max($totalDonationsCount, 1), 2) }}</b></div>
            </div>
            <div class="scard red">
                <div class="scard__icon"><i class="fas fa-receipt"></i></div>
                <div class="scard__label">إجمالي المصاريف</div>
                <div class="scard__val">${{ number_format($totalExpensesAmount, 2) }}</div>
                <div class="scard__sub">{{ $totalExpensesCount }} إيصال · متوسط
                    <b>${{ number_format($totalExpensesAmount / max($totalExpensesCount, 1), 2) }}</b></div>
            </div>
            <div class="scard green">
                <div class="scard__icon"><i class="fas fa-calendar-check"></i></div>
                <div class="scard__label">الأنشطة النشطة</div>
                <div class="scard__val">{{ $activeActivities }}</div>
                <div class="scard__sub">نشاط جارٍ حالياً</div>
            </div>
            <div class="scard amber">
                <div class="scard__icon"><i class="fas fa-users"></i></div>
                <div class="scard__label">عدد المانحين</div>
                <div class="scard__val">{{ $donorsCount }}</div>
                <div class="scard__sub">مانح مسجّل</div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-head">
                <span class="section-head__bar"></span>
                <h2>الوصول السريع</h2>
            </div>
            <div class="actions-grid">
                <a href="{{ route('financial.donations.index') }}" class="action-tile blue">
                    <div class="action-tile__ico"><i class="fas fa-hand-holding-dollar"></i></div>إدارة التبرعات
                </a>
                <a href="{{ route('financial.expenses.index') }}" class="action-tile red">
                    <div class="action-tile__ico"><i class="fas fa-receipt"></i></div>إدارة المصاريف
                </a>
                <a href="{{ route('financial.donations.index') }}" class="action-tile green">
                    <div class="action-tile__ico"><i class="fas fa-list-check"></i></div>جميع التبرعات
                </a>
                <a href="{{ route('financial.reports.index') }}" class="action-tile amber">
                    <div class="action-tile__ico"><i class="fas fa-chart-bar"></i></div>التقارير
                </a>
            </div>
        </div>

        <div class="tables-grid">
            <div class="tcard">
                <div class="tcard__head">
                    <h3><span class="tcard__dot g"></span> آخر التبرعات</h3>
                    <a href="{{ route('financial.donations.index') }}" class="tcard__all">عرض الكل <i
                            class="fas fa-chevron-left" style="font-size:0.55rem"></i></a>
                </div>
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>المانح</th>
                            <th>الفعالية</th>
                            <th>المبلغ</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDonations as $donation)
                            <tr>
                                <td>
                                    <div class="cell-name">
                                        <div class="ava">{{ mb_substr($donation->donor->name ?? 'غ', 0, 1) }}</div>
                                        <span>{{ $donation->donor->name ?? 'غير معروف' }}</span>
                                    </div>
                                </td>
                                <td><span
                                        class="tag">{{ Str::limit($donation->activity->title ?? 'غير محدد', 20) }}</span>
                                </td>
                                <td><span class="amt g">${{ number_format($donation->amount, 2) }}</span></td>
                                <td><span class="dt">{{ $donation->created_at->format('d/m/Y') }}</span></td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="4"><i class="fas fa-inbox"></i> لا توجد تبرعات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="tcard">
                <div class="tcard__head">
                    <h3><span class="tcard__dot r"></span> آخر المصاريف</h3>
                    <a href="{{ route('financial.expenses.index') }}" class="tcard__all">عرض الكل <i
                            class="fas fa-chevron-left" style="font-size:0.55rem"></i></a>
                </div>
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>الوصف</th>
                            <th>الفعالية</th>
                            <th>المبلغ</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentExpenses as $expense)
                            <tr>
                                <td>
                                    <div class="cell-name">
                                        <div class="ava r"><i class="fas fa-receipt" style="font-size:0.62rem"></i></div>
                                        <span>{{ Str::limit($expense->description, 20) }}</span>
                                    </div>
                                </td>
                                <td><span class="tag r">{{ Str::limit($expense->activity->title ?? 'عام', 18) }}</span>
                                </td>
                                <td><span class="amt r">${{ number_format($expense->amount, 2) }}</span></td>
                                <td><span
                                        class="dt">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="4"><i class="fas fa-inbox"></i> لا توجد مصاريف</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
