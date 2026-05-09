<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'لوحة المشرف')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    @stack('styles')
</head>

<body>
    @include('templates.navbar')

    <main class="main-content">
        <!-- Success and Error Messages - applied to all supervisor pages -->

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="/assets/js/app.js"></script>

    <script>
        // Auto-hide messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            // Success message
            const successMsg = document.getElementById('successMessage');
            if (successMsg) {
                setTimeout(() => {
                    successMsg.classList.remove('show');
                    successMsg.classList.add('fade');
                    setTimeout(() => successMsg.remove(), 500);
                }, 5000);
            }

            // Error message
            const errorMsg = document.getElementById('errorMessage');
            if (errorMsg) {
                setTimeout(() => {
                    errorMsg.classList.remove('show');
                    errorMsg.classList.add('fade');
                    setTimeout(() => errorMsg.remove(), 500);
                }, 5000);
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
