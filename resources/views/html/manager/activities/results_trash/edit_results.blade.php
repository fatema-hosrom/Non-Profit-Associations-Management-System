@extends('templates.manager_app')

@section('title', 'تعديل نتائج الفعالية')

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <!-- Header -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">تعديل نتائج الفعالية</h1>
                        <p class="text-gray-500 mt-2">تعديل نتائج فعالية: <span
                                class="font-semibold text-gray-700">{{ $activity->title }}</span></p>
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

                    <form method="POST" action="{{ route('manager.activities.results.update', $activity->id) }}"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- معلومات التطوع -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-users ml-2 text-blue-600"></i>
                                معلومات التطوع
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- عدد المتطوعين -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        عدد المتطوعين المشاركين فعليًا
                                        <span class="text-gray-400 text-xs">(اتركه فارغًا إذا لم يكن محدد)</span>
                                    </label>
                                    <input type="number" name="total_volunteers"
                                        value="{{ old('total_volunteers', $results->total_volunteers) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="مثال: 25" min="0">
                                    @error('total_volunteers')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- مجموع الساعات -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        مجموع ساعات العمل التطوعي
                                        <span class="text-gray-400 text-xs">(اتركه فارغًا إذا لم يكن محدد)</span>
                                    </label>
                                    <input type="number" name="total_hours"
                                        value="{{ old('total_hours', $results->total_hours) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="مثال: 150" min="0">
                                    @error('total_hours')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- عدد الحضور -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        عدد الحضور
                                        <span class="text-gray-400 text-xs">(اتركه فارغًا إذا لم يكن محدد)</span>
                                    </label>
                                    <input type="number" name="attendance_count"
                                        value="{{ old('attendance_count', $results->attendance_count) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        placeholder="مثال: 100" min="0">
                                    @error('attendance_count')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- الأهداف والتحديات -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-bullseye ml-2 text-green-600"></i>
                                الأهداف والتحديات
                            </h2>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- الأهداف المحققة -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        الأهداف التي تم تحقيقها
                                        <span class="text-gray-400 text-xs">(اتركه فارغًا إذا لم يكن محدد)</span>
                                    </label>
                                    <textarea name="goals_achieved" rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-vertical"
                                        placeholder="اكتب الأهداف التي تم تحقيقها...">{{ old('goals_achieved', $results->goals_achieved) }}</textarea>
                                    @error('goals_achieved')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- التحديات -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        التحديات التي واجهت الفعالية
                                        <span class="text-gray-400 text-xs">(اتركه فارغًا إذا لم يكن محدد)</span>
                                    </label>
                                    <textarea name="challenges" rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-vertical"
                                        placeholder="اكتب التحديات التي واجهت الفعالية...">{{ old('challenges', $results->challenges) }}</textarea>
                                    @error('challenges')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- الملاحظات والمرفقات -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-file-alt ml-2 text-purple-600"></i>
                                الملاحظات والمرفقات
                            </h2>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- الملاحظات -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        ملاحظات عامة
                                        <span class="text-gray-400 text-xs">(اتركه فارغًا إذا لم يكن محدد)</span>
                                    </label>
                                    <textarea name="notes" rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-vertical"
                                        placeholder="اكتب ملاحظاتك العامة...">{{ old('notes', $results->notes) }}</textarea>
                                    @error('notes')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- صور وفيديوهات الفعالية -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        صور وفيديوهات الفعالية الجديدة
                                        <span class="text-gray-400 text-xs">(اتركها فارغة إذا لم ترد تغييرها - يمكن رفع عدة
                                            ملفات)</span>
                                    </label>
                                    <input type="file" name="images[]" multiple accept="image/*,video/*"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-gray-500 text-xs mt-1">يمكن رفع صور (JPG, PNG, GIF) وفيديوهات (MP4, AVI,
                                        MOV) بحد أقصى 10 ميجابايت لكل ملف</p>
                                    @if ($results->images)
                                        <p class="text-sm text-gray-600 mt-2">سيتم استبدال الملفات الحالية بالملفات الجديدة
                                        </p>
                                    @endif
                                    @error('images')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- ملف التقرير -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        ملف التقرير الجديد
                                        <span class="text-gray-400 text-xs">(اتركه فارغًا إذا لم ترد تغييره - PDF, DOC,
                                            DOCX)</span>
                                    </label>
                                    <input type="file" name="report_file" accept=".pdf,.doc,.docx"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @if ($results->report_file)
                                        <p class="text-sm text-gray-600 mt-2">
                                            الملف الحالي: <a
                                                href="{{ asset('assets/files/activity_reports/' . $results->report_file) }}"
                                                target="_blank"
                                                class="text-blue-600 hover:underline">{{ $results->report_file }}</a>
                                        </p>
                                    @endif
                                    @error('report_file')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4">
                            <a href="{{ route('manager.activities.results.view', $activity->id) }}"
                                class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
                                <i class="fas fa-arrow-left ml-2"></i>
                                العودة للنتائج
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-save ml-2"></i>
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
