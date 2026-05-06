@extends('public.template_layouts.app')

@section('title', 'الفعاليات المنجزة - ساهم')

@section('content')
<section class="py-14 bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="max-w-7xl mx-auto px-4">

        <!-- العنوان -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <i class="fas fa-trophy text-2xl text-green-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">الفعاليات المنجزة</h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                اكتشف الإنجازات والنتائج الملهمة لفعالياتنا المنجزة
            </p>
        </div>

        <!-- قائمة الفعاليات المنجزة -->
        @if($completedActivities->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                @foreach($completedActivities as $activity)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group">

                        <!-- صورة الفعالية -->
                        <div class="relative h-48 overflow-hidden">
                            @if($activity->image)
                                <img src="{{ asset('assets/images/activities/' . $activity->image) }}"
                                     alt="{{ $activity->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                    <i class="fas fa-trophy text-4xl text-white"></i>
                                </div>
                            @endif

                            <!-- شارة الإنجاز -->
                            <div class="absolute top-4 right-4 {{ $activity->status === 'draft' ? 'bg-blue-500' : 'bg-green-500' }} text-white px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-{{ $activity->status === 'draft' ? 'eye' : 'check-circle' }} ml-1"></i>
                                {{ $activity->status === 'draft' ? 'معاينة' : 'منجزة' }}
                            </div>
                        </div>

                        <!-- محتوى البطاقة -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
                                {{ $activity->title }}
                            </h3>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $activity->description }}
                            </p>

                            <!-- معلومات التبرع إذا كان متاحاً -->
                            @if($activity->activity_type === 'donation' || $activity->activity_type === 'both')
                                @php
                                    $donation = $activity->donationSettings;
                                    $collected = $donation?->collected_amount ?? 0;
                                    $target = $donation?->target_amount ?? 0;
                                    $progress = $target > 0 ? ($collected / $target) * 100 : 0;
                                @endphp
                                @if($collected > 0)
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                        <div class="flex items-center text-sm text-green-700 mb-2">
                                            <i class="fas fa-dollar-sign text-green-600 ml-2 w-4"></i>
                                            <span>تم جمع: {{ number_format($collected, 2) }} $ من {{ number_format($target, 2) }} $</span>
                                        </div>
                                        <div class="w-full bg-green-200 h-2 rounded-full">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ min($progress, 100) }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <!-- إحصائيات النتائج للمنجزة -->
                            @if($activity->status === 'closed' && $activity->results)
                                <div class="space-y-2 mb-4">
                                    @if($activity->results->total_volunteers)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-users text-blue-500 ml-2 w-4"></i>
                                            <span>{{ $activity->results->total_volunteers }} متطوع</span>
                                        </div>
                                    @endif

                                    @if($activity->results->total_hours)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-clock text-green-500 ml-2 w-4"></i>
                                            <span>{{ $activity->results->total_hours }} ساعة</span>
                                        </div>
                                    @endif

                                    @if($activity->results->goals_achieved)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-target text-purple-500 ml-2 w-4"></i>
                                            <span class="line-clamp-1">{{ Str::limit($activity->results->goals_achieved, 30) }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- زر عرض التفاصيل -->
                            <a href="{{ route('public.completed-activities.show', $activity->id) }}"
                               class="inline-flex items-center justify-center w-full bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white py-3 px-6 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-eye ml-2"></i>
                                عرض النتائج
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $completedActivities->links() }}
            </div>

        @else
            <!-- حالة عدم وجود فعاليات -->
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                    <i class="fas fa-calendar-check text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">لا توجد فعاليات منجزة حاليًا</h3>
                <p class="text-gray-500 mb-6">سنضيف المزيد من الفعاليات المنجزة قريبًا</p>
                <a href="{{ route('public.home') }}"
                   class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-8 rounded-xl font-semibold transition-colors">
                    <i class="fas fa-home ml-2"></i>
                    العودة للرئيسية
                </a>
            </div>
        @endif

    </div>
</section>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/public/completed-activities.css') }}">
@endpush
