@extends('public.template_layouts.app')

@section('title', $organization->name . ' - ساهم')

@section('content')
    <section class="max-w-6xl mx-auto my-12 px-4">

        <!-- معلومات الجمعية -->
        <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col md:flex-row items-center md:items-start gap-8">
            <!-- الشعار -->
            <div class="flex-shrink-0">
                @if ($organization->logo)
                    <img src="{{ asset('assets/images/organizations/' . $organization->logo) }}"
                        alt="{{ $organization->name }}" class="h-40 w-40 object-contain rounded-lg shadow-md">
                @else
                    <div class="bg-gray-200 h-40 w-40 flex items-center justify-center rounded-lg text-gray-400">
                        لا يوجد شعار
                    </div>
                @endif
            </div>

            <!-- التفاصيل -->
            <div class="flex-1">
                <h1 class="text-4xl font-bold text-indigo-900 mb-4">{{ $organization->name }}</h1>
                <p class="text-gray-700 mb-4">{{ $organization->description }}</p>

                <div class="flex flex-wrap gap-4 text-gray-700 mb-6">
                    @if ($organization->type)
                        <span class="bg-gray-100 px-3 py-1 rounded font-semibold">النوع:
                            {{ [
                                'local' => 'محلية',
                                'external' => 'خارجية',
                            ][$organization->type] }}</span>
                    @endif
                    @if ($organization->contact_email)
                        <span class="bg-gray-100 px-3 py-1 rounded font-semibold">البريد:
                            {{ $organization->contact_email }}</span>
                    @endif
                    @if ($organization->contact_phone)
                        <span class="bg-gray-100 px-3 py-1 rounded font-semibold">الهاتف:
                            {{ $organization->contact_phone }}</span>
                    @endif
                </div>

                @if ($organization->website_url)
                    <a href="{{ $organization->website_url }}" target="_blank"
                        class="bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-600 transition">
                        زيارة الموقع الرسمي
                    </a>
                @endif
            </div>
        </div>

        <!-- فعاليات الجمعية -->
        <section class="mt-12">
            <h2 class="text-3xl font-bold text-indigo-900 mb-6 text-center">فعاليات هذه الجمعية</h2>

            @if ($organization->events->count() > 0)
                <div class="flex overflow-x-auto space-x-6 scrollbar-hide px-2">
                    @foreach ($organization->events as $event)
                        <a href="{{ route('public.organization.event_show', $event->id) }}"
                            class="min-w-[220px] bg-white rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 flex-shrink-0 overflow-hidden">
                            @if ($event->image)
                                <img src="{{ asset('assets/images/organization_events/' . $event->image) }}"
                                    alt="{{ $event->title }}" class="h-40 w-full object-cover">
                            @else
                                <div class="bg-gray-100 h-40 flex items-center justify-center text-gray-400">
                                    لا توجد صورة
                                </div>
                            @endif
                            <div class="p-4">
                                <h4 class="text-lg font-bold mb-1">{{ $event->title }}</h4>
                                <p class="text-sm text-gray-600">{{ Str::limit($event->description, 60) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500">لا توجد فعاليات حالياً لهذه الجمعية.</p>
            @endif
        </section>

    </section>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/public/organization-show.css') }}">
    @endpush
@endsection
