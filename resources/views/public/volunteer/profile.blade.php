@extends('public.template_layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
    <div class="max-w-5xl mx-auto my-8 px-4 sm:px-6 lg:px-8">
        <!-- Success Alert -->
        @if (session('success'))
            <div class="mb-6 flex items-center p-4 text-green-800 border-l-4 border-green-500 bg-green-50 rounded shadow-sm" role="alert">
                <i class="fas fa-check-circle text-xl mr-3 rtl:ml-3 rtl:mr-0"></i>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="mb-6 p-4 text-red-800 border-l-4 border-red-500 bg-red-50 rounded shadow-sm" role="alert">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle text-xl mr-3 rtl:ml-3 rtl:mr-0"></i>
                    <span class="font-bold">يرجى تصحيح الأخطاء التالية:</span>
                </div>
                <ul class="list-disc list-inside text-sm mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Hero Header -->
        <div class="bg-gradient-to-l from-[#667eea] to-[#764ba2] rounded-2xl p-8 mb-8 text-white shadow-xl relative overflow-hidden">
            <!-- Decorative Circles -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-white opacity-10 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
                <!-- Avatar Placeholder -->
                <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border-2 border-white/30 shadow-inner">
                    <i class="fas fa-user text-4xl text-white"></i>
                </div>
                <div class="text-center md:text-right flex-1">
                    <h1 class="text-3xl font-bold mb-2">{{ $volunteer->name }}</h1>
                    <p class="text-blue-100 flex items-center justify-center md:justify-start gap-2">
                        <i class="fas fa-envelope"></i> {{ $volunteer->email }}
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('volunteer.profile.update') }}" class="space-y-8">
            @method('PUT')
            @csrf

            <!-- Basic Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 transition-shadow hover:shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-b pb-4">
                    <i class="fas fa-id-card text-[#667eea]"></i> المعلومات الأساسية
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea] focus:ring-opacity-20 transition-all outline-none" value="{{ $volunteer->name }}" required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني <span class="text-red-500">*</span></label>
                        <input type="email" id="email" class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed outline-none" value="{{ $volunteer->email }}" disabled>
                        <p class="mt-1 text-xs text-gray-400">لا يمكن تغيير البريد الإلكتروني</p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">رقم الهاتف <span class="text-red-500">*</span></label>
                        <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea] focus:ring-opacity-20 transition-all outline-none" value="{{ $volunteer->phone }}" required>
                    </div>

                    <div>
                        <label for="age" class="block text-sm font-semibold text-gray-700 mb-2">العمر <span class="text-red-500">*</span></label>
                        <input type="number" id="age" name="age" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea] focus:ring-opacity-20 transition-all outline-none" value="{{ $volunteer->age }}" min="16" required>
                    </div>
                </div>
            </div>

            <!-- Comprehensive Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 transition-shadow hover:shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-b pb-4">
                    <i class="fas fa-info-circle text-[#667eea]"></i> معلومات شاملة
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">الجنس <span class="text-red-500">*</span></label>
                        <select id="gender" name="gender" class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed outline-none" disabled>
                            <option>{{ $volunteer->gender }}</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-400">لا يمكن تغيير الجنس</p>
                    </div>

                    <div>
                        <label for="nationality" class="block text-sm font-semibold text-gray-700 mb-2">الجنسية <span class="text-red-500">*</span></label>
                        <input type="text" id="nationality" name="nationality" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea] focus:ring-opacity-20 transition-all outline-none" value="{{ $volunteer->nationality }}" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">العنوان <span class="text-red-500">*</span></label>
                    <textarea id="address" name="address" rows="2" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea] focus:ring-opacity-20 transition-all outline-none resize-y" required>{{ $volunteer->address }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="education_level" class="block text-sm font-semibold text-gray-700 mb-2">المستوى التعليمي</label>
                        <input type="text" id="education_level" name="education_level" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea] focus:ring-opacity-20 transition-all outline-none" value="{{ $volunteer->education_level }}">
                    </div>

                    <div>
                        <label for="availability" class="block text-sm font-semibold text-gray-700 mb-2">التوفر</label>
                        <input type="text" id="availability" name="availability" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea] focus:ring-opacity-20 transition-all outline-none" value="{{ $volunteer->availability }}" placeholder="مثال: أيام الأسبوع، نهاية الأسبوع">
                    </div>
                </div>
            </div>

            <!-- Skills & Experience Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 transition-shadow hover:shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-b pb-4">
                    <i class="fas fa-laptop-code text-[#667eea]"></i> المهارات والخبرة
                </h2>

                <div class="space-y-6">
                    <div>
                        <label for="skills" class="block text-sm font-semibold text-gray-700 mb-2">المهارات</label>
                        <textarea id="skills" name="skills" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea] focus:ring-opacity-20 transition-all outline-none resize-y" placeholder="اذكر مهاراتك (إن وجدت)">{{ $volunteer->skills }}</textarea>
                    </div>

                    <div>
                        <label for="experience" class="block text-sm font-semibold text-gray-700 mb-2">الخبرة السابقة</label>
                        <textarea id="experience" name="experience" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea] focus:ring-opacity-20 transition-all outline-none resize-y" placeholder="اذكر خبراتك السابقة (إن وجدت)">{{ $volunteer->experience }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="preferred_roles" class="block text-sm font-semibold text-gray-700 mb-2">الأدوار المفضلة</label>
                            <input type="text" id="preferred_roles" name="preferred_roles" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea] focus:ring-opacity-20 transition-all outline-none" value="{{ $volunteer->preferred_roles }}" placeholder="مثال: تربوي، إداري">
                        </div>

                        <div>
                            <label for="languages" class="block text-sm font-semibold text-gray-700 mb-2">اللغات</label>
                            <input type="text" id="languages" name="languages" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea] focus:ring-opacity-20 transition-all outline-none" value="{{ $volunteer->languages }}" placeholder="مثال: العربية، الإنجليزية">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 transition-shadow hover:shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-b pb-4">
                    <i class="fas fa-medkit text-red-500"></i> معلومات الطوارئ
                </h2>

                <div>
                    <label for="emergency_contact" class="block text-sm font-semibold text-gray-700 mb-2">جهة اتصال الطوارئ</label>
                    <textarea id="emergency_contact" name="emergency_contact" rows="2" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-red-400 focus:ring-2 focus:ring-red-400 focus:ring-opacity-20 transition-all outline-none resize-y" placeholder="الاسم ورقم الهاتف">{{ $volunteer->emergency_contact }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-4">
                <a href="{{ route('volunteer.dashboard') }}" class="w-full sm:w-auto px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-colors text-center order-2 sm:order-1">
                    إلغاء
                </a>
                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-[#667eea] to-[#764ba2] hover:opacity-90 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all order-1 sm:order-2 flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
@endsection
