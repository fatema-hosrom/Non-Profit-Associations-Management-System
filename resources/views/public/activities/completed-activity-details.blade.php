@extends('public.template_layouts.app')

@section('title', $activity->title . ' - النتائج - ساهم')

@section('content')
    <section class="max-w-6xl mx-auto my-12 px-4">

        <!-- عنوان الفعالية -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-indigo-900 mb-2">{{ $activity->title }}</h1>
            <p class="text-gray-600 text-lg">{{ $activity->description }}</p>
            <div class="inline-flex items-center bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold mt-4">
                <i class="fas fa-check-circle ml-2"></i>
                فعالية منجزة
            </div>
        </div>

        <!-- صورة الفعالية -->
        <div class="mb-8 overflow-hidden rounded-xl shadow-lg">
            <img src="{{ asset('assets/images/activities/' . ($activity->image ?? 'default-event.png')) }}"
                alt="{{ $activity->title }}"
                class="w-full h-96 object-cover transform hover:scale-105 transition duration-500">
        </div>

        <!-- تفاصيل الفعالية -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">

            <!-- معلومات الفعالية -->
            <div class="bg-white rounded-xl shadow-lg p-6 space-y-4">
                <h2 class="text-2xl font-bold text-indigo-900 mb-4">تفاصيل الفعالية</h2>
                <p><strong>نوع الفعالية:</strong>
                    @if ($activity->activity_type == 'donation')
                        تبرع
                    @elseif($activity->activity_type == 'volunteer')
                        تطوع
                    @else
                        كلاهما
                    @endif
                </p>
                <p><strong>الموقع:</strong> {{ $activity->location ?? 'غير محدد' }}</p>
                <p><strong>تاريخ البداية:</strong> {{ $activity->start_date->format('Y-m-d H:i') }}</p>
                <p><strong>تاريخ النهاية:</strong> {{ $activity->end_date->format('Y-m-d H:i') }}</p>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-indigo-900 mb-4">إحصائيات النجاح</h2>
                <div class="grid grid-cols-2 gap-4">
                    @if($activity->results && $activity->results->total_volunteers)
                        <div class="bg-white rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-blue-600">{{ $activity->results->total_volunteers }}</div>
                            <div class="text-sm text-gray-600">متطوع</div>
                        </div>
                    @endif

                    @if($activity->results && $activity->results->total_hours)
                        <div class="bg-white rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-green-600">{{ $activity->results->total_hours }}</div>
                            <div class="text-sm text-gray-600">ساعة</div>
                        </div>
                    @endif

                    @if($activity->results && $activity->results->attendance_count)
                        <div class="bg-white rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-purple-600">{{ $activity->results->attendance_count }}</div>
                            <div class="text-sm text-gray-600">حضور</div>
                        </div>
                    @endif

                    @if($activity->activity_type === 'donation' || $activity->activity_type === 'both')
                        @php
                            $donation = $activity->donationSettings;
                            $collected = $donation?->collected_amount ?? 0;
                        @endphp
                        <div class="bg-white rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-yellow-600">{{ number_format($collected, 0) }}</div>
                            <div class="text-sm text-gray-600">$</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- نتائج الفعالية -->
        @if($activity->results)
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-2xl p-8 mb-12">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                        <i class="fas fa-trophy text-2xl text-green-600"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">نتائج الفعالية</h2>
                    <p class="text-gray-600">إنجازات وإحصائيات الفعالية المنجزة</p>
                </div>

                <!-- إحصائيات النتائج التفصيلية -->
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    @if($activity->results->total_volunteers)
                        <div class="bg-white rounded-xl p-6 text-center shadow-lg">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $activity->results->total_volunteers }}</h3>
                            <p class="text-gray-600">عدد المتطوعين</p>
                        </div>
                    @endif

                    @if($activity->results->total_hours)
                        <div class="bg-white rounded-xl p-6 text-center shadow-lg">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                                <i class="fas fa-clock text-green-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $activity->results->total_hours }}</h3>
                            <p class="text-gray-600">إجمالي الساعات</p>
                        </div>
                    @endif

                    @if($activity->results->attendance_count)
                        <div class="bg-white rounded-xl p-6 text-center shadow-lg">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full mb-3">
                                <i class="fas fa-calendar-check text-purple-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $activity->results->attendance_count }}</h3>
                            <p class="text-gray-600">عدد الحضور</p>
                        </div>
                    @endif
                </div>

                <!-- الأهداف المحققة -->
                @if($activity->results->goals_achieved)
                    <div class="bg-white rounded-xl p-6 mb-6 shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-target text-green-600 ml-2"></i>
                            الأهداف المحققة
                        </h3>
                        <p class="text-gray-700 leading-relaxed">{{ $activity->results->goals_achieved }}</p>
                    </div>
                @endif

                <!-- التحديات والدروس المستفادة -->
                @if($activity->results->challenges)
                    <div class="bg-white rounded-xl p-6 mb-6 shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-exclamation-triangle text-orange-600 ml-2"></i>
                            التحديات والدروس المستفادة
                        </h3>
                        <p class="text-gray-700 leading-relaxed">{{ $activity->results->challenges }}</p>
                    </div>
                @endif

                <!-- الصور والفيديوهات -->
                @if($activity->results->images)
                    @php
                        $images = array_filter(explode("\n", $activity->results->images));
                    @endphp
                    @if($images && count($images) > 0)
                        <div class="bg-white rounded-xl p-6 shadow-lg">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-images text-indigo-600 ml-2"></i>
                                صور الفعالية
                            </h3>
                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($images as $image)
                                    @if(trim($image))
                                        @php
                                            $imagePath = trim($image);
                                            $resolvedImage = \Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '/'])
                                                ? $imagePath
                                                : asset('assets/images/activity_results/' . $imagePath);
                                        @endphp
                                        <div class="relative group">
                                            <img src="{{ $resolvedImage }}"
                                                 alt="صورة من الفعالية"
                                                 class="w-full h-48 object-cover rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300 cursor-pointer"
                                                 onclick="openModal('{{ $resolvedImage }}')"
                                                 onerror="this.src='/assets/images/placeholder.png'">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @endif

        <!-- معلومات التبرع إذا كان متاحاً -->
        @if($activity->activity_type === 'donation' || $activity->activity_type === 'both')
            @php
                $donation = $activity->donationSettings;
                $collected = $donation?->collected_amount ?? 0;
                $target = $donation?->target_amount ?? 0;
                $progress = $target > 0 ? ($collected / $target) * 100 : 0;
            @endphp
            @if($collected > 0)
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-8 mb-8">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                            <i class="fas fa-dollar-sign text-2xl text-yellow-600"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">إنجازات التبرع</h2>
                        <p class="text-gray-600">المبلغ الذي تم جمعه خلال الفعالية</p>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-lg max-w-md mx-auto">
                        <div class="text-center mb-4">
                            <h3 class="text-3xl font-bold text-yellow-600 mb-2">{{ number_format($collected, 2) }} $</h3>
                            <p class="text-gray-600">من إجمالي {{ number_format($target, 2) }} $</p>
                        </div>
                        <div class="w-full bg-gray-200 h-4 rounded-full mb-2">
                            <div class="bg-yellow-500 h-4 rounded-full transition-all duration-500" style="width: {{ min($progress, 100) }}%"></div>
                        </div>
                        <p class="text-center text-sm text-gray-500">{{ number_format($progress, 1) }}% مكتمل</p>
                    </div>
                </div>
            @endif
        @endif

        <!-- زر العودة -->
        <div class="text-center">
            <a href="{{ route('public.completed-activities') }}"
               class="inline-flex items-center bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white py-3 px-8 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-arrow-left ml-2"></i>
                العودة للفعاليات المنجزة
            </a>
        </div>
    </section>

    <!-- Modal للصور -->
    @if($activity->results && $activity->results->images)
        @php
            $images = array_filter(explode("\n", $activity->results->images));
        @endphp
        @if($images && count($images) > 0)
            <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4">
                <div class="relative max-w-4xl max-h-full">
                    <img id="modalImage" src="" alt="صورة من الفعالية" class="max-w-full max-h-full object-contain">
                    <button onclick="closeModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
    @endif

    @push('scripts')
        <script>
            function openModal(imageSrc) {
                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('imageModal').classList.remove('hidden');
                document.getElementById('imageModal').classList.add('flex');
            }

            function closeModal() {
                document.getElementById('imageModal').classList.add('hidden');
                document.getElementById('imageModal').classList.remove('flex');
            }

            // إغلاق الـ Modal عند النقر خارجه
            document.getElementById('imageModal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        </script>
    @endpush
@endsection
