@extends('templates.manager_app')

@section('title', 'إضافة متطوع جديد')

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <!-- Header -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">إضافة متطوع جديد</h1>
                        <p class="text-gray-500 mt-2">ملء جميع البيانات المطلوبة لإضافة متطوع جديد</p>
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

                    <form method="POST" action="{{ route('manager.volunteers.store') }}" class="space-y-6">
                        @csrf

                        <!-- معلومات أساسية -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-user ml-2 text-blue-600"></i>
                                المعلومات الأساسية
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- الاسم الكامل -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        الاسم الكامل <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="أدخل الاسم الكامل" required>
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- البريد الإلكتروني -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        البريد الإلكتروني <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="example@email.com" required>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- كلمة المرور -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        كلمة المرور <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="على الأقل 8 أحرف" required>
                                    @error('password')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تأكيد كلمة المرور -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        تأكيد كلمة المرور <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="تأكيد كلمة المرور" required>
                                    @error('password_confirmation')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- رقم الهاتف -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        رقم الهاتف <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="966501234567" required>
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- الجنس -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        الجنس <span class="text-red-500">*</span>
                                    </label>
                                    <select name="gender"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        required>
                                        <option value="">اختر الجنس</option>
                                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>ذكر
                                        </option>
                                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>أنثى
                                        </option>
                                    </select>
                                    @error('gender')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- العمر -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        العمر <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="age" value="{{ old('age') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="25" min="16" required>
                                    @error('age')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- الجنسية -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        الجنسية <span class="text-red-500">*</span>
                                    </label>
                                    <select name="nationality"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        required>
                                        <option value="">اختر الجنسية</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country['name_ar'] }}"
                                                {{ old('nationality') === $country['name_ar'] ? 'selected' : '' }}>
                                                {{ $country['name_ar'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('nationality')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- المستوى التعليمي -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        المستوى التعليمي
                                    </label>
                                    <select name="education_level"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                                        <option value="">اختر المستوى</option>
                                        <option value="high_school"
                                            {{ old('education_level') === 'high_school' ? 'selected' : '' }}>ثانوي</option>
                                        <option value="bachelor"
                                            {{ old('education_level') === 'bachelor' ? 'selected' : '' }}>
                                            بكالوريوس</option>
                                        <option value="master" {{ old('education_level') === 'master' ? 'selected' : '' }}>
                                            ماجستير
                                        </option>
                                        <option value="phd" {{ old('education_level') === 'phd' ? 'selected' : '' }}>
                                            دكتوراه
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- العنوان -->
                            <div class="mt-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    العنوان <span class="text-red-500">*</span>
                                </label>
                                <textarea name="address" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                    placeholder="أدخل العنوان الكامل" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- المهارات والخبرات -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-tools ml-2 text-green-600"></i>
                                المهارات والخبرات
                            </h2>

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">المهارات</label>
                                    <textarea name="skills" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="اكتب المهارات مفصولة بفواصل">{{ old('skills') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">الخبرة</label>
                                    <textarea name="experience" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="اكتب الخبرات السابقة">{{ old('experience') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">الأدوار المفضلة</label>
                                    <input type="text" name="preferred_roles" value="{{ old('preferred_roles') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="مثال: تدريب، تنظيم، إغاثة">
                                </div>
                            </div>
                        </div>

                        <!-- التوفر واللغات -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-clock ml-2 text-purple-600"></i>
                                التوفر واللغات
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">التوفر الزمني</label>
                                    <select name="availability"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                                        <option value="">اختر التوفر</option>
                                        <option value="full_time"
                                            {{ old('availability') === 'full_time' ? 'selected' : '' }}>دوام
                                            كامل</option>
                                        <option value="part_time"
                                            {{ old('availability') === 'part_time' ? 'selected' : '' }}>دوام
                                            جزئي</option>
                                        <option value="weekends"
                                            {{ old('availability') === 'weekends' ? 'selected' : '' }}>عطلات
                                            نهاية الأسبوع</option>
                                        <option value="flexible"
                                            {{ old('availability') === 'flexible' ? 'selected' : '' }}>مرن
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الطوارئ</label>
                                    <input type="tel" name="emergency_contact"
                                        value="{{ old('emergency_contact') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="966501234567">
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">اللغات المحكمة</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach ($languages as $language)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" name="languages[]" value="{{ $language['name_ar'] }}"
                                                {{ in_array($language['name_ar'], old('languages', [])) ? 'checked' : '' }}
                                                class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">{{ $language['name_ar'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4">
                            <a href="{{ route('manager.volunteers.index') }}"
                                class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition text-center flex items-center justify-center">
                                <i class="fas fa-arrow-left ml-2"></i>
                                العودة
                            </a>
                            <button type="submit"
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:shadow-lg transition flex items-center justify-center">
                                <i class="fas fa-save ml-2"></i>
                                حفظ المتطوع
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endsection
