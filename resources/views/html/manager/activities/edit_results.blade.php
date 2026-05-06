@extends('templates.manager_app')

@section('title', 'تعديل نتائج الفعالية')

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <!-- Header Gradient -->
                    <div class="mb-8">
                        <div
                            class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 rounded-xl shadow-lg p-8 text-white">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-white/20 p-4 rounded-lg backdrop-blur-sm">
                                        <i class="fas fa-pen-to-square text-2xl"></i>
                                    </div>
                                    <div>
                                        <h1 class="text-4xl font-bold">تعديل نتائج الفعالية</h1>
                                        <p class="text-blue-100 mt-1">{{ $activity->title }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('manager.activities.index') }}"
                                    class="inline-flex items-center px-6 py-3 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 transition backdrop-blur-sm">
                                    <i class="fas fa-arrow-right ml-2"></i>
                                    العودة للقائمة
                                </a>
                            </div>
                            <p class="text-blue-100">قم بتعديل نتائج وإحصائيات الفعالية</p>
                        </div>
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

                    <!-- نموذج التعديل -->
                    <form method="POST" action="{{ route('manager.activities.results.update', $activity->id) }}"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- معلومات التطوع -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <div class="bg-blue-100 p-3 rounded-lg ml-3">
                                    <i class="fas fa-users text-blue-600 text-lg"></i>
                                </div>
                                معلومات التطوع والحضور
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- عدد المتطوعين -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-people-group text-blue-600 ml-2"></i>
                                        عدد المتطوعين
                                        <span class="text-gray-400 text-xs">(اختياري)</span>
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
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-hourglass-end text-green-600 ml-2"></i>
                                        مجموع الساعات
                                        <span class="text-gray-400 text-xs">(اختياري)</span>
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
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-clipboard-check text-purple-600 ml-2"></i>
                                        عدد الحضور
                                        <span class="text-gray-400 text-xs">(اختياري)</span>
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
                                <div class="bg-green-100 p-3 rounded-lg ml-3">
                                    <i class="fas fa-bullseye text-green-600 text-lg"></i>
                                </div>
                                الأهداف والتحديات
                            </h2>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- الأهداف المحققة -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-check-double text-green-600 ml-2"></i>
                                        الأهداف المحققة
                                        <span class="text-gray-400 text-xs">(اختياري)</span>
                                    </label>
                                    <textarea name="goals_achieved" rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-vertical">{{ old('goals_achieved', $results->goals_achieved) }}</textarea>
                                    @error('goals_achieved')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- التحديات -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-exclamation-triangle text-orange-600 ml-2"></i>
                                        التحديات المواجهة
                                        <span class="text-gray-400 text-xs">(اختياري)</span>
                                    </label>
                                    <textarea name="challenges" rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-vertical">{{ old('challenges', $results->challenges) }}</textarea>
                                    @error('challenges')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- الملاحظات والمرفقات -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <div class="bg-purple-100 p-3 rounded-lg ml-3">
                                    <i class="fas fa-file-alt text-purple-600 text-lg"></i>
                                </div>
                                الملاحظات والمرفقات
                            </h2>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- الملاحظات -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-sticky-note text-purple-600 ml-2"></i>
                                        ملاحظات عامة
                                        <span class="text-gray-400 text-xs">(اختياري)</span>
                                    </label>
                                    <textarea name="notes" rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-vertical">{{ old('notes', $results->notes) }}</textarea>
                                    @error('notes')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- رفع الصور -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-image text-indigo-600 ml-2"></i>
                                        صور الفعالية
                                        <span class="text-gray-400 text-xs">(اختياري - عدة صور مسموحة)</span>
                                    </label>

                                    <!-- عرض الصور الحالية -->
                                    @if($results->images)
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-600 mb-2">الصور الحالية:</p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                                @foreach (explode("\n", $results->images) as $image)
                                                    @if (trim($image))
                                                        <div class="relative group">
                                                            <img src="{{ asset('assets/images/activity_results/' . trim($image)) }}"
                                                                alt="صورة الفعالية"
                                                                class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                                            <div class="absolute inset-0 bg-red bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
                                                                <span class="text-white opacity-0 group-hover:opacity-100 transition-opacity text-sm">سيتم حذفها عند الرفع</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <input type="file" name="images[]" multiple accept="image/*"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                                    <p class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-info-circle ml-1"></i>
                                        يمكنك اختيار عدة صور جديدة للفعالية (سيتم حذف الصور القديمة واستبدالها بالجديدة)
                                    </p>
                                    @error('images')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    @error('images.*')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- ملف التقرير -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-file-pdf text-red-600 ml-2"></i>
                                        ملف التقرير الجديد
                                        <span class="text-gray-400 text-xs">(اختياري - PDF, DOC, DOCX)</span>
                                    </label>
                                    <input type="file" name="report_file" accept=".pdf,.doc,.docx"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @if ($results->report_file)
                                        <p class="text-sm text-gray-600 mt-2">
                                            الملف الحالي: <a href="{{ asset('assets/files/activity_reports/' . $results->report_file) }}"
                                                target="_blank" class="text-blue-600 hover:underline">{{ $results->report_file }}</a>
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
                            <a href="{{ route('manager.activities.index') }}"
                                class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
                                <i class="fas fa-arrow-right ml-2"></i>
                                العودة للقائمة
                            </a>
                            <button type="submit"
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-save ml-2"></i>
                                حفظ التغييرات
                            </button>
                            <button type="button"
                                onclick="confirmDelete({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                                class="inline-flex items-center justify-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-trash ml-2"></i>
                                حذف النتائج
                            </button>
                        </div>
                    </form>
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
            <p class="text-gray-600 text-center mb-4">هل أنت متأكد من حذف نتائج الفعالية <span id="activityName"
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
                document.getElementById('activityName').textContent = name;
                document.getElementById('deleteForm').action = `/manager/activities/${id}/results`;
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
