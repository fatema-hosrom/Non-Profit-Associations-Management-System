@extends('templates.manager_app')

@section('title', 'إدارة المتطوعين بالفعالية')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-6">
            <!-- Success and Error Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
                    <i class="fas fa-check-circle ml-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center">
                    <i class="fas fa-exclamation-circle ml-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Title -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-user-plus ml-2 text-blue-600"></i>
                    إدارة المتطوعين بالفعالية
                </h1>
            </div>

            <!-- Page Description -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-blue-800">
                    <i class="fas fa-info-circle ml-2"></i>
                    هذه الصفحة تتيح لك إدارة المتطوعين في الفعاليات. يمكنك عرض الفعاليات التي تحتاج متطوعين،
                    إضافة متطوعين جدد، الموافقة على طلبات التطوع، رفضها، أو إزالة المتطوعين من الفعاليات.
                </p>
            </div>
            
            <!-- Search Field -->
            <div class="mb-6">
                <form method="GET" action="{{ route('manager.activity_volunteers.index') }}" class="flex gap-2">
                    <div class="flex-grow">
                        <input type="text" name="search" placeholder="ابحث عن فعالية..." value="{{ request('search') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-search ml-2"></i>
                        بحث
                    </button>
                    @if (request('search'))
                        <a href="{{ route('manager.activity_volunteers.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                            <i class="fas fa-times ml-2"></i>
                            مسح
                        </a>
                    @endif
                </form>
            </div>

            <!-- Activities List -->
            <div class="grid gap-6">
                @forelse($activities as $activity)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                        <!-- رأس البطاقة -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-bold text-white">
                                    {{ $activity->title }}
                                </h3>
                                <span class="bg-white bg-opacity-20  px-3 py-1 rounded-full text-sm">
                                    {{ $activity->manager->full_name ?? 'غير محدد' }}
                                </span>
                            </div>
                        </div>

                        <!-- محتوى البطاقة -->
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <!-- Activity Date -->
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt text-blue-600 ml-2"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">تاريخ الفعالية</p>
                                            <p class="font-semibold">
                                                {{ \Carbon\Carbon::parse($activity->start_date)->format('Y/m/d') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Activity Time -->
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-green-600 ml-2"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">الوقت</p>
                                            <p class="font-semibold">
                                                {{ $activity->start_date ? \Carbon\Carbon::parse($activity->start_date)->format('H:i') : 'غير محدد' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Required Volunteers Count -->
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-users text-purple-600 ml-2"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">المتطوعين المطلوب</p>
                                            <p class="font-semibold">
                                                {{ $activity->volunteerRequirements->required_volunteers ?? 0 }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Registered Volunteers Count -->
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-check text-orange-600 ml-2"></i>
                                        <div>
                                            <p class="text-sm text-gray-600">المتطوعين المسجلين</p>
                                            <p class="font-semibold">{{ $activity->assignments_count }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($activity->description)
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-800 mb-2">وصف الفعالية:</h4>
                                    <p class="text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $activity->description }}</p>
                                </div>
                            @endif

                            <!-- إحصائيات المتطوعين -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ $activity->assignments->where('status', 'pending')->count() }}</div>
                                    <div class="text-sm text-gray-600">في الانتظار</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ $activity->assignments->where('status', 'approved')->count() }}</div>
                                    <div class="text-sm text-gray-600">مُعتمد</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-red-600">
                                        {{ $activity->assignments->where('status', 'rejected')->count() }}</div>
                                    <div class="text-sm text-gray-600">مرفوض</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-600">
                                        {{ $activity->assignments->where('status', 'cancelled')->count() }}</div>
                                    <div class="text-sm text-gray-600">ملغي</div>
                                </div>
                            </div>

                            <!-- أزرار العمليات -->
                            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                                <a href="{{ route('manager.activity_volunteers.manage', $activity->id) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                                    <i class="fas fa-eye ml-2"></i>
                                    إدارة المتطوعين
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- No Activities State -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 text-center">
                        <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد فعاليات تحتاج متطوعين</h3>
                        <p class="text-gray-500">جميع الفعاليات الحالية مكتملة العدد أو لا تحتاج متطوعين إضافيين.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
