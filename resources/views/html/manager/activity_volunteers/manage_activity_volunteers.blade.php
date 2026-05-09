@extends('templates.manager_app')

@section('title', 'إدارة المتطوعين - ' . $activity->activity_name)

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

            <!-- Back Link -->
            <div class="mb-6">
                <a href="{{ route('manager.activity_volunteers.index') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 transition duration-200">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة لقائمة الفعاليات
                </a>
            </div>

            <!-- معلومات الفعالية -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $activity->title }}</h1>
                        <p class="text-gray-600">{{ $activity->manager->full_name ?? 'غير محدد' }}</p>
                    </div>
                    <div class="text-left">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $activity->start_date ? \Carbon\Carbon::parse($activity->start_date)->format('Y/m/d') : 'غير محدد' }}
                        </span>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-blue-600 ml-2"></i>
                            <div>
                                <p class="text-sm text-gray-600">وقت الفعالية</p>
                                <p class="font-semibold">
                                    {{ $activity->start_date ? \Carbon\Carbon::parse($activity->start_date)->format('H:i') : 'غير محدد' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-users text-green-600 ml-2"></i>
                            <div>
                                <p class="text-sm text-gray-600">المتطوعين المطلوب</p>
                                <p class="font-semibold">{{ $activity->volunteerRequirements->required_volunteers ?? 0 }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 ml-2"></i>
                            <div>
                                <p class="text-sm text-gray-600">الموقع</p>
                                <p class="font-semibold">{{ $activity->location ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($activity->description)
                    <div class="mt-4">
                        <h4 class="font-semibold text-gray-800 mb-2">وصف الفعالية:</h4>
                        <p class="text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $activity->description }}</p>
                    </div>
                @endif
            </div>

            <!-- إحصائيات المتطوعين -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-600">إجمالي المتطوعين</div>
                </div>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                    <div class="text-sm text-gray-600">في الانتظار</div>
                </div>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                    <div class="text-sm text-gray-600">مُعتمد</div>
                </div>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 text-center">
                    <div class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                    <div class="text-sm text-gray-600">مرفوض</div>
                </div>
            </div>

            <!-- أزرار العمليات -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">قائمة المتطوعين</h2>
                <div class="flex gap-2">
                    <a href="{{ route('manager.activity_volunteers.pdf', $activity->id) }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-file-pdf ml-2"></i>
                        تنزيل كشوفات الحضور (PDF)
                    </a>
                    <button onclick="openAssignModal()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-user-plus ml-2"></i>
                        إضافة متطوع جديد
                    </button>
                </div>
            </div>

            <!-- جدول المتطوعين -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    المتطوع</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    تاريخ الطلب</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    تاريخ الموافقة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    العمليات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($assignments as $assignment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600"></i>
                                                </div>
                                            </div>
                                            <div class="mr-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $assignment->volunteer->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $assignment->volunteer->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $assignment->request_date ? \Carbon\Carbon::parse($assignment->request_date)->format('Y/m/d H:i') : 'غير محدد' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$assignment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusText[$assignment->status] ?? $assignment->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $assignment->decision_date ? \Carbon\Carbon::parse($assignment->decision_date)->format('Y/m/d H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2 rtl:space-x-reverse">
                                            <!-- View Details Button -->
                                            <button onclick="viewVolunteerDetails({{ $assignment->id }})"
                                                class="text-blue-600 hover:text-blue-900 transition duration-200">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            @if ($assignment->status === 'pending')
                                                <!-- Approve Button -->
                                                <button onclick="approveVolunteer({{ $assignment->id }})"
                                                    class="text-green-600 hover:text-green-900 transition duration-200">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <!-- Reject Button -->
                                                <button onclick="rejectVolunteer({{ $assignment->id }})"
                                                    class="text-red-600 hover:text-red-900 transition duration-200">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif($assignment->status === 'approved')
                                                <!-- Remove Button -->
                                                <button onclick="removeVolunteer({{ $assignment->id }})"
                                                    class="text-red-600 hover:text-red-900 transition duration-200">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                                        <p>لا يوجد متطوعين مسجلين في هذه الفعالية</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal for Adding Volunteer -->
        <div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto  p-5 border w-50 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">إضافة متطوع للفعالية</h3>
                        <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="assignForm" onsubmit="submitAssignForm(event)">
                        @csrf
                        <!-- Search Field -->
                        <div class="mb-4">
                            <label for="volunteerSearchInput" class="block text-sm font-medium text-gray-700 mb-2">بحث عن
                                متطوع</label>
                            <input type="text" id="volunteerSearchInput" placeholder="ابحث باسم أو بريد المتطوع..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="mb-4">
                            <label for="volunteerSelect" class="block text-sm font-medium text-gray-700 mb-2">اختر
                                المتطوع</label>
                            <select id="volunteerSelect" name="volunteer_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">اختر متطوع...</option>
                            </select>
                        </div>

                        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                            <button type="button" onclick="closeAssignModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                                إلغاء
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                                إضافة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal for Volunteer Details -->
        <div id="volunteerDetailsModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-3 border w-50 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">تفاصيل المتطوع</h3>
                        <button onclick="closeVolunteerDetailsModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div id="volunteerDetailsContent">
                        <!-- Details will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Global variables
            let currentActivityId = {{ $activity->id }};

            // Add Modal functions
            function openAssignModal() {
                loadAvailableVolunteers();
                document.getElementById('assignModal').classList.remove('hidden');
            }

            function closeAssignModal() {
                document.getElementById('assignModal').classList.add('hidden');
                document.getElementById('assignForm').reset();
            }

            // Load available volunteers
            function loadAvailableVolunteers() {
                fetch(`{{ route('manager.activity_volunteers.available', $activity->id) }}`)
                    .then(response => response.json())
                    .then(data => {
                        const select = document.getElementById('volunteerSelect');
                        select.innerHTML = '<option value="">اختر متطوع...</option>';

                        // حفظ البيانات الأصلية
                        window.allVolunteers = data;

                        data.forEach(volunteer => {
                            const option = document.createElement('option');
                            option.value = volunteer.id;
                            option.textContent =
                                `${volunteer.name} (${volunteer.email})`;
                            select.appendChild(option);
                        });

                        // إضافة مستمع البحث
                        addSearchListener();
                    })
                    .catch(error => {
                        console.error('Error loading volunteers:', error);
                        alert('حدث خطأ في تحميل قائمة المتطوعين');
                    });
            }

            // Add search listener
            function addSearchListener() {
                const searchInput = document.getElementById('volunteerSearchInput');
                const volunteerSelect = document.getElementById('volunteerSelect');

                searchInput.addEventListener('input', function(e) {
                    const searchText = e.target.value.toLowerCase();

                    volunteerSelect.innerHTML = '<option value="">اختر متطوع...</option>';

                    if (!window.allVolunteers) return;

                    window.allVolunteers.forEach(volunteer => {
                        if (volunteer.name.toLowerCase().includes(searchText) ||
                            volunteer.email.toLowerCase().includes(searchText)) {
                            const option = document.createElement('option');
                            option.value = volunteer.id;
                            option.textContent = `${volunteer.name} (${volunteer.email})`;
                            volunteerSelect.appendChild(option);
                        }
                    });
                });
            }

            // Submit assign form
            function submitAssignForm(event) {
                event.preventDefault();

                const formData = new FormData(event.target);
                const volunteerId = formData.get('volunteer_id');

                fetch(`{{ route('manager.activity_volunteers.assign', $activity->id) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                                '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            volunteer_id: volunteerId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeAssignModal();
                            location.reload();
                        } else {
                            alert(data.message || 'حدث خطأ في إضافة المتطوع');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ في إضافة المتطوع');
                    });
            }

            // Approve volunteer
            function approveVolunteer(assignmentId) {
                if (!confirm('هل أنت متأكد من الموافقة على هذا المتطوع؟')) return;

                fetch(`{{ route('manager.activity_volunteers.approve', [$activity->id, ':assignmentId']) }}`.replace(
                        ':assignmentId',
                        assignmentId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                                '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '{{ route('manager.activity_volunteers.index') }}';
                        } else {
                            alert(data.message || 'حدث خطأ في الموافقة على المتطوع');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ في الموافقة على المتطوع');
                    });
            }

            // Reject volunteer
            function rejectVolunteer(assignmentId) {
                const reason = prompt('يرجى إدخال سبب الرفض:');
                if (!reason) return;

                fetch(`{{ route('manager.activity_volunteers.reject', [$activity->id, ':assignmentId']) }}`.replace(
                        ':assignmentId',
                        assignmentId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                                '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            rejection_reason: reason
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '{{ route('manager.activity_volunteers.index') }}';
                        } else {
                            alert(data.message || 'حدث خطأ في رفض المتطوع');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ في رفض المتطوع');
                    });
            }

            // Remove volunteer
            function removeVolunteer(assignmentId) {
                const reason = prompt('يرجى إدخال سبب الإزالة:');
                if (!reason) return;

                fetch(`{{ route('manager.activity_volunteers.remove', [$activity->id, ':assignmentId']) }}`.replace(
                        ':assignmentId',
                        assignmentId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                                '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            removal_reason: reason
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '{{ route('manager.activity_volunteers.index') }}';
                        } else {
                            alert(data.message || 'حدث خطأ في إزالة المتطوع');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ في إزالة المتطوع');
                    });
            }

            // View volunteer details
            function viewVolunteerDetails(assignmentId) {
                fetch(`{{ route('manager.activity_volunteers.details', [$activity->id, ':assignmentId']) }}`.replace(
                        ':assignmentId',
                        assignmentId))
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('volunteerDetailsContent').innerHTML = html;
                        document.getElementById('volunteerDetailsModal').classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ في تحميل تفاصيل المتطوع');
                    });
            }

            function closeVolunteerDetailsModal() {
                document.getElementById('volunteerDetailsModal').classList.add('hidden');
            }

            // Close Modal when clicking outside
            document.addEventListener('click', function(event) {
                const assignModal = document.getElementById('assignModal');
                const volunteerDetailsModal = document.getElementById('volunteerDetailsModal');

                if (event.target === assignModal) {
                    closeAssignModal();
                }
                if (event.target === volunteerDetailsModal) {
                    closeVolunteerDetailsModal();
                }
            });
        </script>
    </div>
@endsection
