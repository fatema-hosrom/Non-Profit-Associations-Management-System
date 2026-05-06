@extends('templates.financial_app')

@section('title', 'عرض التقارير و الاحصائيات المالية')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/financial/reports-index.css') }}">
@endpush

@section('content')
    <div class="dashboard-shell no-horizontal-scroll">
        <div class="container mx-auto px-4 md:px-6">

            <!-- Header -->
            <div class="hero-block rounded-3xl p-8 md:p-10 mb-8">
                <div class="text-center">
                    <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-extrabold mb-3">
                        عرض التقارير و الاحصائيات المالية
                    </h1>
                    <p class="hero-subtitle text-lg max-w-3xl mx-auto leading-8">
                        لوحة مالية احترافية لمتابعة التبرعات والمصاريف والصافي والإحصائيات الشهرية بشكل واضح وهادئ
                    </p>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">

                <div class="glass-card rounded-3xl p-7">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-2">إجمالي التبرعات</p>
                            <h2 class="text-3xl md:text-4xl font-bold text-emerald-600 leading-tight">
                                {{ number_format($totalDonations, 2) }}
                            </h2>
                            <span class="text-emerald-600 text-xl font-semibold">$</span>
                        </div>
                        <div class="stat-icon bg-emerald-50">
                            <svg class="w-9 h-9 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-3xl p-7">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-2">إجمالي المصاريف</p>
                            <h2 class="text-3xl md:text-4xl font-bold text-rose-600 leading-tight">
                                {{ number_format($totalExpenses, 2) }}
                            </h2>
                            <span class="text-rose-600 text-xl font-semibold">$</span>
                        </div>
                        <div class="stat-icon bg-rose-50">
                            <svg class="w-9 h-9 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="soft-highlight rounded-3xl p-7">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500 mb-2">الصافي</p>
                            <h2
                                class="text-3xl md:text-4xl font-bold {{ $netAmount >= 0 ? 'text-sky-700' : 'text-rose-700' }} leading-tight">
                                {{ number_format($netAmount, 2) }}
                            </h2>
                            <span class="text-slate-700 text-xl font-semibold">$</span>
                        </div>
                        <div class="stat-icon bg-slate-100">
                            <svg class="w-9 h-9 text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">

                <div class="glass-card rounded-3xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl md:text-3xl font-bold section-title">الإحصائيات الشهرية</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <div class="glass-card rounded-3xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl md:text-3xl font-bold section-title">توزيع التبرعات حسب النوع</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="donationTypesChart"></canvas>
                    </div>
                </div>

            </div>

            <!-- Bottom Section -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 bottom-grid">

                <!-- Quick Reports -->
                <div class="glass-card rounded-3xl p-8 equal-card order-2 xl:order-1">
                    <h3 class="text-2xl md:text-3xl font-bold section-title mb-6">التقارير السريعة</h3>

                    <div class="compact-links">
                        <a href="{{ route('financial.reports.activities') }}"
                            class="quick-link flex items-center gap-4 bg-white rounded-2xl border border-slate-200 px-5 py-4">
                            <div class="stat-icon bg-sky-50 !w-14 !h-14">
                                <svg class="w-7 h-7 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-900 mb-1">تقرير الفعاليات</h4>
                                <p class="text-sm muted-text">عرض تفصيلي ومنظم لأداء جميع الفعاليات</p>
                            </div>
                        </a>

                        <a href="{{ route('financial.reports.donations') }}"
                            class="quick-link flex items-center gap-4 bg-white rounded-2xl border border-slate-200 px-5 py-4">
                            <div class="stat-icon bg-emerald-50 !w-14 !h-14">
                                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-900 mb-1">تقرير التبرعات</h4>
                                <p class="text-sm muted-text">تفاصيل التبرعات والمبالغ والأنواع بشكل كامل</p>
                            </div>
                        </a>

                        <a href="{{ route('financial.reports.expenses') }}"
                            class="quick-link flex items-center gap-4 bg-white rounded-2xl border border-slate-200 px-5 py-4">
                            <div class="stat-icon bg-rose-50 !w-14 !h-14">
                                <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-900 mb-1">تقرير المصاريف</h4>
                                <p class="text-sm muted-text">مراجعة المصروفات والقيود المالية بسهولة</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Top Activities -->
                <div class="glass-card rounded-3xl p-8 equal-card order-1 xl:order-2">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl md:text-3xl font-bold section-title">أفضل 5 فعاليات</h3>
                    </div>

                    <div class="table-wrap">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="table-head">
                                    <th class="px-5 py-4 text-right font-bold text-slate-600 rounded-tr-2xl">الفعالية</th>
                                    <th class="px-5 py-4 text-right font-bold text-slate-600">التبرعات</th>
                                    <th class="px-5 py-4 text-right font-bold text-slate-600 rounded-tl-2xl">الصافي</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($topActivities as $activity)
                                    <tr class="table-row transition-colors">
                                        <td class="px-5 py-4 font-semibold text-slate-800 max-w-xs truncate">
                                            {{ $activity['title'] }}
                                        </td>
                                        <td class="px-5 py-4 font-bold text-emerald-600 whitespace-nowrap">
                                            {{ number_format($activity['donations'], 2) }} $
                                        </td>
                                        <td
                                            class="px-5 py-4 font-bold whitespace-nowrap {{ $activity['net'] >= 0 ? 'text-sky-700' : 'text-rose-600' }}">
                                            {{ number_format($activity['net'], 2) }} $
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-5 py-8 text-center text-slate-500">
                                            لا توجد بيانات متاحة حالياً
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthlyCanvas = document.getElementById('monthlyChart');
            const donationTypesCanvas = document.getElementById('donationTypesChart');

            const monthlyLabels = @json(array_column($monthlyStats ?? [], 'month'));
            const monthlyDonations = @json(array_column($monthlyStats ?? [], 'donations'));
            const monthlyExpenses = @json(array_column($monthlyStats ?? [], 'expenses'));

            const donationTypesArray = @json($donationTypes ? $donationTypes->toArray() : []);
            const donationTypeLabels = donationTypesArray.map(item => item.type);
            const donationTypeTotals = donationTypesArray.map(item => item.total);

            if (monthlyCanvas && typeof Chart !== 'undefined' && monthlyLabels.length > 0) {
                const monthlyCtx = monthlyCanvas.getContext('2d');

                new Chart(monthlyCtx, {
                    type: 'bar',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                                label: 'التبرعات',
                                data: monthlyDonations,
                                backgroundColor: 'rgba(16, 185, 129, 0.72)',
                                borderColor: 'rgba(5, 150, 105, 1)',
                                borderWidth: 1.5,
                                borderRadius: 10,
                                maxBarThickness: 42
                            },
                            {
                                label: 'المصاريف',
                                data: monthlyExpenses,
                                backgroundColor: 'rgba(244, 63, 94, 0.65)',
                                borderColor: 'rgba(225, 29, 72, 1)',
                                borderWidth: 1.5,
                                borderRadius: 10,
                                maxBarThickness: 42
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 10,
                                    color: '#334155',
                                    font: {
                                        family: 'inherit',
                                        size: 13
                                    }
                                }
                            },
                            tooltip: {
                                rtl: true,
                                titleAlign: 'right',
                                bodyAlign: 'right',
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.raw
                                            .toLocaleString() + ' $';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#64748b',
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.15)'
                                },
                                ticks: {
                                    color: '#64748b',
                                    font: {
                                        size: 12
                                    },
                                    callback: function(value) {
                                        return value.toLocaleString() + ' $';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            if (donationTypesCanvas && typeof Chart !== 'undefined' && donationTypeLabels.length > 0) {
                const donationTypesCtx = donationTypesCanvas.getContext('2d');

                new Chart(donationTypesCtx, {
                    type: 'doughnut',
                    data: {
                        labels: donationTypeLabels,
                        datasets: [{
                            data: donationTypeTotals,
                            backgroundColor: [
                                '#2563eb',
                                '#14b8a6',
                                '#f59e0b',
                                '#ef4444',
                                '#8b5cf6',
                                '#06b6d4',
                                '#84cc16',
                                '#f97316'
                            ],
                            borderColor: '#ffffff',
                            borderWidth: 4,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '66%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 10,
                                    color: '#334155',
                                    padding: 18,
                                    font: {
                                        family: 'inherit',
                                        size: 13
                                    }
                                }
                            },
                            tooltip: {
                                rtl: true,
                                titleAlign: 'right',
                                bodyAlign: 'right',
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.raw.toLocaleString() +
                                            ' $';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
