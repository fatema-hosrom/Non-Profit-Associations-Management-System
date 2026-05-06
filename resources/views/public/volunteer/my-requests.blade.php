@extends('public.template_layouts.app')

@section('title', 'طلباتي')

@section('content')
    <div class="requests-container">
        <div class="page-header">
            <h1>طلبات التطوع الخاصة بي</h1>
            <p>عرض جميع طلبات التطوع والحالة الحالية</p>
        </div>

        @if ($requests->count() === 0)
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h2>لم تقدم أي طلبات حتى الآن</h2>
                <p>
                    <a href="{{ route('volunteer.available-activities') }}" class="link-primary">
                        استعرض الفعاليات المتاحة
                    </a>
                </p>
            </div>
        @else
            <div class="requests-table-responsive">
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>الفعالية</th>
                            <th>التاريخ</th>
                            <th>حالة الطلب</th>
                            <th>تاريخ الطلب</th>
                            <th>تسجيل الحضور</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr>
                                <td class="activity-cell">
                                    <strong>{{ $request->activity->title }}</strong>
                                    <small>{{ $request->activity->location }}</small>
                                </td>
                                <td class="date-cell">
                                    {{ $request->activity->start_date->format('Y-m-d') }}
                                </td>
                                <td class="status-cell">
                                    <span class="status-badge status-{{ $request->status }}">
                                        @switch($request->status)
                                            @case('pending')
                                                قيد الانتظار
                                            @break

                                            @case('approved')
                                                مقبول
                                            @break

                                            @case('rejected')
                                                مرفوض
                                            @break

                                            @case('removed')
                                                تمت الإزالة
                                            @break

                                            @default
                                                معطل
                                        @endswitch
                                    </span>
                                </td>
                                <td class="date-cell">
                                    {{ $request->request_date->format('Y-m-d H:i') }}
                                </td>
                                <td class="checkin-cell">
                                    @if($request->status == 'approved')
                                        @if($request->checked_in_at)
                                            <span class="status-badge status-approved">
                                                <i class="fas fa-check-circle ml-1"></i> تم الحضور
                                            </span>
                                        @else
                                            <div class="checkin-form">
                                                <input type="text" placeholder="أدخل الكود" class="checkin-input" id="code-{{ $request->activity_id }}">
                                                <button onclick="submitCheckin({{ $request->activity_id }})" class="btn-checkin">تحقق</button>
                                            </div>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/volunteer/my-requests.css') }}">
    @endpush

    @push('scripts')
    <script>
        function submitCheckin(activityId) {
            const codeInput = document.getElementById(`code-${activityId}`);
            if (!codeInput) {
                alert('تعذر العثور على حقل الكود');
                return;
            }
            const code = codeInput.value.trim();
            if (!code) {
                alert('يرجى إدخال كود التحقق');
                return;
            }

            const url = `{{ url('/volunteer/activity') }}/${activityId}/check-in`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ code: code }),
            })
            .then(async (response) => {
                const text = await response.text();
                let data = {};
                try {
                    data = text ? JSON.parse(text) : {};
                } catch (e) {
                    console.error('Non-JSON response', text);
                    throw new Error('استجابة غير متوقعة من السيرفر');
                }
                if (!response.ok) {
                    throw new Error(data.message || data.error || `خطأ ${response.status}`);
                }
                return data;
            })
            .then((data) => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'حدث خطأ ما');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert(error.message || 'حدث خطأ في الاتصال بالسيرفر');
            });
        }
    </script>
    @endpush
@endsection
