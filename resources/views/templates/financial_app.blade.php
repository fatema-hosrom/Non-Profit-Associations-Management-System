<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة المدير المالي')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/financial/services.css">
    @stack('styles')
</head>

<body>

    @include('templates.navbar_financial')

    <main class="main-content">
        @if (session('success'))
            <div id="successMessage"
                class="mx-auto my-4 max-w-4xl rounded-lg bg-green-100 border border-green-400 px-4 py-3 text-green-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="/assets/js/app.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successMsg = document.getElementById('successMessage');
            if (successMsg) {
                setTimeout(() => {
                    successMsg.classList.remove('show');
                    successMsg.classList.add('fade');
                    setTimeout(() => successMsg.remove(), 500);
                }, 5000);
            }

            const errorMsg = document.getElementById('errorMessage');
            if (errorMsg) {
                setTimeout(() => {
                    errorMsg.classList.remove('show');
                    errorMsg.classList.add('fade');
                    setTimeout(() => errorMsg.remove(), 500);
                }, 5000);
            }

            const tailwindMessages = document.querySelectorAll(
                '.bg-green-100.border.border-green-400, .bg-red-100.border.border-red-400'
            );

            tailwindMessages.forEach(message => {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    message.style.opacity = '0';
                    message.style.transform = 'translateX(100%)';

                    setTimeout(() => {
                        if (message.parentNode) {
                            message.remove();
                        }
                    }, 500);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
