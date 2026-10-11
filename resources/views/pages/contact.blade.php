@extends('layouts.site')

@section('title', __('Kapcsolat').' – '.$profile->name)

@section('content')
    @include('partials.page-head', ['label' => __('Kapcsolat'), 'title' => __('Dolgozzunk együtt'), 'lead' => __('Van egy ötleted vagy állásajánlatod? Írj nyugodtan, hamarosan válaszolok.')])

    <section class="mx-auto grid max-w-7xl gap-5 px-5 lg:grid-cols-[1.4fr_1fr]">
        <div class="card p-6 md:p-10" data-reveal>
            <livewire:contact-form />
        </div>
        <div class="card p-8" data-reveal>
            <p class="label mb-4">{{ __('Elérhetőség') }}</p>
            <ul class="space-y-3 text-white/75">
                @if ($profile->location) <li>📍 {{ $profile->location }}</li> @endif
                @if ($profile->email) <li>✉️ <a class="hover:text-white" href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></li> @endif
                @if ($profile->phone) <li>📞 <a class="hover:text-white" href="tel:{{ preg_replace('/\s+/', '', $profile->phone) }}">{{ $profile->phone }}</a></li> @endif
            </ul>
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach ($profile->socials ?? [] as $s)
                    <a href="{{ $s['url'] }}" target="_blank" rel="noopener" class="chip">{{ $s['label'] }} ↗</a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
