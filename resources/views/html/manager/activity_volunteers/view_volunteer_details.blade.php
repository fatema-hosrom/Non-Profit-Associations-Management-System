@if ($assignment)
    <div class="space-y-5">
        <!-- رأس البطاقة - معلومات المتطوع الأساسية -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-2 border border-blue-200">
            <div class="flex items-start justify-between">
                <div class="flex-grow">
                    <h3 class="text-lg font-bold text-gray-900">{{ $assignment->volunteer->name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-envelope ml-1"></i>
                        {{ $assignment->volunteer->email }}
                    </p>
                    @if ($assignment->volunteer->phone)
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="fas fa-phone ml-1"></i>
                            {{ $assignment->volunteer->phone }}
                        </p>
                    @endif
                </div>
                <div class="text-left">
                    @php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'cancelled' => 'bg-gray-100 text-gray-800',
                        ];
                        $statusText = [
                            'pending' => 'في الانتظار',
                            'approved' => 'مُعتمد',
                            'rejected' => 'مرفوض',
                            'cancelled' => 'ملغي',
                        ];
                    @endphp
                    <span
                        class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full {{ $statusClasses[$assignment->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusText[$assignment->status] ?? $assignment->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- معلومات التطوع -->
        <div class="bg-white rounded-lg p-2 border border-gray-200">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clipboard-list text-blue-600 ml-2"></i>
                معلومات التطوع
            </h4>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-lg p-3">
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">تاريخ الطلب</label>
                    <p class="text-gray-900 font-semibold">
                        {{ $assignment->request_date ? \Carbon\Carbon::parse($assignment->request_date)->format('Y/m/d') : 'غير محدد' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $assignment->request_date ? \Carbon\Carbon::parse($assignment->request_date)->format('H:i') : '' }}
                    </p>
                </div>

                @if ($assignment->decision_date)
                    <div class="bg-gray-50 rounded-lg p-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">تاريخ القرار</label>
                        <p class="text-gray-900 font-semibold">
                            {{ \Carbon\Carbon::parse($assignment->decision_date)->format('Y/m/d') }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($assignment->decision_date)->format('H:i') }}
                        </p>
                    </div>
                @endif

                @if ($assignment->joined_at)
                    <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                        <label class="block text-xs font-semibold text-green-600 uppercase mb-1">تاريخ الانضمام</label>
                        <p class="text-gray-900 font-semibold">
                            {{ \Carbon\Carbon::parse($assignment->joined_at)->format('Y/m/d') }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($assignment->joined_at)->format('H:i') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- أسباب الرفض أو الإزالة -->
        @if ($assignment->rejection_reason || $assignment->removal_reason)
            <div class="bg-white rounded-lg p-2 border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-comment-times text-red-600 ml-2"></i>
                    الملاحظات والأسباب
                </h4>
                <div class="space-y-3">
                    @if ($assignment->rejection_reason)
                        <div class="bg-red-50 border-r-4 border-red-500 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-red-700 mb-2">سبب الرفض</label>
                            <p class="text-gray-900">{{ $assignment->rejection_reason }}</p>
                        </div>
                    @endif
                    @if ($assignment->removal_reason)
                        <div class="bg-orange-50 border-r-4 border-orange-500 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-orange-700 mb-2">سبب الإزالة</label>
                            <p class="text-gray-900">{{ $assignment->removal_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- معلومات إضافية عن المتطوع -->
        @if (
            $assignment->volunteer->age ||
                $assignment->volunteer->gender ||
                $assignment->volunteer->address ||
                $assignment->volunteer->nationality)
            <div class="bg-white rounded-lg p-2 border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-circle text-purple-600 ml-2"></i>
                    معلومات إضافية
                </h4>
                <div class="grid md:grid-cols-3 gap-4">
                    @if ($assignment->volunteer->age)
                        <div class="bg-gray-50 rounded-lg p-3">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">العمر</label>
                            <p class="text-gray-900 font-semibold text-lg">{{ $assignment->volunteer->age }}</p>
                        </div>
                    @endif
                    @if ($assignment->volunteer->gender)
                        <div class="bg-gray-50 rounded-lg p-3">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">الجنس</label>
                            <p class="text-gray-900 font-semibold">
                                @if ($assignment->volunteer->gender === 'male')
                                    <i class="fas fa-mars text-blue-600 ml-1"></i>ذكر
                                @elseif ($assignment->volunteer->gender === 'female')
                                    <i class="fas fa-venus text-pink-600 ml-1"></i>أنثى
                                @else
                                    {{ $assignment->volunteer->gender }}
                                @endif
                            </p>
                        </div>
                    @endif
                    @if ($assignment->volunteer->nationality)
                        <div class="bg-gray-50 rounded-lg p-3">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">الجنسية</label>
                            <p class="text-gray-900 font-semibold">{{ $assignment->volunteer->nationality }}</p>
                        </div>
                    @endif
                    @if ($assignment->volunteer->address)
                        <div class="col-span-2 bg-gray-50 rounded-lg p-3">
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">العنوان</label>
                            <p class="text-gray-900 flex items-start">
                                <i class="fas fa-map-marker-alt text-red-600 ml-2 mt-1"></i>
                                {{ $assignment->volunteer->address }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@else
    <div class="text-center text-gray-500">
        <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
        <p>لم يتم العثور على بيانات المتطوع</p>
    </div>
@endif
