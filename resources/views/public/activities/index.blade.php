@extends('public.template_layouts.app')

@section('title', 'جميع الفعاليات - ساهم')

@section('content')

    <section class="py-14">

        <!-- العنوان -->
        <div class="text-center mb-10">
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-3">
                فعاليات ساهم
            </h2>
            <p class="text-gray-600 text-lg mb-7 max-w-2xl mx-auto">
                ابحث وشارك بالتبرع أو التطوع بكل سهولة
            </p>

            <!-- زر الفعاليات المنجزة -->
            <a href="{{ route('public.completed-activities') }}"
               class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-indigo-600 hover:from-emerald-700 hover:to-indigo-700 text-white py-3 px-8 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 shadow-lg shadow-indigo-200">
                <i class="fas fa-trophy ml-2"></i>
                عرض الفعاليات المنجزة
            </a>
        </div>

        <!-- أدوات البحث والفلترة -->
        <div class="bg-white/80 backdrop-blur rounded-2xl border border-gray-200 shadow-sm p-4 md:p-5 flex flex-col md:flex-row gap-4 mb-10 items-center justify-between">

            <!-- البحث -->
            <div class="w-full md:w-1/2">
                <input id="searchInput" type="text" placeholder="ابحث عن فعالية..."
                    class="w-full rounded-xl border border-gray-300 px-5 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
            </div>

            <!-- الفلاتر -->
            <div class="flex gap-3 w-full md:w-auto">
                <button data-filter="all" class="filter-btn bg-indigo-700 text-white px-5 py-2 rounded-xl font-semibold w-full md:w-auto">
                    الكل
                </button>
                <button data-filter="donation" class="filter-btn bg-gray-100 text-gray-700 px-5 py-2 rounded-xl w-full md:w-auto">
                    تبرع
                </button>
                <button data-filter="volunteer" class="filter-btn bg-gray-100 text-gray-700 px-5 py-2 rounded-xl w-full md:w-auto">
                    تطوع
                </button>
            </div>
        </div>

        <!-- قائمة الفعاليات -->
        <div id="activitiesWrapper" class="grid grid-cols-1 xl:grid-cols-2 gap-8">

            @foreach ($activities as $activity)
                <div class="activity-item flex flex-col md:flex-row bg-white rounded-3xl border border-gray-100 shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden"
                    data-type="{{ $activity->activity_type }}" data-title="{{ strtolower($activity->title) }}">

                    <!-- الصورة -->
                    <div class="md:w-2/5 h-64 md:h-auto relative overflow-hidden bg-gray-100">
                        @if ($activity->image)
                            <img src="{{ asset('assets/images/activities/' . $activity->image) }}"
                                class="w-full h-full object-cover transition-transform duration-700 hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        @endif

                        <!-- شارة النوع -->
                        <span
                            class="absolute top-4 right-4 text-xs px-3 py-1 rounded-full text-white
                        {{ $activity->activity_type === 'donation'
                            ? 'bg-green-600'
                            : ($activity->activity_type === 'volunteer'
                                ? 'bg-blue-600'
                                : 'bg-purple-600') }}">
                            {{ $activity->activity_type === 'donation'
                                ? 'تبرع'
                                : ($activity->activity_type === 'volunteer'
                                    ? 'تطوع'
                                    : 'تبرع & تطوع') }}
                        </span>
                    </div>

                    <!-- المحتوى -->
                    <div class="flex-1 p-6 md:p-7 flex flex-col">
                        <h3 class="text-2xl font-extrabold mb-2 text-gray-800">
                            {{ $activity->title }}
                        </h3>

                        <p class="text-gray-600 mb-5 line-clamp-3 leading-7">
                            {{ $activity->description }}
                        </p>

                        <!-- Progress Bar للتبرع -->
                        @if ($activity->donationSettings)
                            @php
                                $goal = $activity->donationSettings->target_amount;
                                $current = $activity->donationSettings->collected_amount;
                                $percent = $goal > 0 ? min(100, ($current / $goal) * 100) : 0;
                            @endphp

                            <div class="mb-5 bg-emerald-50 border border-emerald-100 rounded-xl p-3">
                                <div class="flex justify-between text-sm mb-1 text-emerald-900">
                                    <span class="font-semibold">التبرعات</span>
                                    <span>{{ number_format($current) }} / {{ number_format($goal) }} $</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-green-600 h-3 rounded-full transition-all"
                                        style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- معلومات إضافية -->
                        <div class="mt-auto text-sm text-gray-500">
                            <div class="flex justify-between items-center border-t border-gray-100 pt-4 mb-4">
                                <span class="font-medium text-gray-700">
                                    <i class="fas fa-users ml-1 text-indigo-600"></i>
                                    {{ $activity->volunteerRequirements->required_volunteers ?? 'غير محدد' }}
                                    متطوع
                                </span>
                                <a href="{{ route('public.activities.sahem.show', $activity->id) }}"
                                    class="bg-gray-900 text-white px-5 py-2 rounded-xl hover:bg-indigo-700 transition">
                                    عرض التفاصيل
                                </a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @if ($activity->donationSettings && $activity->donationSettings->collected_amount < $activity->donationSettings->target_amount)
                                    <a href="{{ route('public.activities.sahem.show', $activity->id) . '?donate=1' }}"
                                        class="inline-flex items-center justify-center bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-700 transition">
                                        <i class="fas fa-hand-holding-heart ml-2"></i>
                                        تبرع الآن
                                    </a>
                                @elseif ($activity->donationSettings)
                                    <div class="inline-flex items-center justify-center bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold">
                                        الحملة مكتملة الآن
                                    </div>
                                @endif

                                @if (($activity->activity_type === 'volunteer' || $activity->activity_type === 'both') && $activity->volunteerRequirements && ($activity->volunteerRequirements->required_volunteers ?? 0) > 0)
                                    <a href="{{ route('public.activities.sahem.show', $activity->id) }}"
                                        class="inline-flex items-center justify-center bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
                                        <i class="fas fa-user-plus ml-2"></i>
                                        تطوع الآن
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <div id="noResults" class="hidden mt-10 text-center bg-white border border-gray-200 rounded-2xl p-10 text-gray-600">
            لا توجد فعاليات مطابقة للبحث أو الفلترة الحالية.
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        const searchInput = document.getElementById('searchInput');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const items = document.querySelectorAll('.activity-item');

        let currentFilter = 'all';

        function filterActivities() {
            const search = searchInput.value.toLowerCase();
            let visibleCount = 0;

            items.forEach(item => {
                const type = item.dataset.type;
                const title = item.dataset.title;

                const matchFilter = currentFilter === 'all' || type === currentFilter || type === 'both';
                const matchSearch = title.includes(search);

                item.style.display = matchFilter && matchSearch ? 'flex' : 'none';
                if (matchFilter && matchSearch) visibleCount++;
            });

            const noResults = document.getElementById('noResults');
            if (noResults) {
                noResults.classList.toggle('hidden', visibleCount > 0);
            }
        }

        searchInput.addEventListener('input', filterActivities);

        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => b.classList.remove('bg-indigo-700', 'text-white'));
                filterButtons.forEach(b => b.classList.add('bg-gray-100', 'text-gray-700'));
                btn.classList.add('bg-indigo-700', 'text-white');
                btn.classList.remove('bg-gray-100', 'text-gray-700');

                currentFilter = btn.dataset.filter;
                filterActivities();
            });
        });
    </script>
@endpush
