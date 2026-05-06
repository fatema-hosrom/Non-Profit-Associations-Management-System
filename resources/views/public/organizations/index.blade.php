@extends('public.template_layouts.app')

@section('title', 'قائمة الجمعيات - ساهم')

@section('content')
    <section class="max-w-7xl mx-auto my-12 px-4">

        <!-- عنوان الصفحة -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-indigo-900 mb-3">الجمعيات</h1>
            <p class="text-gray-600 text-lg">تصفح الجمعيات وانضم للمساهمة في فعالياتهم بسهولة</p>
        </div>

        <!-- شبكة الجمعيات -->
        <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-6">
            @foreach ($organizations as $org)
                <a href="{{ route('public.organizations.show', $org->id) }}" class="group">
                    <div
                        class="bg-white rounded-xl shadow-lg overflow-hidden transform transition hover:-translate-y-2 hover:shadow-2xl duration-300 flex flex-col h-full">

                        <!-- شعار الجمعية -->
                        <div class="flex justify-center items-center bg-gray-50 p-6">
                            @if ($org->logo)
                                <img src="{{ asset('assets/images/organizations/' . $org->logo) }}" alt="{{ $org->name }}"
                                    class="h-24 object-contain transition-transform duration-300 group-hover:scale-110">
                            @else
                                <div
                                    class="bg-gray-200 h-24 w-24 flex items-center justify-center rounded-full text-gray-400">
                                    لا يوجد شعار
                                </div>
                            @endif
                        </div>

                        <!-- معلومات الجمعية -->
                        <div class="p-6 flex flex-col justify-between flex-1">
                            <h2
                                class="text-2xl font-bold text-indigo-900 mb-2 text-center group-hover:text-green-600 transition-colors duration-300">
                                {{ $org->name }}</h2>
                            <p class="text-gray-600 text-sm text-center">{{ Str::limit($org->description, 120) }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </section>

@endsection
