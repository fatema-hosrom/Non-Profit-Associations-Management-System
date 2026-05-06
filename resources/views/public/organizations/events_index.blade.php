@extends('public.template_layouts.app')

@section('title', 'جميع فعاليات الجمعيات - ساهم')

@section('content')
    <section class="max-w-7xl mx-auto my-12 px-4">
        <h2 class="text-3xl font-bold text-center mb-8 text-indigo-900">
            جميع فعاليات الجمعيات
        </h2>

        @if ($events->count() > 0)
            <div class="grid md:grid-cols-3 gap-6">
                @foreach ($events as $event)
                    <a href="{{ route('public.organization.event_show', $event->id) }}"
                        class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 overflow-hidden">

                        @if ($event->image)
                            <img src="{{ asset('assets/images/organization_events/' . $event->image) }}"
                                alt="{{ $event->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="bg-gray-100 h-48 flex items-center justify-center text-gray-400">
                                لا توجد صورة
                            </div>
                        @endif

                        <div class="p-4">
                            <h3 class="text-lg font-bold mb-2">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($event->description, 100) }}</p>
                            <p class="text-sm text-gray-500 mb-2">الموقع: {{ $event->location ?? 'غير محدد' }}</p>
                            <p class="text-sm text-gray-500 mb-2">
                                {{ $event->start_date ? 'تبدأ: ' . $event->start_date->format('d/m/Y H:i') : '' }}
                                {{ $event->end_date ? ' | تنتهي: ' . $event->end_date->format('d/m/Y H:i') : '' }}
                            </p>
                            <span
                                class="inline-block mt-2 px-3 py-1 bg-indigo-700 text-white rounded font-semibold hover:bg-indigo-600 transition">
                                عرض التفاصيل
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500">لا توجد فعاليات للجمعيات حالياً.</p>
        @endif
    </section>
@endsection
