@extends('templates.manager_app')

@section('title', 'تفاصيل المتطوع')

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <!-- Header -->
                    <div class="mb-8 flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $volunteer->name }}</h1>
                            <p class="text-gray-500 mt-2">معلومات المتطوع الكاملة</p>
                        </div>
                        <a href="{{ route('manager.volunteers.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                            <i class="fas fa-arrow-right ml-2"></i>
                            العودة
                        </a>
                    </div>

                    <!-- Messages -->
                    @if (session('success'))
                        <div
                            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                            <i class="fas fa-check-circle ml-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                            <i class="fas fa-exclamation-circle ml-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Profile Header Card -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-8 mb-8 text-white">
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-4xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold mb-2">{{ $volunteer->name }}</h2>
                                <p class="text-blue-100">
                                    <i class="fas fa-envelope ml-2"></i>
                                    {{ $volunteer->email }}
                                </p>
                                <p class="text-blue-100 mt-1">
                                    <i class="fas fa-phone ml-2"></i>
                                    {{ $volunteer->phone }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 mb-8" dir="rtl">
                        <a href="{{ route('manager.volunteers.edit', $volunteer) }}"
                            class="inline-flex items-center px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-semibold">
                            <i class="fas fa-edit ml-2"></i>
                            تعديل البيانات
                        </a>
                        <button type="button" onclick="confirmDelete({{ $volunteer->id }}, '{{ $volunteer->name }}')"
                            class="inline-flex items-center px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                            <i class="fas fa-trash ml-2"></i>
                            حذف المتطوع
                        </button>
                    </div>

                    <!-- Main Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- معلومات الاتصال -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-address-card ml-2 text-blue-600"></i>
                                معلومات الاتصال
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">البريد الإلكتروني</p>
                                    <p class="font-semibold text-gray-900">{{ $volunteer->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">رقم الهاتف</p>
                                    <p class="font-semibold text-gray-900">{{ $volunteer->phone }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">العنوان</p>
                                    <p class="font-semibold text-gray-900">{{ $volunteer->address }}</p>
                                </div>
                                @if ($volunteer->emergency_contact)
                                    <div>
                                        <p class="text-sm text-gray-600">رقم الطوارئ</p>
                                        <p class="font-semibold text-gray-900">{{ $volunteer->emergency_contact }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- المعلومات الشخصية -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-user-circle ml-2 text-green-600"></i>
                                المعلومات الشخصية
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">العمر</p>
                                    <p class="font-semibold text-gray-900">{{ $volunteer->age }} سنة</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">الجنس</p>
                                    <p class="font-semibold text-gray-900">
                                        @if ($volunteer->gender === 'male')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-mars ml-2"></i>
                                                ذكر
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-pink-100 text-pink-800">
                                                <i class="fas fa-venus ml-2"></i>
                                                أنثى
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">الجنسية</p>
                                    <p class="font-semibold text-gray-900">{{ $volunteer->nationality }}</p>
                                </div>
                                @if ($volunteer->education_level)
                                    <div>
                                        <p class="text-sm text-gray-600">المستوى التعليمي</p>
                                        <p class="font-semibold text-gray-900">
                                            @switch($volunteer->education_level)
                                                @case('high_school')
                                                    ثانوي
                                                @break

                                                @case('bachelor')
                                                    بكالوريوس
                                                @break

                                                @case('master')
                                                    ماجستير
                                                @break

                                                @case('phd')
                                                    دكتوراه
                                                @break

                                                @default
                                                    {{ $volunteer->education_level }}
                                            @endswitch
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Skills and Experience -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        @if ($volunteer->skills)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-star ml-2 text-yellow-500"></i>
                                    المهارات
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach (explode(',', $volunteer->skills) as $skill)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full font-medium">
                                            {{ trim($skill) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($volunteer->experience)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-briefcase ml-2 text-orange-500"></i>
                                    الخبرة
                                </h3>
                                <p class="text-gray-700 leading-relaxed">{{ $volunteer->experience }}</p>
                            </div>
                        @endif
                    </div>

                    @if ($volunteer->preferred_roles)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-tasks ml-2 text-purple-600"></i>
                                الأدوار المفضلة
                            </h3>
                            <p class="text-gray-700">{{ $volunteer->preferred_roles }}</p>
                        </div>
                    @endif

                    <!-- Availability and Languages -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        @if ($volunteer->availability)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-calendar-check ml-2 text-green-600"></i>
                                    التوفر الزمني
                                </h3>
                                <p class="font-semibold text-gray-900">
                                    @switch($volunteer->availability)
                                        @case('full_time')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">دوام
                                                كامل</span>
                                        @break

                                        @case('part_time')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">دوام
                                                جزئي</span>
                                        @break

                                        @case('weekends')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">عطلات
                                                نهاية الأسبوع</span>
                                        @break

                                        @case('flexible')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">مرن</span>
                                        @break
                                    @endswitch
                                </p>
                            </div>
                        @endif

                        @if ($volunteer->languages)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-globe ml-2 text-blue-600"></i>
                                    اللغات المحكمة
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach (explode(',', $volunteer->languages) as $language)
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full font-medium">
                                            {{ trim($language) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- System Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-cog ml-2 text-gray-600"></i>
                            معلومات النظام
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <p class="text-sm text-gray-600">تاريخ الإضافة</p>
                                <p class="font-semibold text-gray-900">{{ $volunteer->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">آخر تحديث</p>
                                <p class="font-semibold text-gray-900">{{ $volunteer->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">معرّف المتطوع</p>
                                <p class="font-semibold text-gray-900">#{{ $volunteer->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="fas fa-exclamation text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">تأكيد الحذف</h3>
                <p class="text-gray-600 text-center mb-4">هل أنت متأكد من حذف المتطوع <span id="volunteerName"
                        class="font-semibold"></span>؟</p>
                <p class="text-red-600 text-sm text-center mb-6">لا يمكن التراجع عن هذا الإجراء</p>
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                        إلغاء
                    </button>
                    <form id="deleteForm" method="POST" style="flex: 1;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                            حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                function confirmDelete(id, name) {
                    document.getElementById('volunteerName').textContent = name;
                    document.getElementById('deleteForm').action = `/manager/volunteers/${id}`;
                    document.getElementById('deleteModal').classList.remove('hidden');
                }

                function closeDeleteModal() {
                    document.getElementById('deleteModal').classList.add('hidden');
                }

                // إغلاق الـ Modal عند النقر خارجه
                document.getElementById('deleteModal')?.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeDeleteModal();
                    }
                });
            </script>
        @endpush
    @endsection
