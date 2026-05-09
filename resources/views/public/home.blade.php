@extends('public.template_layouts.app')

@section('title', 'الرئيسية - ساهم')

@section('content')

{{-- placeholder --}}
    <!-- Sahem Statistics -->
    <section class="py-16 bg-gradient-to-r from-indigo-900 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <!-- Total Activities -->
            <div
                class="bg-indigo-800 rounded-xl p-8 shadow-lg transform hover:scale-105 transition flex flex-col items-center">
                <i class="fas fa-calendar-check fa-3x mb-3"></i>
                <h3 class="text-4xl font-bold">{{ $sahemActivities->count() }}</h3>
                <p class="text-lg mt-2 font-semibold">عدد فعاليات ساهم</p>
            </div>
            <!-- Total Donations -->
            <div
                class="bg-indigo-800 rounded-xl p-8 shadow-lg transform hover:scale-105 transition flex flex-col items-center">
                @php
                    $totalDonations = $sahemActivities->sum(fn($a) => $a->donationSettings?->collected_amount ?? 0);
                @endphp
                <i class="fas fa-hand-holding-dollar fa-3x mb-3"></i>
                <h3 class="text-4xl font-bold">{{ number_format($totalDonations, 2) }} $</h3>
                <p class="text-lg mt-2 font-semibold">إجمالي التبرعات</p>
            </div>
            <!-- Total Volunteers -->
            <div
                class="bg-indigo-800 rounded-xl p-8 shadow-lg transform hover:scale-105 transition flex flex-col items-center">
                @php
                    $totalVolunteers = $sahemActivities->sum(
                        fn($a) => $a->volunteerRequirements?->volunteers_count ?? 0,
                    );
                @endphp
                <i class="fas fa-users fa-3x mb-3"></i>
                <h3 class="text-4xl font-bold">{{ $totalVolunteers }}</h3>
                <p class="text-lg mt-2 font-semibold">عدد المتطوعين</p>
            </div>
        </div>
    </section>

    <!-- Donate Now Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 text-center mb-8">
            <h2 class="text-4xl font-bold text-indigo-900 mb-3">تبرع الآن</h2>
            <p class="text-lg text-gray-600">اختر الفعالية المناسبة للمساهمة بالبطاقة أو عرض تفاصيل الفعالية قبل التبرع.</p>
        </div>

        <div class="relative max-w-7xl mx-auto px-4">
            <!-- Navigation Arrows -->
            <button id="donation-prev"
                class="absolute top-1/2 -translate-y-1/2 left-0 z-10 bg-indigo-700 text-white rounded-full p-3 hover:bg-indigo-600 transition">
                <i class="fas fa-chevron-right"></i>
            </button>
            <button id="donation-next"
                class="absolute top-1/2 -translate-y-1/2 right-0 z-10 bg-indigo-700 text-white rounded-full p-3 hover:bg-indigo-600 transition">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div id="donation-carousel"
                class="flex rtl:space-x-reverse overflow-x-auto scrollbar-hide space-x-4 scroll-smooth">
                @foreach ($sahemActivities as $activity)
                    @if ($activity->activity_type == 'donation' || $activity->activity_type == 'both')
                        <a href="{{ route('public.activities.sahem.show', $activity->id) . '?donate=1' }}"
                            class="min-w-[220px] bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 flex-shrink-0 overflow-hidden">
                            <img src="{{ asset('assets/images/activities/' . $activity->image) }}"
                                alt="{{ $activity->title }}" class="h-40 w-full object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-bold mb-1">{{ $activity->title }}</h4>
                                <span class="text-sm text-gray-600 px-2 py-1 bg-gray-100 rounded">تبرع</span>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- Volunteer Activities -->
    <section class="py-16 bg-gray-100">
        <h2 class="text-4xl font-bold text-center mb-8 text-indigo-900">فعاليات التطوع</h2>

        <div class="relative max-w-7xl mx-auto px-4">
            <!-- Navigation Arrows -->
            <button id="volunteer-prev"
                class="absolute top-1/2 -translate-y-1/2 left-0 z-10 bg-indigo-700 text-white rounded-full p-3 hover:bg-indigo-600 transition">
                <i class="fas fa-chevron-right"></i>
            </button>
            <button id="volunteer-next"
                class="absolute top-1/2 -translate-y-1/2 right-0 z-10 bg-indigo-700 text-white rounded-full p-3 hover:bg-indigo-600 transition">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div id="volunteer-carousel"
                class="flex rtl:space-x-reverse overflow-x-auto scrollbar-hide space-x-4 scroll-smooth">
                @foreach ($sahemActivities as $activity)
                    @if ($activity->activity_type == 'volunteer' || $activity->activity_type == 'both')
                        <a href="{{ route('public.activities.sahem.show', $activity->id) }}"
                            class="min-w-[220px] bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:scale-105 flex-shrink-0 overflow-hidden">
                            <img src="{{ asset('assets/images/activities/' . $activity->image) }}"
                                alt="{{ $activity->title }}" class="h-40 w-full object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-bold mb-1">{{ $activity->title }}</h4>
                                <span class="text-sm text-gray-600 px-2 py-1 bg-gray-100 rounded">تطوع</span>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-10 bg-transparent">
        <div class="max-w-5xl mx-auto text-center mb-10">
            <h2 class="text-3xl font-extrabold mb-3 text-gray-800">
                نسهّل عليك المساهمة مع الجمعيات
            </h2>
            <p class="text-gray-600 text-lg">
                منصة واحدة تجمع عشرات الجمعيات وتفتح لك أبواب التبرع والتطوع بسهولة
            </p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid mb-12">
            <div>
                <h3 class="text-indigo-700">{{ $orgStats['organizations'] }}</h3>
                <p class="text-gray-600">جمعية نشطة</p>
            </div>
            <div>
                <h3 class="text-indigo-700">{{ $orgStats['events'] }}</h3>
                <p class="text-gray-600">فعالية للجمعيات</p>
            </div>
        </div>

        <!-- Circular Logo Strip -->
        <div class="logo-wheel-wrapper">
            <div class="logo-wheel" id="logoWheel">
                @foreach ($organizations as $org)
                    @if ($org->website_url)
                        <a href="{{ route('public.organizations.show', $org->id) }}" target="_blank"
                            class="logo-wheel-item">
                            <img src="{{ asset('assets/images/organizations/' . $org->logo) }}" alt="{{ $org->name }}">
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>




    <!-- Join Us Section -->
    <section class="py-20 bg-yellow-400 text-gray-900 text-center rounded-xl mx-4 md:mx-16 mt-20 shadow-lg">
        <h2 class="text-4xl font-bold mb-4">انضم وكن جزءًا من التغيير!</h2>
        <p class="text-lg mb-6">شارك معنا في فعاليات التطوع أو ساهم في التبرعات لتغيير حياة الناس</p>
        <a href="{{ route('public.volunteer.register') }}"
            class="bg-gray-900 text-white py-3 px-6 rounded-xl font-semibold hover:bg-gray-800 transition">
            سجل كمتطوع الآن
        </a>
    </section>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/public/home.css') }}">
    @endpush

    @push('scripts')
        <script>
            // Donation carousel
            const donationCarousel = document.getElementById('donation-carousel');
            document.getElementById('donation-prev').addEventListener('click', () => {
                donationCarousel.scrollBy({
                    left: -250,
                    behavior: 'smooth'
                });
            });
            document.getElementById('donation-next').addEventListener('click', () => {
                donationCarousel.scrollBy({
                    left: 250,
                    behavior: 'smooth'
                });
            });

            // Volunteer carousel
            const volunteerCarousel = document.getElementById('volunteer-carousel');
            document.getElementById('volunteer-prev').addEventListener('click', () => {
                volunteerCarousel.scrollBy({
                    left: -250,
                    behavior: 'smooth'
                });
            });
            document.getElementById('volunteer-next').addEventListener('click', () => {
                volunteerCarousel.scrollBy({
                    left: 250,
                    behavior: 'smooth'
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const wheel = document.getElementById('logoWheel');
                let offset = 0;
                const speed = 0.4; // movement speed
                let paused = false;

                function animate() {
                    if (!paused) {
                        offset -= speed;
                        wheel.style.transform = `translateX(${offset}px)`;

                        const firstItem = wheel.children[0];
                        if (firstItem) {
                            const itemWidth = firstItem.offsetWidth + 24;

                            // If item fully exits from the left
                            if (-offset >= itemWidth) {
                                wheel.appendChild(firstItem);
                                offset += itemWidth;
                            }
                        }
                    }
                    requestAnimationFrame(animate);
                }

                wheel.addEventListener('mouseenter', () => paused = true);
                wheel.addEventListener('mouseleave', () => paused = false);

                animate();
            });
        </script>
    @endpush

@endsection
