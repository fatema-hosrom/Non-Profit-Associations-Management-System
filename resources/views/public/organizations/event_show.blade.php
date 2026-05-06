@extends('public.template_layouts.app')

@section('title', $event->title . ' - ساهم')

@section('content')
    <section class="max-w-5xl mx-auto my-12 px-4">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <!-- صورة الفعالية -->
            @if ($event->image)
                <img src="{{ asset('assets/images/organization_events/' . $event->image) }}" alt="{{ $event->title }}"
                    class="w-full h-64 object-cover rounded-lg mb-6">
            @endif

            <h1 class="text-3xl font-bold text-indigo-900 mb-4">{{ $event->title }}</h1>
            <h4 class="text-xl text-[tomato] font-semibold mb-4">{{ $event->organization->name }}</h4>
            <p class="text-gray-700 mb-4">{{ $event->description }}</p>

            <div class="flex flex-wrap gap-4 text-gray-700 mb-6">
                @if ($event->location)
                    <span class="bg-gray-100 px-3 py-1 rounded font-semibold">الموقع: {{ $event->location }}</span>
                @endif
                @if ($event->start_date)
                    <span class="bg-gray-100 px-3 py-1 rounded font-semibold">
                        بداية: {{ $event->start_date->format('d/m/Y H:i') }}
                    </span>
                @endif
                @if ($event->end_date)
                    <span class="bg-gray-100 px-3 py-1 rounded font-semibold">
                        نهاية: {{ $event->end_date->format('d/m/Y H:i') }}
                    </span>
                @endif
            </div>

            @if ($event->external_url)
                <a href="{{ $event->external_url }}" target="_blank"
                    class="bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-600 transition">
                    زيارة موقع الفعالية
                </a>
            @endif
        </div>

        <!-- فعاليات أخرى للجمعية -->
        @if ($otherEvents->count() > 0)

            <section class="mt-12">
                <h2 class="text-2xl font-bold mb-4 text-indigo-900">فعاليات {{ $event->organization->name }}</h2>
                <div class="flex overflow-x-auto space-x-6 scrollbar-hide px-2">
                    @foreach ($otherEvents as $otherEvent)
                        @if ($otherEvent->id !== $event->id)
                            <a href="{{ route('public.organization.event_show', $otherEvent->id) }}"
                                class="min-w-[200px] bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 flex-shrink-0 overflow-hidden">
                                @if ($otherEvent->image)
                                    <img src="{{ asset('assets/images/organization_events/' . $otherEvent->image) }}"
                                        alt="{{ $otherEvent->title }}" class="h-36 w-full object-cover">
                                @else
                                    <div class="bg-gray-100 h-36 flex items-center justify-center text-gray-400">
                                        لا توجد صورة
                                    </div>
                                @endif
                                <div class="p-3">
                                    <h4 class="text-lg font-bold mb-1">{{ $otherEvent->title }}</h4>
                                    <span
                                        class="text-sm text-gray-600">{{ Str::limit($otherEvent->description, 50) }}</span>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif
    </section>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/public/event-show.css') }}">
    @endpush
@endsection
