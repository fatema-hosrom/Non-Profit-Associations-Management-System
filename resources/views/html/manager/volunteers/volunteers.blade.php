@extends('templates.manager_app')

@section('title', 'إدارة المتطوعين')

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900">المتطوعين</h1>
                            <p class="text-gray-500 mt-1">إدارة قائمة المتطوعين والبحث عنهم</p>
                        </div>
                        <a href="{{ route('manager.volunteers.add') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-plus ml-2"></i>
                            إضافة متطوع جديد
                        </a>
                    </div>

                    <!-- Messages -->
                    @if (session('success'))
                        <div id="successAlert"
                            class="mb-6 rounded-xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-green-50 px-4 py-3 text-emerald-800 shadow-sm transition-opacity duration-300">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="mr-3 font-medium">{{ session('success') }}</span>
                                </div>
                                <button type="button" onclick="dismissSuccessAlert()"
                                    class="rounded-md p-1 text-emerald-600 hover:bg-emerald-100 transition"
                                    aria-label="إغلاق الرسالة">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                            <i class="fas fa-exclamation-circle ml-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Search and Filter -->
                    <div class="bg-white rounded-lg shadow-sm mb-8 p-6 border border-gray-200">
                        <form method="GET" action="{{ route('manager.volunteers.index') }}"
                            class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="ابحث عن المتطوع..."
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                                </div>
                            </div>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-search ml-2"></i>
                                بحث
                            </button>
                            @if (request('search'))
                                <a href="{{ route('manager.volunteers.index') }}"
                                    class="px-6 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
                                    <i class="fas fa-times ml-2"></i>
                                    مسح
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        @if ($volunteers->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                #</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                الاسم</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                البريد الإلكتروني</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                العمر</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                الجنس</th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($volunteers as $volunteer)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ ($volunteers->currentPage() - 1) * $volunteers->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center" style="gap: 12px;">
                                                        <p class="text-sm font-semibold text-gray-900">
                                                            {{ $volunteer->name }}
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $volunteer->email }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $volunteer->age }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                                </td>


                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <a href="{{ route('manager.volunteers.show', $volunteer) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition font-semibold text-sm"
                                                            title="عرض التفاصيل">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('manager.volunteers.edit', $volunteer) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition font-semibold text-sm"
                                                            title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button"
                                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition font-semibold text-sm"
                                                            onclick="confirmDelete({{ $volunteer->id }}, '{{ $volunteer->name }}')"
                                                            title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                                        <p class="text-lg text-gray-500">لا يوجد متطوعين</p>
                                                        <p class="text-sm text-gray-400">ابدأ بإضافة متطوع جديد</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                                {{ $volunteers->links() }}
                            </div>
                        @else
                            <div class="px-6 py-12 text-center">
                                <i class="fas fa-search text-4xl text-gray-300 mb-4 block"></i>
                                <p class="text-lg text-gray-600">لم يتم العثور على نتائج</p>
                            </div>
                        @endif
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

                    function dismissSuccessAlert() {
                        const successAlert = document.getElementById('successAlert');
                        if (!successAlert) return;
                        successAlert.classList.add('opacity-0');
                        setTimeout(() => successAlert.remove(), 250);
                    }

                    setTimeout(dismissSuccessAlert, 7000);

                    // Close Modal when clicking outside
                    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
                        if (e.target === this) {
                            closeDeleteModal();
                        }
                    });
                </script>
            @endpush
        @endsection
