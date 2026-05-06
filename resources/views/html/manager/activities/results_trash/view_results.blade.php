@extends('templates.manager_app')

@section('title', 'نتائج الفعالية')

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    <!-- Header -->
                    <div class="mb-8 flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">نتائج الفعالية</h1>
                            <p class="text-gray-500 mt-2">نتائج فعالية: <span
                                    class="font-semibold text-gray-700">{{ $activity->title }}</span></p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('manager.activities.results.edit', $activity->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-edit ml-2"></i>
                                تعديل النتائج
                            </a>
                            <a href="{{ route('manager.activities.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
                                <i class="fas fa-arrow-right ml-2"></i>
                                العودة
                            </a>
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

                    @if ($results)
                        <!-- معلومات التطوع -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-users ml-2 text-blue-600"></i>
                                معلومات التطوع
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600">عدد المتطوعين المشاركين فعليًا</div>
                                    <div class="text-2xl font-bold text-gray-900 mt-1">
                                        {{ $results->total_volunteers ?? 'غير محدد' }}
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600">مجموع ساعات العمل التطوعي</div>
                                    <div class="text-2xl font-bold text-gray-900 mt-1">
                                        {{ $results->total_hours ?? 'غير محدد' }}
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600">عدد الحضور</div>
                                    <div class="text-2xl font-bold text-gray-900 mt-1">
                                        {{ $results->attendance_count ?? 'غير محدد' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الأهداف والتحديات -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">

                                الأهداف والتحديات
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">الأهداف المحققة</h3>
                                    <div class="bg-green-50 p-4 rounded-lg">
                                        {{ $results->goals_achieved ?? 'لم يتم تحديد أهداف' }}
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">التحديات</h3>
                                    <div class="bg-red-50 p-4 rounded-lg">
                                        {{ $results->challenges ?? 'لم يتم تحديد تحديات' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الملاحظات والمرفقات -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-file-alt ml-2 text-purple-600"></i>
                                الملاحظات والمرفقات
                            </h2>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- الملاحظات -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">الملاحظات العامة</h3>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        {{ $results->notes ?? 'لا توجد ملاحظات' }}
                                    </div>
                                </div>

                                <!-- الصور والفيديوهات -->
                                @if ($results->images)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3">الصور والفيديوهات</h3>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            @php
                                                $files = json_decode($results->images, true);
                                            @endphp
                                            @if ($files && is_array($files))
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                    @foreach ($files as $file)
                                                        @php
                                                            $filePath = 'assets/files/activity_results/' . $file;
                                                            $fileExtension = strtolower(
                                                                pathinfo($file, PATHINFO_EXTENSION),
                                                            );
                                                            $isImage = in_array($fileExtension, [
                                                                'jpg',
                                                                'jpeg',
                                                                'png',
                                                                'gif',
                                                            ]);
                                                            $isVideo = in_array($fileExtension, ['mp4', 'avi', 'mov']);
                                                        @endphp
                                                        @if ($isImage)
                                                            <div class="relative">
                                                                <img src="{{ asset($filePath) }}" alt="صورة الفعالية"
                                                                    class="w-full h-48 object-cover rounded-lg shadow-sm">
                                                                <a href="{{ asset($filePath) }}" target="_blank"
                                                                    class="absolute top-2 left-2 bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 transition">
                                                                    <i class="fas fa-external-link-alt text-sm"></i>
                                                                </a>
                                                            </div>
                                                        @elseif($isVideo)
                                                            <div class="relative">
                                                                <video controls
                                                                    class="w-full h-48 object-cover rounded-lg shadow-sm">
                                                                    <source src="{{ asset($filePath) }}"
                                                                        type="video/{{ $fileExtension }}">
                                                                    متصفحك لا يدعم تشغيل الفيديو.
                                                                </video>
                                                            </div>
                                                        @else
                                                            <div class="bg-white p-4 rounded-lg border">
                                                                <i class="fas fa-file text-gray-400 text-2xl mb-2"></i>
                                                                <p class="text-sm text-gray-600">{{ $file }}</p>
                                                                <a href="{{ asset($filePath) }}" target="_blank"
                                                                    class="text-blue-600 hover:text-blue-800 text-sm">عرض
                                                                    الملف</a>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- ملف التقرير -->
                                @if ($results->report_file)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3">ملف التقرير</h3>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <a href="{{ asset('assets/files/activity_reports/' . $results->report_file) }}"
                                                target="_blank"
                                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                                                <i class="fas fa-download ml-2"></i>
                                                تحميل التقرير
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- معلومات النظام -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-info-circle ml-2 text-gray-600"></i>
                                معلومات النظام
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="text-sm text-gray-600">تاريخ الإضافة</div>
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ $results->created_at->format('Y-m-d H:i') }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">آخر تحديث</div>
                                    <div class="text-lg font-semibold text-gray-900">
                                        {{ $results->updated_at->format('Y-m-d H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- زر الحذف -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-red-900 mb-4">حذف النتائج</h3>
                            <p class="text-red-700 mb-4">تحذير: هذا الإجراء لا يمكن التراجع عنه. سيتم حذف جميع نتائج
                                الفعالية نهائيًا.</p>
                            <form method="POST" action="{{ route('manager.activities.results.destroy', $activity->id) }}"
                                onsubmit="return confirm('هل أنت متأكد من حذف نتائج الفعالية؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-trash ml-2"></i>
                                    حذف النتائج
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-4xl mb-4"></i>
                            <h3 class="text-xl font-semibold text-yellow-900 mb-2">لا توجد نتائج</h3>
                            <p class="text-yellow-700 mb-4">لم يتم إضافة نتائج لهذه الفعالية بعد.</p>
                            <a href="{{ route('manager.activities.results.add', $activity->id) }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-plus ml-2"></i>
                                إضافة نتائج
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
