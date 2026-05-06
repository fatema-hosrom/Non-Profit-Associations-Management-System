@extends('templates.financial_app')

@section('title', 'تقرير التبرعات')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/financial/reports-donations.css') }}">
@endpush

@section('content')
    <div class="page-shell rtl">
        <div class="container mx-auto px-4 md:px-6">

            <div class="hero-card rounded-3xl p-8 md:p-10 mb-8 text-center">
                <h1 class="hero-title text-4xl md:text-5xl font-extrabold mb-4">تقرير التبرعات</h1>
                <p class="text-lg muted-text max-w-3xl mx-auto leading-8">
                    عرض تفصيلي وهادئ للتبرعات، مع فلاتر واضحة ومخطط يوضح توزيع الأنواع
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <div class="soft-card metric-card rounded-3xl p-6">
                    <p class="text-sm text-slate-500 mb-2">إجمالي المبلغ</p>
                    <div class="text-3xl font-bold text-emerald-600 mb-1">{{ number_format($totalAmount, 2) }}</div>
                    <p class="text-sm muted-text">$</p>
                </div>

                <div class="soft-card metric-card rounded-3xl p-6">
                    <p class="text-sm text-slate-500 mb-2">عدد التبرعات</p>
                    <div class="text-3xl font-bold text-sky-700 mb-1">{{ number_format($donationsCount) }}</div>
                    <p class="text-sm muted-text">عملية تبرع</p>
                </div>

                <div class="soft-card metric-card rounded-3xl p-6">
                    <p class="text-sm text-slate-500 mb-2">متوسط التبرع</p>
                    <div class="text-3xl font-bold text-violet-700 mb-1">
                        {{ $donationsCount > 0 ? number_format($totalAmount / $donationsCount, 2) : '0' }}
                    </div>
                    <p class="text-sm muted-text">$</p>
                </div>

                <div class="soft-card metric-card rounded-3xl p-6 flex items-center justify-center">
                    <button onclick="exportToCSV()" class="btn w-full bg-slate-900 text-white hover:bg-slate-800">
                        تصدير CSV
                    </button>
                </div>
            </div>

            <div class="soft-card rounded-3xl p-6 mb-8">
                <h3 class="text-2xl font-bold text-slate-800 mb-5">فلترة التقرير</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input">

                    <select name="type" class="form-select">
                        <option value="">كل الأنواع</option>
                        <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>نقدي</option>
                        <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>أونلاين</option>
                        <option value="check" {{ request('type') == 'check' ? 'selected' : '' }}>شيك</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>

                    <button type="submit" class="btn bg-emerald-600 text-white hover:bg-emerald-700">
                        تطبيق الفلترة
                    </button>

                    <button type="button" onclick="clearFilters()"
                        class="btn bg-slate-200 text-slate-800 hover:bg-slate-300">
                        مسح الفلاتر
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
                <div class="soft-card rounded-3xl p-8">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6">توزيع التبرعات حسب النوع</h3>
                    <div class="chart-wrap">
                        <canvas id="donationTypesChart"></canvas>
                    </div>
                </div>

                <div class="soft-card rounded-3xl p-8">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6">أحدث التبرعات</h3>

                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse($donations->take(5) as $don)
                            <div class="recent-item rounded-2xl p-4">
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <span class="text-lg font-bold text-emerald-600">
                                        {{ number_format($don->amount, 2) }} $
                                    </span>
                                    <span class="badge bg-emerald-100 text-emerald-700">
                                        {{ $don->donation_type_ar ?? $don->donation_type }}
                                    </span>
                                </div>

                                <p class="text-sm text-slate-700 mb-1">{{ $don->donor->name ?? 'غير محدد' }}</p>
                                <p class="text-xs text-slate-500 mb-1">{{ $don->activity->title ?? 'غير محدد' }}</p>
                                <p class="text-xs text-slate-400">{{ $don->date->format('Y-m-d H:i') }}</p>
                            </div>
                        @empty
                            <p class="text-center py-12 text-slate-500">لا توجد تبرعات في الفترة المحددة</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="soft-card rounded-3xl overflow-hidden">
                <div class="p-8 border-b border-slate-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <h3 class="text-3xl font-bold text-slate-800">جدول التبرعات الكامل</h3>
                        <div class="text-sm text-slate-500">
                            عرض {{ $donations->firstItem() ?? 0 }} - {{ $donations->lastItem() ?? 0 }} من أصل
                            {{ $donations->total() }}
                        </div>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>العنوان</th>
                                <th>المتبرع</th>
                                <th>المبلغ</th>
                                <th>النوع</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($donations as $don)
                                <tr>
                                    <td>
                                        <div class="font-semibold text-slate-900 max-w-xs truncate">
                                            {{ $don->activity->title ?? 'غير محدد' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-slate-900 font-medium">{{ $don->donor->name ?? 'غير محدد' }}</div>
                                        @if (!empty($don->donor?->phone))
                                            <div class="text-xs text-slate-500">{{ $don->donor->phone }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-emerald-100 text-emerald-700">
                                            {{ number_format($don->amount, 2) }} $
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-sky-100 text-sky-700">
                                            {{ $don->donation_type_ar ?? $don->donation_type }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-sm text-slate-800">{{ $don->date->format('Y-m-d') }}</div>
                                        <div class="text-xs text-slate-500">{{ $don->date->format('H:i') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-16 text-slate-500">
                                        لا توجد بيانات تطابق شروط الفلترة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-8 bg-slate-50">
                    {{ $donations->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        function exportToCSV() {
            const headers = ['العنوان', 'المتبرع', 'المبلغ', 'النوع', 'التاريخ'];
            const csv = [headers.join(',')];

            document.querySelectorAll('.report-table tbody tr').forEach(row => {
                const cells = Array.from(row.querySelectorAll('td')).map(cell =>
                    '"' + cell.textContent.replace(/"/g, '""').trim() + '"'
                );
                if (cells.length) csv.push(cells.join(','));
            });

            const blob = new Blob([new Uint8Array([0xEF, 0xBB, 0xBF]), csv.join('\n')], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'donations-report-' + new Date().toISOString().slice(0, 10) + '.csv';
            link.click();
        }

        function clearFilters() {
            const url = new URL(window.location);
            url.searchParams.delete('start_date');
            url.searchParams.delete('end_date');
            url.searchParams.delete('type');
            window.location.href = url;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('donationTypesChart')?.getContext('2d');
            if (!ctx || typeof Chart === 'undefined') return;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['نقدي', 'أونلاين', 'شيك', 'أخرى'],
                    datasets: [{
                        data: [
                            {{ $donations->where('donation_type', 'cash')->count() }},
                            {{ $donations->where('donation_type', 'online')->count() }},
                            {{ $donations->where('donation_type', 'check')->count() }},
                            {{ $donations->where('donation_type', 'other')->count() }}
                        ],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                        borderColor: '#ffffff',
                        borderWidth: 4,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '64%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 10,
                                padding: 18,
                                color: '#334155'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.label + ': ' + ctx.parsed.toLocaleString() + ' تبرع';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
