@extends('layouts.site')

@section('title', __('Rólam').' – '.$profile->name)

@section('content')
    @include('partials.page-head', ['label' => __('Rólam'), 'title' => $profile->headline ?: __('Rólam')])

    <section class="mx-auto grid max-w-7xl gap-5 px-5 lg:grid-cols-12">
        <div class="card p-8 lg:col-span-8" data-reveal>
            <div class="prose-about text-lg">{!! $profile->about !!}</div>
        </div>

        <div class="space-y-5 lg:col-span-4">
            <div class="card p-7" data-reveal>
                <p class="label mb-4">{{ __('Elérhetőség') }}</p>
                <ul class="space-y-3 text-sm text-white/75">
                    @if ($profile->location) <li>📍 {{ $profile->location }}</li> @endif
                    @if ($profile->email) <li>✉️ <a class="hover:text-white" href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></li> @endif
                    @if ($profile->phone) <li>📞 <a class="hover:text-white" href="tel:{{ preg_replace('/\s+/', '', $profile->phone) }}">{{ $profile->phone }}</a></li> @endif
                    @if ($profile->website) <li>🌐 <a class="hover:text-white" href="{{ $profile->website }}" target="_blank" rel="noopener">{{ preg_replace('#^https?://#', '', rtrim($profile->website, '/')) }}</a></li> @endif
                </ul>
                @if ($cv = $profile->getFirstMediaUrl('cv'))
                    <a href="{{ $cv }}" target="_blank" class="btn btn-primary mt-6">{{ __('Önéletrajz letöltése') }}</a>
                @endif
            </div>

            @php
                $personal = array_filter([
                    __('Születési dátum') => $profile->birth_date?->format(app()->getLocale() === 'hu' ? 'Y. m. d.' : 'd.m.Y'),
                    __('Születési hely') => $profile->birth_place,
                    __('Állampolgárság') => $profile->nationality,
                    __('Családi állapot') => $profile->marital_status,
                    __('Jogosítvány') => $profile->driving_license,
                ], fn ($v) => filled($v));
            @endphp
            @if ($personal)
                <div class="card p-7" data-reveal>
                    <p class="label mb-4">{{ __('Személyes adatok') }}</p>
                    <dl class="space-y-3 text-sm">
                        @foreach ($personal as $label => $value)
                            <div class="flex justify-between gap-4 border-b border-white/5 pb-3 last:border-0 last:pb-0">
                                <dt class="text-white/50">{{ $label }}</dt>
                                <dd class="text-right text-white/80">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            @endif
        </div>
    </section>
@endsection
