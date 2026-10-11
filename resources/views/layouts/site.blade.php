<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $profile->seo_title ?: $profile->name.' – '.__('Önéletrajz'))</title>
    <meta name="description" content="{{ $profile->seo_description ?: $profile->tagline }}">
    <meta property="og:title" content="{{ $profile->seo_title ?: $profile->name }}">
    <meta property="og:description" content="{{ $profile->seo_description ?: $profile->tagline }}">
    @if ($photo = $profile->getFirstMediaUrl('photo', 'web'))
        <meta property="og:image" content="{{ $photo }}">
    @endif
    <meta name="theme-color" content="#090909">
    @vite(['resources/js/app.js'])
    @livewireStyles
    <style>:root{--accent-from: {{ $profile->accent_from ?: '#e63946' }}; --accent-to: {{ $profile->accent_to ?: '#2ec27e' }};}</style>
</head>
<body class="antialiased">
    <div class="noise"></div>
    <div class="chess-floor" aria-hidden="true"></div>
    <canvas id="bg-canvas" class="pointer-events-none fixed inset-0 z-0" aria-hidden="true" data-motifs="{{ json_encode($profile->bg_motifs ?: []) }}"></canvas>

    @php
        $links = [
            'home' => __('Kezdőlap'), 'about' => __('Rólam'), 'experience' => __('Tapasztalat'),
            'skills' => __('Képességek'), 'interests' => __('Szenvedélyek'),
        ];
        $activeKey = request()->route()?->getName();
        $initials = collect(explode(' ', $profile->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
        $switchUrl = fn (string $l) => request()->route()?->getName()
            ? route(request()->route()->getName(), [...request()->route()->parameters(), ...request()->query(), 'locale' => $l])
            : route('home', ['locale' => $l]);
    @endphp

    <header x-data="{ open: false }" class="sticky top-0 z-50 border-b border-white/5 bg-[#090909]/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-[var(--accent-from)] font-impact text-lg text-white">{{ $initials }}</span>
                <span class="hidden font-display text-sm font-semibold tracking-wide sm:block">{{ $profile->name }}</span>
            </a>

            <nav class="hidden items-center gap-1 text-sm lg:flex">
                @foreach ($links as $route => $label)
                    <a href="{{ route($route) }}" class="rounded-lg px-3 py-2 transition {{ $activeKey === $route ? 'text-white' : 'text-white/55 hover:text-white' }}">
                        {{ $label }}
                        @if ($activeKey === $route)<span class="mx-auto mt-1 block h-0.5 w-5 rounded bg-[var(--accent-from)]"></span>@endif
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <div class="flex items-center rounded-lg border border-white/10 text-xs font-medium" role="group" aria-label="{{ __('Nyelv') }}">
                    @foreach (config('app.locales') as $code => $name)
                        <a href="{{ $switchUrl($code) }}" hreflang="{{ $code }}" lang="{{ $code }}" title="{{ $name }}" class="px-2.5 py-2 uppercase transition {{ app()->getLocale() === $code ? 'text-white' : 'text-white/45 hover:text-white' }}">{{ $code }}</a>
                    @endforeach
                </div>
                <a href="{{ route('resume') }}" target="_blank" class="btn btn-ghost hidden !py-2 md:inline-flex">{{ __('Letölthető verzió') }}</a>
                <a href="{{ route('contact') }}" class="btn btn-primary hidden !py-2 sm:inline-flex">{{ __('Kapcsolat') }}</a>
                <button @click="open = !open" class="grid h-10 w-10 place-items-center rounded-lg border border-white/10 lg:hidden" aria-label="Menü">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>
        <nav x-show="open" x-cloak @click.outside="open = false" class="border-t border-white/5 px-5 pb-4 lg:hidden">
            @foreach ($links + ['contact' => __('Kapcsolat'), 'resume' => __('Letölthető verzió')] as $route => $label)
                <a href="{{ route($route) }}" @if ($route === 'resume') target="_blank" @endif class="block border-b border-white/5 py-3 {{ $activeKey === $route ? 'text-[var(--accent-from)]' : 'text-white/70' }}">{{ $label }}</a>
            @endforeach
        </nav>
    </header>

    <main class="relative z-10">
        @yield('content')
    </main>

    <footer class="relative z-10 mt-24 border-t border-white/5">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-5 py-10 text-sm text-white/45 md:flex-row md:items-center md:justify-between">
            <p>© {{ date('Y') }} {{ $profile->name }}@if ($profile->footer_text) · {{ $profile->footer_text }}@endif</p>
            <div class="flex flex-wrap gap-x-5 gap-y-2">
                @foreach ($profile->socials ?? [] as $s)
                    <a href="{{ $s['url'] }}" target="_blank" rel="noopener" class="transition hover:text-white">{{ $s['label'] }} ↗</a>
                @endforeach
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
