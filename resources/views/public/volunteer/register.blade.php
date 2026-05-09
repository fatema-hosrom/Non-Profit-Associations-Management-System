@extends('public.template_layouts.app')

@section('title', 'تسجيل متطوع - ساهم')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/volunteer/register.css') }}">
@endpush

@section('content')
<div class="vol-page">

    {{-- HERO --}}
    <div class="vol-hero">
        <div class="vol-hero__icon"><i class="fas fa-hands-helping"></i></div>
        <h1>تسجيل متطوع جديد</h1>
        <p>ساهم معنا في دعم المبادرات الإنسانية والفعاليات التطوعية</p>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
    <div class="alert success">
        <i class="fas fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert error">
        <i class="fas fa-circle-xmark"></i>
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('public.volunteer.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-card">

    {{-- SECTION 1: Account Information --}}
            <div class="fsection">
                <div class="fsection__head">
                    <span class="bar"></span>
                    <h2><i class="fas fa-lock" style="color:var(--blue);margin-left:6px;font-size:0.85rem"></i>معلومات الحساب</h2>
                </div>
                <div class="fgrid">
                    <div class="field">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" class="finput" placeholder="example@mail.com" value="{{ old('email') }}">
                        @error('email')<p class="ferr"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label>كلمة المرور</label>
                        <input type="password" name="password" class="finput" placeholder="أدخل كلمة المرور">
                        @error('password')<p class="ferr"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label>تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" class="finput" placeholder="أعد إدخال كلمة المرور">
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Personal Information --}}
            <div class="fsection">
                <div class="fsection__head">
                    <span class="bar"></span>
                    <h2><i class="fas fa-user" style="color:var(--blue);margin-left:6px;font-size:0.85rem"></i>المعلومات الشخصية</h2>
                </div>
                <div class="fgrid">
                    <div class="field">
                        <label>الاسم الكامل</label>
                        <input type="text" name="name" class="finput" placeholder="أدخل اسمك الكامل" value="{{ old('name') }}">
                        @error('name')<p class="ferr"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label>رقم الهاتف</label>
                        <input type="text" name="phone" class="finput" placeholder="09xx xxx xxx" value="{{ old('phone') }}">
                        @error('phone')<p class="ferr"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label>الجنس</label>
                        <select name="gender" class="finput">
                            <option value="">اختر الجنس</option>
                            <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                        @error('gender')<p class="ferr"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label>العمر</label>
                        <input type="number" name="age" class="finput" placeholder="أدخل عمرك" value="{{ old('age') }}">
                        @error('age')<p class="ferr"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label>الجنسية</label>
                        <select name="nationality" class="finput">
                            <option value="">اختر الجنسية</option>
                            @foreach($countries as $country)
                            <option value="{{ $country['name_ar'] }}">{{ $country['name_ar'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>المستوى التعليمي</label>
                        <input type="text" name="education_level" class="finput" placeholder="مثل: بكالوريوس، ثانوي" value="{{ old('education_level') }}">
                        @error('education_level')<p class="ferr"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                    </div>
                    <div class="field full">
                        <label>العنوان <span class="opt">(اختياري)</span></label>
                        <input type="text" name="address" class="finput" placeholder="أدخل العنوان الكامل" value="{{ old('address') }}">
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Volunteering Information --}}
            <div class="fsection">
                <div class="fsection__head">
                    <span class="bar"></span>
                    <h2><i class="fas fa-star" style="color:var(--blue);margin-left:6px;font-size:0.85rem"></i>معلومات التطوع</h2>
                </div>
                <div class="fgrid">
                    <div class="field">
                        <label>التوفر <span class="opt">(اختياري)</span></label>
                        <input type="text" name="availability" class="finput" placeholder="مثال: نهاية الأسبوع، صباحاً" value="{{ old('availability') }}">
                    </div>
                    <div class="field">
                        <label>الأدوار المفضلة <span class="opt">(اختياري)</span></label>
                        <input type="text" name="preferred_roles" class="finput" placeholder="مثال: منسق، مدرب" value="{{ old('preferred_roles') }}">
                    </div>
                    <div class="field">
                        <label>اللغات</label>
                        <div class="check-list">
                            @foreach($languages as $lang)
                            <label class="check-item">
                                <input type="checkbox" name="languages[]" value="{{ $lang['name_ar'] }}"
                                    {{ collect(old('languages'))->contains($lang['name_ar']) ? 'checked' : '' }}>
                                <span>{{ $lang['name_ar'] }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('languages')<p class="ferr"><i class="fas fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                    </div>
                    <div class="field">
                        <label>جهة الاتصال في الطوارئ <span class="opt">(اختياري)</span></label>
                        <input type="text" name="emergency_contact" class="finput" placeholder="الاسم ورقم الهاتف" value="{{ old('emergency_contact') }}">
                    </div>
                    <div class="field full">
                        <label>المهارات <span class="opt">(اختياري)</span></label>
                        <textarea name="skills" class="finput" placeholder="اذكر مهاراتك التي يمكن الاستفادة منها">{{ old('skills') }}</textarea>
                    </div>
                    <div class="field full">
                        <label>الخبرة <span class="opt">(اختياري)</span></label>
                        <textarea name="experience" class="finput" placeholder="اذكر خبراتك التطوعية السابقة">{{ old('experience') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="form-footer">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i>
                    إرسال طلب التسجيل
                </button>
            </div>

        </div>
    </form>

</div>
@endsection
