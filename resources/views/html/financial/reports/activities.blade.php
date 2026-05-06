@extends('templates.financial_app')

@section('title', 'تقرير الفعاليات')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/financial/reports-activities.css') }}">
@endpush

@section('content')
    <div class="page-shell rtl">
        <div class="container mx-auto px-4 md:px-6">

            <div class="hero-card rounded-3xl p-8 md:p-10 mb-8 text-center">
                <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4">
                    تقرير الفعاليات
                </h1>
                <p class="text-lg md:text-xl muted-text max-w-3xl mx-auto leading-8">
                    تحليل مالي واضح وهادئ لأداء الفعاليات من حيث التبرعات والمصاريف والصافي
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                <div class="soft-card metric-card rounded-3xl p-7">
                    <p class="text-sm text-slate-500 mb-2">عدد الفعاليات</p>
                    <div class="text-4xl font-bold text-slate-800 mb-2">{{ $activities->count() }}</div>
                    <p class="text-sm muted-text">إجمالي الفعاليات المدرجة</p>
                </div>

                <div class="soft-card metric-card rounded-3xl p-7">
                    <p class="text-sm text-slate-500 mb-2">الفعاليات الناجحة</p>
                    <div class="text-4xl font-bold text-emerald-600 mb-2">
                        {{ $activities->where('net_amount', '>', 0)->count() }}
                    </div>
                    <p class="text-sm muted-text">فعاليات بصافي موجب</p>
                </div>

                <div class="soft-card metric-card rounded-3xl p-7">
                    <p class="text-sm text-slate-500 mb-2">إجمالي الصافي</p>
                    <div
                        class="text-4xl font-bold {{ $activities->sum('net_amount') >= 0 ? 'text-sky-700' : 'text-rose-600' }} mb-2">
                        {{ number_format($activities->sum('net_amount'), 2) }}
                    </div>
                    <p class="text-sm muted-text">$</p>
                </div>
            </div>

            <div class="soft-card rounded-3xl p-8 mb-8">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="section-title text-2xl md:text-3xl font-bold mb-1">الأداء المالي للفعاليات</h3>
                        <p class="muted-text">مقارنة أول 10 فعاليات من حيث التبرعات والمصاريف</p>
                    </div>

                    <button onclick="exportToExcel()" class="export-btn">
                        تصدير البيانات
                    </button>
                </div>

                <div class="chart-wrap">
                    <canvas id="activitiesChart"></canvas>
                </div>
            </div>

            <div class="soft-card rounded-3xl p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="section-title text-2xl font-bold">أفضل 5 فعاليات</h3>
                    <a href="#all-activities" class="text-sky-700 font-semibold hover:text-sky-800">عرض الكل</a>
                </div>

                <div class="space-y-4">
                    @foreach ($activities->sortByDesc('net_amount')->take(5) as $index => $act)
                        <div class="activity-item mini-stat rounded-2xl p-5">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-slate-900 mb-1">{{ $act['title'] }}</h4>
                                        <p class="text-sm text-slate-500">{{ $act['created_at'] }}</p>
                                    </div>
                                </div>

                                <div class="text-left">
                                    <div
                                        class="text-xl font-bold {{ $act['net_amount'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ number_format($act['net_amount'], 2) }} $
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 pt-4 border-t border-slate-100">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-emerald-600">
                                        {{ number_format($act['donations_total'], 0) }}
                                    </div>
                                    <p class="text-xs text-slate-500">تبرعات</p>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-rose-600">
                                        {{ number_format($act['expenses_total'], 0) }}
                                    </div>
                                    <p class="text-xs text-slate-500">مصاريف</p>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-slate-700">
                                        {{ number_format(($act['net_amount'] / max($act['donations_total'], 1)) * 100, 1) }}%
                                    </div>
                                    <p class="text-xs text-slate-500">الكفاءة</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <section id="all-activities">
                <div class="soft-card rounded-3xl overflow-hidden">
                    <div class="p-8 border-b border-slate-200">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div>
                                <h3 class="section-title text-3xl font-bold mb-2">جميع الفعاليات</h3>
                                <p class="muted-text">جدول شامل ومبسط لأداء كل فعالية</p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span class="badge bg-slate-100 text-slate-700">{{ $activities->count() }} فعالية</span>
                                <span class="badge bg-emerald-100 text-emerald-700">
                                    {{ number_format($activities->sum('donations_total'), 0) }} $ تبرعات
                                </span>
                                <span class="badge bg-rose-100 text-rose-700">
                                    {{ number_format($activities->sum('expenses_total'), 0) }} $ مصاريف
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>الفعالية</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>عدد التبرعات</th>
                                    <th>إجمالي التبرعات</th>
                                    <th>عدد المصاريف</th>
                                    <th>إجمالي المصاريف</th>
                                    <th>الصافي</th>
                                    <th>الكفاءة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $act)
                                    <tr>
                                        <td>
                                            <div class="font-bold text-slate-900">{{ $act['title'] }}</div>
                                            <div class="text-xs text-slate-500 mt-1">ID: {{ $act['id'] }}</div>
                                        </td>
                                        <td class="text-slate-700">{{ $act['created_at'] }}</td>
                                        <td>
                                            <span
                                                class="badge bg-emerald-100 text-emerald-700">{{ $act['donations_count'] }}</span>
                                        </td>
                                        <td class="font-bold text-emerald-600">
                                            {{ number_format($act['donations_total'], 2) }} $
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-rose-100 text-rose-700">{{ $act['expenses_count'] }}</span>
                                        </td>
                                        <td class="font-bold text-rose-600">
                                            {{ number_format($act['expenses_total'], 2) }} $
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $act['net_amount'] >= 0 ? 'badge-success' : 'badge-danger' }}">
                                                {{ number_format($act['net_amount'], 2) }} $
                                            </span>
                                        </td>
                                        <td class="text-slate-700">
                                            {{ number_format(($act['net_amount'] / max($act['donations_total'], 1)) * 100, 1) }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-16 text-slate-500">
                                            لا توجد فعاليات حالياً
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection

@php
    $exportData = $activities
        ->map(function ($act) {
            return [
                $act['title'],
                $act['created_at'],
                $act['donations_count'],
                $act['donations_total'],
                $act['expenses_count'],
                $act['expenses_total'],
                $act['net_amount'],
            ];
        })
        ->values()
        ->toArray();
@endphp

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        function exportToExcel() {
            const data = @json($exportData);

            const csv = ['الفعالية,التاريخ,عدد التبرعات,إجمالي التبرعات,عدد المصاريف,إجمالي المصاريف,الصافي']
                .concat(data.map(row => row.join(',')))
                .join('\n');

            const blob = new Blob([new Uint8Array([0xEF, 0xBB, 0xBF]), csv], {
                type: 'text/csv;charset=utf-8;'
            });

            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'activities-report-' + new Date().toISOString().slice(0, 10) + '.csv';
            link.click();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('activitiesChart')?.getContext('2d');
            if (!ctx || typeof Chart === 'undefined' || @json($activities->count()) === 0) return;

            const labels = @json($activities->pluck('title')->take(10)->toArray());
            const donations = @json($activities->pluck('donations_total')->take(10)->toArray());
            const expenses = @json($activities->pluck('expenses_total')->take(10)->toArray());

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'التبرعات',
                        data: donations,
                        backgroundColor: 'rgba(16, 185, 129, 0.72)',
                        borderColor: 'rgba(5, 150, 105, 1)',
                        borderWidth: 1.5,
                        borderRadius: 10,
                        maxBarThickness: 40
                    }, {
                        label: 'المصاريف',
                        data: expenses,
                        backgroundColor: 'rgba(244, 63, 94, 0.62)',
                        borderColor: 'rgba(225, 29, 72, 1)',
                        borderWidth: 1.5,
                        borderRadius: 10,
                        maxBarThickness: 40
                    }]
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
                                color: '#334155'
                            }
                        },
                        tooltip: {
                            rtl: true,
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString() +
                                        ' $';
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
                                maxRotation: 25
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#64748b',
                                callback: function(value) {
                                    return value.toLocaleString() + ' $';
                                }
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.14)'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
