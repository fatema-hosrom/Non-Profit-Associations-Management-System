<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="{{ asset('assets/css/manager/volunteers-pdf.css') }}">
</head>
<body>
    <div class="header">
        <h1>قائمة المتطوعين - {{ $activity->title }}</h1>
        <p>تاريخ التوليد: {{ $date }}</p>
    </div>

    <div class="activity-info">
        <p><strong>الموقع:</strong> {{ $activity->location }}</p>
        <p><strong>التاريخ:</strong> من {{ $activity->start_date->format('Y-m-d') }} إلى {{ $activity->end_date->format('Y-m-d') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>اسم المتطوع</th>
                <th>البريد الإلكتروني</th>
                <th>كود التحقق الفريد</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $index => $assignment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $assignment->volunteer->name }}</td>
                    <td dir="ltr" style="text-align: left;">{{ $assignment->volunteer->email }}</td>
                    <td dir="ltr" class="code" style="text-align: center;">{{ $assignment->checkin_code }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right; font-size: 9pt;">
        <p>منصة ساهم - نظام إدارة التطوع</p>
    </div>
</body>
</html>