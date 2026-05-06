<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ساهم')</title>

    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/public/template-app.css') }}">

    @stack('styles')
</head>

<body class="relative">

    {{-- ===== NAVBAR ===== --}}
    <header class="bg-[#2c3e50] text-white sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                {{-- الشعار --}}
                <a href="{{ route('public.home') }}" class="flex items-center gap-2 flex-shrink-0">
                    <img src="/assets/images/logos/logo.png" alt="شعار ساهم"
                         class="h-14 w-auto transition-transform duration-300 hover:scale-105">
                    <span class="text-white text-xl font-bold hidden sm:block tracking-tight">
                        سا<span class="text-yellow-400">هم</span>
                    </span>
                </a>

                {{-- روابط الديسكتوب --}}
                <nav class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('public.home') }}"
                       class="px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                              {{ Request::routeIs('public.home') ? 'text-yellow-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        الرئيسية
                    </a>
                    <a href="{{ route('public.organizations.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                              {{ Request::routeIs('public.organizations.*') ? 'text-yellow-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        الجمعيات
                    </a>
                    <a href="{{ route('public.organization.events_index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                              {{ Request::routeIs('public.organization.events_index') ? 'text-yellow-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        فعاليات الجمعيات
                    </a>
                    <a href="{{ route('public.activities.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                              {{ Request::routeIs('public.activities.*') ? 'text-yellow-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        فعاليات ساهم
                    </a>

                    {{-- فاصل --}}
                    <div class="w-px h-5 bg-white/15 mx-1"></div>

                    {{-- رابط الفعاليات المنجزة --}}
                    <a href="{{ route('public.completed-activities') }}"
                       class="px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-200
                              {{ Request::routeIs('public.completed-activities') ? 'text-yellow-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }} flex items-center gap-1.5">
                        <i class="fas fa-trophy text-xs"></i>
                        الفعاليات المنجزة
                    </a>
                </nav>

                {{-- أزرار المستخدم --}}
                <div class="hidden lg:flex items-center gap-3">
                    @php $volunteer = Auth::guard('volunteer')->user(); @endphp

                    @if ($volunteer)
                        <div class="flex items-center gap-2 bg-white/8 border border-white/15
                                    rounded-full py-1.5 pr-1.5 pl-4">
                            <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center
                                        text-[#2c3e50] font-bold text-sm flex-shrink-0">
                                {{ mb_substr($volunteer->name, 0, 1) }}
                            </div>
                            <span class="text-white text-sm font-semibold max-w-[100px] truncate">
                                {{ $volunteer->name }}
                            </span>
                            <a href="{{ route('volunteer.dashboard') }}"
                               class="text-yellow-400 text-xs bg-yellow-400/15 px-3 py-1.5 rounded-full
                                      hover:bg-yellow-400/25 transition-all duration-200 font-semibold whitespace-nowrap">
                                لوحتي
                            </a>
                            <a href="{{ route('volunteer.logout') }}"
                               class="text-red-400 text-xs px-2 py-1.5 rounded-full
                                      hover:bg-red-400/15 transition-all duration-200 whitespace-nowrap">
                                خروج
                            </a>
                        </div>
                    @else
                        <button onclick="openLoginModal()"
                                class="border border-white/30 text-white px-4 py-2 rounded-lg text-sm font-semibold
                                       hover:border-yellow-400 hover:text-yellow-400 transition-all duration-200">
                            تسجيل الدخول
                        </button>
                        <a href="{{ route('public.volunteer.register') }}"
                           class="bg-yellow-400 text-[#2c3e50] px-5 py-2 rounded-lg text-sm font-bold
                                  hover:bg-yellow-300 transition-all duration-200 whitespace-nowrap">
                            كن متطوعاً
                        </a>
                    @endif
                </div>

                {{-- زر الهامبرغر --}}
                <button id="mobile-toggle"
                        class="lg:hidden text-white p-2 rounded-lg hover:bg-white/10 transition focus:outline-none">
                    <i id="hamburger-icon" class="fas fa-bars text-xl"></i>
                </button>
            </div>

            {{-- قائمة الموبايل --}}
            <div id="mobile-menu" class="lg:hidden hidden border-t border-white/10 pb-4 pt-3">
                <nav class="flex flex-col gap-1">
                    <a href="{{ route('public.home') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold transition
                              {{ Request::routeIs('public.home') ? 'text-yellow-400 bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        <i class="fas fa-home text-xs w-4 opacity-60"></i>الرئيسية
                    </a>
                    <a href="{{ route('public.organizations.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold
                              text-gray-300 hover:text-white hover:bg-white/10 transition">
                        <i class="fas fa-building text-xs w-4 opacity-60"></i>الجمعيات
                    </a>
                    <a href="{{ route('public.organization.events_index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold
                              text-gray-300 hover:text-white hover:bg-white/10 transition">
                        <i class="fas fa-calendar text-xs w-4 opacity-60"></i>فعاليات الجمعيات
                    </a>
                    <a href="{{ route('public.activities.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold
                              text-gray-300 hover:text-white hover:bg-white/10 transition">
                        <i class="fas fa-star text-xs w-4 opacity-60"></i>فعاليات ساهم
                    </a>
                    <span class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-semibold
                                 text-gray-500 border border-white/10 mt-1 cursor-not-allowed select-none">
                        <i class="fas fa-check-circle text-xs w-4 text-gray-500"></i>
                        الفعاليات المنجزة
                        <span class="text-[10px] bg-yellow-400/20 text-yellow-400 px-1.5 py-0.5 rounded-full font-bold mr-auto">قريباً</span>
                    </span>
                </nav>

                {{-- أزرار المتطوع في الموبايل --}}
                <div class="pt-4 mt-3 border-t border-white/10 px-1">
                    @if ($volunteer)
                        <div class="flex items-center justify-between bg-white/8 rounded-xl px-4 py-3
                                    border border-white/12">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-yellow-400 flex items-center
                                            justify-content text-[#2c3e50] font-bold text-sm
                                            flex-shrink-0 flex items-center justify-center">
                                    {{ mb_substr($volunteer->name, 0, 1) }}
                                </div>
                                <span class="text-white text-sm font-semibold truncate max-w-[120px]">
                                    {{ $volunteer->name }}
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('volunteer.dashboard') }}"
                                   class="bg-yellow-400 text-[#2c3e50] px-3 py-1.5 rounded-lg text-xs font-bold
                                          hover:bg-yellow-300 transition whitespace-nowrap">
                                    لوحتي
                                </a>
                                <a href="{{ route('volunteer.logout') }}"
                                   class="border border-red-400/40 text-red-400 px-3 py-1.5 rounded-lg text-xs
                                          hover:bg-red-400/15 transition whitespace-nowrap">
                                    خروج
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="flex gap-3">
                            <button onclick="openLoginModal()"
                                    class="flex-1 border border-white/30 text-white py-2.5 rounded-lg
                                           text-sm font-semibold hover:border-yellow-400 hover:text-yellow-400 transition">
                                تسجيل الدخول
                            </button>
                            <a href="{{ route('public.volunteer.register') }}"
                               class="flex-1 text-center bg-yellow-400 text-[#2c3e50] py-2.5 rounded-lg
                                      text-sm font-bold hover:bg-yellow-300 transition">
                                كن متطوعاً
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    {{-- ===== HERO SLIDER ===== --}}
    @if (Request::routeIs('public.home'))
        <div class="relative mt-6 overflow-hidden rounded-xl h-128 md:h-[500px]">
            @php
                $allItems = array_merge($recentOrgEvents->toArray(), $sahemActivities->toArray());
                $slides = array_chunk($allItems, 2);
            @endphp

            @foreach ($slides as $index => $slideItems)
                <div class="hero-slide absolute w-full h-full flex gap-4 transition-opacity duration-1000
                            {{ $index == 0 ? 'opacity-100' : 'opacity-0' }}">
                    @foreach ($slideItems as $item)
                        @php
                            $title = $item['title'] ?? '';
                            $imagePath = $item['image'] ?? null;
                            if (isset($item['organization_id'])) {
                                $fullPath = $imagePath && file_exists(public_path('assets/images/organization_events/' . $imagePath))
                                    ? 'assets/images/organization_events/' . $imagePath
                                    : 'assets/images/default-event.png';
                            } else {
                                $fullPath = $imagePath && file_exists(public_path('assets/images/activities/' . $imagePath))
                                    ? 'assets/images/activities/' . $imagePath
                                    : 'assets/images/default-event.png';
                            }
                        @endphp
                        <div class="w-1/2 relative overflow-hidden rounded-lg">
                            <img src="{{ asset($fullPath) }}" alt="{{ $title }}"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center
                                        text-white text-xl md:text-2xl font-bold text-center px-2">
                                {{ $title }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <button id="prev-slide"
                    class="absolute top-1/2 left-2 -translate-y-1/2 bg-black/30 text-white p-2
                           rounded-full hover:bg-black/60 transition z-10">
                &#10094;
            </button>
            <button id="next-slide"
                    class="absolute top-1/2 right-2 -translate-y-1/2 bg-black/30 text-white p-2
                           rounded-full hover:bg-black/60 transition z-10">
                &#10095;
            </button>
        </div>
    @endif

    {{-- ===== MODAL LOGIN ===== --}}
    <div id="loginModal" class="login-modal" style="display: none;">
        <div class="login-modal-backdrop"></div>
        <div class="login-modal-box">
            <div class="login-modal-header">
                <h2>تسجيل دخول المتطوع</h2>
                <button type="button" onclick="closeLoginModal()" class="close-btn">&times;</button>
            </div>

            <form method="POST" action="{{ route('volunteer.login.post') }}" class="login-form">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="form-control"
                           required value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control"
                               required style="padding-left: 2.5rem;">
                        <button type="button" onclick="togglePasswordVisibility()"
                                style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
                                       background: none; border: none; color: #6c757d; cursor: pointer;">
                            <i id="togglePasswordIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login-submit">دخول</button>
            </form>

            <div class="login-footer">
                <p>ليس لديك حساب؟
                    <a href="{{ route('public.volunteer.register') }}" onclick="closeLoginModal()">سجل كمتطوع</a>
                </p>
            </div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="container mx-auto my-2 px-4">

        @if (session('success'))
            <div id="successMessage"
                 class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow-lg"
                 role="alert" style="z-index: 9999; min-width: 300px;">
                <i class="fas fa-check-circle ms-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div id="errorMessage"
                 class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow-lg"
                 role="alert" style="z-index: 9999; min-width: 300px;">
                <i class="fas fa-exclamation-circle ms-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-[#2c3e50] text-white mt-10">

        {{-- القسم الرئيسي --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

                {{-- العمود 1: الشعار والإحصائيات --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <a href="{{ route('public.home') }}" class="flex items-center gap-3 mb-4">
                        <img src="/assets/images/logos/logo.png" alt="شعار ساهم" class="h-14 w-auto">
                        <span class="text-xl font-bold">سا<span class="text-yellow-400">هم</span></span>
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6 max-w-[220px]">
                        منصة ساهم لدعم الجمعيات والفعاليات الإنسانية وتسهيل المشاركة التطوعية في المجتمع.
                    </p>

                    {{-- إحصائيات --}}
                    <div class="flex gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-2xl font-black text-yellow-400">+1200</div>
                            <div class="text-xs text-gray-500 mt-0.5">متطوع</div>
                        </div>
                        <div class="w-px bg-white/10"></div>
                        <div class="text-center">
                            <div class="text-2xl font-black text-yellow-400">+85</div>
                            <div class="text-xs text-gray-500 mt-0.5">جمعية</div>
                        </div>
                        <div class="w-px bg-white/10"></div>
                        <div class="text-center">
                            <div class="text-2xl font-black text-yellow-400">+340</div>
                            <div class="text-xs text-gray-500 mt-0.5">فعالية</div>
                        </div>
                    </div>

                    {{-- سوشيال ميديا --}}
                    <div class="flex gap-2">
                        <a href="#" title="Twitter"
                           class="w-9 h-9 rounded-lg bg-white/8 border border-white/12 flex items-center justify-center
                                  text-gray-400 hover:text-yellow-400 hover:border-yellow-400/40
                                  hover:bg-yellow-400/10 transition-all duration-200">
                            <i class="fab fa-x-twitter text-sm"></i>
                        </a>
                        <a href="#" title="Instagram"
                           class="w-9 h-9 rounded-lg bg-white/8 border border-white/12 flex items-center justify-center
                                  text-gray-400 hover:text-yellow-400 hover:border-yellow-400/40
                                  hover:bg-yellow-400/10 transition-all duration-200">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="#" title="Facebook"
                           class="w-9 h-9 rounded-lg bg-white/8 border border-white/12 flex items-center justify-center
                                  text-gray-400 hover:text-yellow-400 hover:border-yellow-400/40
                                  hover:bg-yellow-400/10 transition-all duration-200">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" title="YouTube"
                           class="w-9 h-9 rounded-lg bg-white/8 border border-white/12 flex items-center justify-center
                                  text-gray-400 hover:text-yellow-400 hover:border-yellow-400/40
                                  hover:bg-yellow-400/10 transition-all duration-200">
                            <i class="fab fa-youtube text-sm"></i>
                        </a>
                    </div>
                </div>

                {{-- العمود 2: روابط سريعة --}}
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 pb-3 border-b border-white/10">
                        روابط سريعة
                    </h4>
                    <nav class="flex flex-col gap-2">
                        <a href="{{ route('public.home') }}"
                           class="text-gray-400 text-sm hover:text-yellow-400 transition flex items-center gap-2">
                            <i class="fas fa-chevron-left text-xs opacity-40"></i>الرئيسية
                        </a>
                        <a href="{{ route('public.organizations.index') }}"
                           class="text-gray-400 text-sm hover:text-yellow-400 transition flex items-center gap-2">
                            <i class="fas fa-chevron-left text-xs opacity-40"></i>الجمعيات
                        </a>
                        <a href="{{ route('public.organization.events_index') }}"
                           class="text-gray-400 text-sm hover:text-yellow-400 transition flex items-center gap-2">
                            <i class="fas fa-chevron-left text-xs opacity-40"></i>فعاليات الجمعيات
                        </a>
                        <a href="{{ route('public.activities.index') }}"
                           class="text-gray-400 text-sm hover:text-yellow-400 transition flex items-center gap-2">
                            <i class="fas fa-chevron-left text-xs opacity-40"></i>فعاليات ساهم
                        </a>
                        <a href="{{ route('public.completed-activities') }}"
                           class="text-gray-400 text-sm hover:text-yellow-400 transition flex items-center gap-2 mt-1">
                            <i class="fas fa-chevron-left text-xs opacity-40"></i>الفعاليات المنجزة
                        </a>
                        <a href="{{ route('public.volunteer.register') }}"
                           class="text-gray-400 text-sm hover:text-yellow-400 transition flex items-center gap-2 mt-1">
                            <i class="fas fa-chevron-left text-xs opacity-40"></i>كن متطوعاً
                        </a>
                    </nav>
                </div>

                {{-- العمود 3: عن المنصة --}}
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 pb-3 border-b border-white/10">
                        عن المنصة
                    </h4>
                    <nav class="flex flex-col gap-2">
                        <a href="#" class="text-gray-400 text-sm hover:text-yellow-400 transition flex items-center gap-2">
                            <i class="fas fa-chevron-left text-xs opacity-40"></i>عن ساهم
                        </a>
                        <a href="#" class="text-gray-400 text-sm hover:text-yellow-400 transition flex items-center gap-2">
                            <i class="fas fa-chevron-left text-xs opacity-40"></i>كيف تعمل المنصة
                        </a>
                        <a href="#" class="text-gray-400 text-sm hover:text-yellow-400 transition flex items-center gap-2">
                            <i class="fas fa-chevron-left text-xs opacity-40"></i>الأسئلة الشائعة
                        </a>
                        <a href="#" class="text-gray-400 text-sm hover:text-yellow-400 transition flex items-center gap-2">
                            <i class="fas fa-chevron-left text-xs opacity-40"></i>سياسة الخصوصية
                        </a>
                        <a href="#" class="text-gray-400 text-sm hover:text-yellow-400 transition flex items-center gap-2">
                            <i class="fas fa-chevron-left text-xs opacity-40"></i>الشروط والأحكام
                        </a>
                    </nav>
                    <a href="{{ route('auth.login') }}"
                       class="inline-flex items-center gap-2 mt-5 border border-yellow-400/30 text-yellow-400
                              text-xs px-4 py-2 rounded-lg hover:bg-yellow-400/10 transition-all duration-200">
                        <i class="fas fa-lock text-xs"></i>
                        تسجيل دخول الموظفين
                    </a>
                </div>

                {{-- العمود 4: تواصل + نشرة --}}
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 pb-3 border-b border-white/10">
                        تواصل معنا
                    </h4>
                    <div class="flex flex-col gap-3 mb-5">
                        <a href="mailto:support@sahm.sa"
                           class="flex items-center gap-2.5 text-gray-400 text-sm hover:text-yellow-400 transition">
                            <i class="fas fa-envelope text-xs text-yellow-400/60 w-4"></i>
                            support@sahm.sa
                        </a>
                        <a href="tel:920000000"
                           class="flex items-center gap-2.5 text-gray-400 text-sm hover:text-yellow-400 transition">
                            <i class="fas fa-phone text-xs text-yellow-400/60 w-4"></i>
                            920-000-000
                        </a>
                        <span class="flex items-center gap-2.5 text-gray-400 text-sm">
                            <i class="fas fa-map-marker-alt text-xs text-yellow-400/60 w-4"></i>
                            الجمهورية العربية السورية
                        </span>
                    </div>

                    {{-- نشرة بريدية --}}
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                        <p class="text-gray-400 text-xs font-semibold mb-3">
                            <i class="fas fa-bell ml-1 text-yellow-400"></i>
                            اشترك لتصلك آخر الفعاليات
                        </p>
                        <form class="flex gap-2" onsubmit="return false;">
                            <input type="email" placeholder="بريدك الإلكتروني"
                                   class="flex-1 bg-[#243342] border border-white/15 rounded-lg text-white
                                          text-xs px-3 py-2 outline-none placeholder-gray-500
                                          focus:border-yellow-400/50 transition font-sans">
                            <button type="submit"
                                    class="bg-yellow-400 text-[#2c3e50] text-xs font-bold px-3 py-2
                                           rounded-lg hover:bg-yellow-300 transition flex-shrink-0">
                                اشتراك
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- الشريط السفلي --}}
        <div class="border-t border-white/8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5
                        flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-gray-500 text-xs">
                    © {{ date('Y') }} ساهم — جميع الحقوق محفوظة
                </p>
                <div class="flex items-center gap-4 flex-wrap justify-center">
                    <a href="#" class="text-gray-500 text-xs hover:text-gray-300 transition">سياسة الخصوصية</a>
                    <a href="#" class="text-gray-500 text-xs hover:text-gray-300 transition">الشروط</a>
                    <a href="#" class="text-gray-500 text-xs hover:text-gray-300 transition">إمكانية الوصول</a>
                    <a href="#" class="text-gray-500 text-xs hover:text-gray-300 transition">خريطة الموقع</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- ===== SCRIPTS ===== --}}
    <script>
        // Mobile menu toggle
        const toggleBtn = document.getElementById('mobile-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        if (toggleBtn && mobileMenu) {
            toggleBtn.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.toggle('hidden');
                hamburgerIcon.className = isHidden ? 'fas fa-bars text-xl' : 'fas fa-times text-xl';
            });
        }

        // Modal
        function openLoginModal() {
            document.getElementById('loginModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeLoginModal() {
            document.getElementById('loginModal').style.display = 'none';
            document.body.style.overflow = '';
        }
        document.getElementById('loginModal').addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('login-modal-backdrop')) {
                closeLoginModal();
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLoginModal();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            if (params.get('open_volunteer_login') === '1') {
                openLoginModal();
                params.delete('open_volunteer_login');
                const qs = params.toString();
                const newUrl = window.location.pathname + (qs ? '?' + qs : '') + window.location.hash;
                window.history.replaceState({}, '', newUrl);
            }
        });

        // Password toggle
        function togglePasswordVisibility() {
            const input = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Hero Slider
        const heroSlides = document.querySelectorAll('.hero-slide');
        let currentSlide = 0;
        function showSlide(index) {
            heroSlides.forEach((slide, i) => {
                slide.classList.toggle('opacity-100', i === index);
                slide.classList.toggle('opacity-0', i !== index);
            });
        }
        const prevBtn = document.getElementById('prev-slide');
        const nextBtn = document.getElementById('next-slide');
        if (prevBtn && nextBtn && heroSlides.length) {
            prevBtn.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + heroSlides.length) % heroSlides.length;
                showSlide(currentSlide);
            });
            nextBtn.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % heroSlides.length;
                showSlide(currentSlide);
            });
            setInterval(() => {
                currentSlide = (currentSlide + 1) % heroSlides.length;
                showSlide(currentSlide);
            }, 5000);
        }
    </script>

    @stack('scripts')
</body>

</html>
