@extends('public.template_layouts.app')

@section('title', $activity->title . ' - ساهم')

@section('content')
    <section class="max-w-6xl mx-auto my-12 px-4">

        <!-- عنوان الفعالية -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-indigo-900 mb-2">{{ $activity->title }}</h1>
            <p class="text-gray-600 text-lg">{{ $activity->description }}</p>
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
                @if($activity->status !== 'draft')
                    <p><strong>تاريخ البداية:</strong> {{ $activity->start_date->format('Y-m-d H:i') }}</p>
                    <p><strong>تاريخ النهاية:</strong> {{ $activity->end_date->format('Y-m-d H:i') }}</p>
                @endif
            </div>

            <!-- التبرعات أو التطوع -->
            @if($activity->status !== 'draft')
            <div class="space-y-6">

                <!-- قسم التبرع -->
                @if ($activity->activity_type == 'donation' || $activity->activity_type == 'both')
                    @php
                        $donation = $activity->donationSettings;
                        $progress = $donation
                            ? ($donation->collected_amount / max($donation->target_amount, 1)) * 100
                            : 0;
                    @endphp
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-indigo-900 mb-4">التبرع</h2>
                        <p>المبلغ المستهدف: {{ number_format($donation?->target_amount ?? 0, 2) }} $</p>
                        <p>المبلغ المجموع: {{ number_format($donation?->collected_amount ?? 0, 2) }} $</p>

                        <div class="w-full bg-gray-200 h-6 rounded-full mt-3 overflow-hidden">
                            <div class="bg-green-500 h-6 rounded-full" style="width: {{ min($progress, 100) }}%"></div>
                        </div>

                        @if ($donation->collected_amount < $donation->target_amount)
                            <button id="openDonationModal"
                                class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold transition">
                                تبرع الآن
                            </button>
                        @else
                            <div class="mt-4 bg-gray-100 border border-green-200 text-green-700 rounded-xl p-4 text-center font-semibold">
                                تم جمع المبلغ المطلوب لهذه الفعالية. شكراً لكل من ساهم.
                            </div>
                        @endif
                    </div>
                @endif

                <!-- قسم التطوع -->
                @if ($activity->activity_type == 'volunteer' || $activity->activity_type == 'both')
                    @php
                        $volunteer = $activity->volunteerRequirements;
                    @endphp
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-indigo-900 mb-4">التطوع</h2>
                        <p>عدد المتطوعين المطلوبين: {{ $volunteer?->required_volunteers ?? 'غير محدد' }}</p>
                        <p>عدد المسجلين: {{ $volunteer?->volunteers_count ?? 0 }}</p>
                        <p>الحد الأدنى للعمر: {{ $volunteer?->min_age ?? '-' }}</p>
                        <p>الجنس:
                            {{ [
                                'male' => 'ذكر',
                                'female' => 'انثى',
                                'both' => 'كلا الجنسين',
                            ][$volunteer?->gender_requirement ?? 'both'] }}
                        </p>
                        <p>المهارات المطلوبة: {{ $volunteer?->skills_required ?? '-' }}</p>

                        @auth('volunteer')
                            @php
                                $existingAssignment = \App\Models\ActivityVolunteerAssignment::where('activity_id', $activity->id)
                                    ->where('volunteer_id', auth('volunteer')->id())
                                    ->first();
                            @endphp

                            @if($existingAssignment)
                                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 text-center font-semibold">
                                    <i class="fas fa-check-circle ml-2"></i>
                                    @if($existingAssignment->status == 'approved')
                                        أنت مسجل بالفعل في هذه الفعالية
                                    @elseif($existingAssignment->status == 'pending')
                                        طلبك قيد المراجعة حالياً
                                    @elseif($existingAssignment->status == 'rejected')
                                        تم الاعتذار عن طلبك لهذه الفعالية
                                    @endif
                                </div>
                                <a href="{{ route('volunteer.my-requests') }}" class="mt-2 block text-center text-sm text-blue-600 hover:underline">
                                    انتقل إلى طلباتي
                                </a>
                            @else
                                <form action="{{ route('volunteer.request-volunteer', $activity->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
                                        انضم إلينا كمتطوع الآن
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('volunteer.login') }}"
                                class="mt-4 block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold text-center transition">
                                سجل دخول للتطوع الآن
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
            @endif
        </div>

        <!-- معلومات إضافية للتبرع -->
        @if($activity->activity_type === 'donation' || $activity->activity_type === 'both')
            @php
                $donation = $activity->donationSettings;
                $collected = $donation?->collected_amount ?? 0;
                $target = $donation?->target_amount ?? 0;
                $progress = $target > 0 ? ($collected / $target) * 100 : 0;
            @endphp
            @if($collected > 0 || $activity->status === 'active')
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 mb-8">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <i class="fas fa-dollar-sign text-2xl text-green-600"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">التبرعات المجموعة</h2>
                        <p class="text-gray-600">المبلغ الذي تم جمعه حتى الآن</p>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-lg max-w-md mx-auto">
                        <div class="text-center mb-4">
                            <h3 class="text-3xl font-bold text-green-600 mb-2">{{ number_format($collected, 2) }} $</h3>
                            <p class="text-gray-600">من إجمالي {{ number_format($target, 2) }} $</p>
                        </div>
                        <div class="w-full bg-gray-200 h-4 rounded-full mb-2">
                            <div class="bg-green-500 h-4 rounded-full transition-all duration-500" style="width: {{ min($progress, 100) }}%"></div>
                        </div>
                        <p class="text-center text-sm text-gray-500">{{ number_format($progress, 1) }}% مكتمل</p>
                    </div>
                </div>
            @endif
        @endif

        <!-- نتائج الفعالية إذا كانت منجزة أو مسودة -->
        @if(($activity->status === 'closed' || $activity->status === 'draft') && $activity->results)
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-2xl p-8 mb-12">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                        <i class="fas fa-trophy text-2xl text-green-600"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">نتائج الفعالية</h2>
                    <p class="text-gray-600">إنجازات وإحصائيات الفعالية المنجزة</p>
                </div>

                <!-- إحصائيات النتائج -->
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

                <!-- التحديات -->
                @if($activity->results->challenges)
                    <div class="bg-white rounded-xl p-6 mb-6 shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-exclamation-triangle text-orange-600 ml-2"></i>
                            التحديات والدروس المستفادة
                        </h3>
                        <p class="text-gray-700 leading-relaxed">{{ $activity->results->challenges }}</p>
                    </div>
                @endif

                <!-- ملاحظات إضافية -->
                {{-- @if($activity->results->notes)
                    <div class="bg-white rounded-xl p-6 mb-6 shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-sticky-note text-blue-600 ml-2"></i>
                            ملاحظات إضافية
                        </h3>
                        <p class="text-gray-700 leading-relaxed">{{ $activity->results->notes }}</p>
                    </div>
                @endif --}}

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
                                        <div class="relative group">
                                            <img src="{{ trim($image) }}"
                                                 alt="صورة من الفعالية"
                                                 class="w-full h-48 object-cover rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300 cursor-pointer"
                                                 onclick="openModal('{{ trim($image) }}')"
                                                 onerror="this.src='/assets/images/placeholder.png'">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

                <!-- ملف التقرير -->
                {{-- @if($activity->results->report_file)
                    <div class="bg-white rounded-xl p-6 shadow-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-file-pdf text-red-600 ml-2"></i>
                            تقرير الفعالية
                        </h3>
                        <a href="{{ asset('assets/files/activity_reports/' . $activity->results->report_file) }}"
                           target="_blank"
                           class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white py-3 px-6 rounded-xl font-semibold transition-colors">
                            <i class="fas fa-download ml-2"></i>
                            تحميل التقرير
                        </a>
                    </div>
                @endif --}}
            </div>
        @endif

        <!-- أزرار العودة -->
        <div class="text-center mt-12">
            <a href="{{ route('public.activities.index') }}"
                class="inline-block bg-gray-900 hover:bg-gray-800 text-white py-3 px-6 rounded-xl font-semibold transition">
                العودة إلى جميع الفعاليات
            </a>
        </div>

    </section>

    @include('public.activities.partials.donation-modal')

    <!-- Modal لعرض الصور -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="relative max-w-4xl max-h-full">
            <img id="modalImage" src="" alt="صورة الفعالية" class="max-w-full max-h-full object-contain">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function openModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// المودال الخاص بالدفع
const donationModal = document.getElementById('donationModal');
const openDonationModalButton = document.getElementById('openDonationModal');
const closeDonationModalButton = document.getElementById('closeDonationModal');
const cancelDonationModalButton = document.getElementById('cancelDonationModal');

if (openDonationModalButton) {
    openDonationModalButton.addEventListener('click', () => {
        donationModal.classList.remove('hidden');
        donationModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    });
}

if (closeDonationModalButton) {
    closeDonationModalButton.addEventListener('click', closeDonationModal);
}

if (cancelDonationModalButton) {
    cancelDonationModalButton.addEventListener('click', closeDonationModal);
}

function closeDonationModal() {
    donationModal.classList.add('hidden');
    donationModal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function openDonationModalFromQuery() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('donate') === '1' && donationModal) {
        donationModal.classList.remove('hidden');
        donationModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

openDonationModalFromQuery();

// تنزيل الإيصال تلقائيا بعد نجاح الدفع
@if(session('receipt_payment_id'))
window.addEventListener('load', function () {
    const receiptUrl = "{{ route('public.activities.payment.receipt', session('receipt_payment_id')) }}";
    const link = document.createElement('a');
    link.href = receiptUrl;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
@endif

// إغلاق المودال عند النقر خارج النموذج
if (donationModal) {
    donationModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDonationModal();
        }
    });
}

// إغلاق أي مودال بالضغط على Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeDonationModal();
    }
});
</script>
@endpush
