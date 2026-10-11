@extends('layouts.site')

@section('content')
    @php
        $word = $profile->hero_word ?: __('ÖNÉLETRAJZ');
        $photoUrl = $profile->getFirstMediaUrl('photo', 'web');
        $nameParts = explode(' ', trim($profile->name));
        $last = array_pop($nameParts);
        $initials = collect(explode(' ', $profile->name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
    @endphp

    {{-- HERO --}}
    <section class="mx-auto max-w-7xl px-5 pt-8 lg:pt-12">
        <div class="relative lg:h-[46rem]">
            <div class="hero-word pointer-events-none text-center lg:absolute lg:inset-x-0 lg:top-0 lg:z-0" style="font-size: min(17vw, 15rem)" aria-hidden="true">{{ $word }}</div>

            <div class="relative flex flex-col items-center lg:absolute lg:inset-x-0 lg:bottom-0 lg:top-[12rem] lg:flex-row-reverse lg:items-center lg:justify-between lg:px-4">
            <div class="relative z-10 flex justify-center">
                @if ($photoUrl)
                    <figure class="hero-photo mt-2 aspect-square w-60 sm:w-72 lg:mt-0 lg:w-[23rem]">
                        <img src="{{ $photoUrl }}" alt="{{ $profile->name }}" class="h-full w-full object-cover object-top">
                    </figure>
                @else
                    <div class="photo-fade relative -mt-4 grid h-80 w-72 place-items-center overflow-hidden rounded-t-[3rem] border border-white/10 bg-gradient-to-b from-white/10 to-transparent lg:mt-0 lg:h-96 lg:w-[26rem]">
                        <div class="checker-mini absolute inset-0 opacity-10"></div>
                        <span class="relative font-impact text-[9rem] leading-none text-white/80">{{ $initials }}</span>
                    </div>
                @endif
            </div>

            <div class="relative z-20 mt-6 w-full lg:mt-0 lg:max-w-sm">
                @if ($profile->greeting)
                    <p class="font-display text-lg text-white/70">{{ $profile->greeting }}</p>
                @endif
                <h1 class="page-title mt-4 text-5xl sm:text-6xl">{{ implode(' ', $nameParts) }} <span class="red-text">{{ $last }}</span></h1>
                <div class="mt-4 flex min-h-[2rem] items-center font-display text-lg text-white/85" x-data="typer(@js($profile->roles ?: [$profile->headline]))">
                    <span class="mr-2 text-[var(--accent-to)]">/</span><span x-text="text"></span><span class="ml-0.5 inline-block h-5 w-0.5 animate-pulse bg-[var(--accent-from)]"></span>
                </div>
                @if ($profile->tagline)
                    <p class="mt-4 text-sm leading-relaxed text-white/55">{{ $profile->tagline }}</p>
                @endif
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('discipline', ['slug' => 'informatika', 'projektek' => 1]) }}" class="btn btn-primary">{{ __('Projektjeim') }}</a>
                    <a href="{{ route('resume') }}" target="_blank" class="btn btn-ghost">{{ __('Letölthető verzió') }}</a>
                </div>
            </div>
            </div>
        </div>
    </section>

    {{-- BENTO --}}
    <section class="mx-auto mt-14 max-w-7xl px-5">
        <div class="grid gap-5 lg:grid-cols-12">
            <a href="{{ route('about') }}" class="card block p-7 lg:col-span-5" data-reveal>
                <p class="label mb-4">{{ __('Rólam') }}</p>
                <p class="text-lg leading-relaxed text-white/75">{{ \Illuminate\Support\Str::limit(strip_tags((string) $profile->about), 260) }}</p>
                <p class="mt-6 text-sm font-medium text-[var(--accent-from)]">{{ __('Tovább') }} →</p>
            </a>

            <div class="card p-7 lg:col-span-7" data-reveal>
                <div class="mb-5 flex items-center justify-between">
                    <p class="label">{{ __('Szakmáim') }}</p>
                    <a href="{{ route('disciplines') }}" class="text-xs text-white/50 hover:text-white">{{ __('Mind') }} →</a>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    @forelse ($navDisciplines as $d)
                        <a href="{{ route('discipline', $d->slug) }}" class="group flex items-center gap-4 rounded-xl border border-white/8 bg-white/[.02] p-4 transition hover:border-[var(--accent-from)]">
                            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-lg bg-[var(--accent-from)]/15 font-display text-lg text-[var(--accent-from)]">{{ $d->icon ?: '◆' }}</span>
                            <span>
                                <span class="block font-display font-semibold">{{ $d->name }}</span>
                                <span class="block text-xs text-white/45">{{ $d->tagline }}</span>
                            </span>
                        </a>
                    @empty
                        <p class="text-sm text-white/50">{{ __('Adj hozzá szakmákat az admin felületen.') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="lg:col-span-12" data-reveal>
                <div class="mb-5 mt-6 flex items-end justify-between">
                    <h2 class="section-title text-3xl md:text-4xl">{{ __('Válogatott') }} <span class="red-text">{{ __('projektek') }}</span></h2>
                    <a href="{{ route('discipline', ['slug' => 'informatika', 'projektek' => 1]) }}" class="text-sm text-white/50 hover:text-white">{{ __('Összes') }} →</a>
                </div>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:col-span-12 lg:grid-cols-3">
                @foreach ($projects as $project)
                    @include('partials.project-card', ['project' => $project])
                @endforeach
            </div>

            <div class="card p-7 lg:col-span-5" data-reveal>
                <div class="mb-5 flex items-center justify-between">
                    <p class="label">{{ __('Legutóbbi tapasztalat') }}</p>
                    <a href="{{ route('experience') }}" class="text-xs text-white/50 hover:text-white">{{ __('Részletek') }} →</a>
                </div>
                <div class="space-y-5">
                    @foreach ($experiences as $exp)
                        <div class="flex gap-4 border-b border-white/5 pb-5 last:border-0 last:pb-0">
                            <span class="font-impact text-2xl text-[var(--accent-from)]">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                <p class="font-display font-semibold">{{ $exp->position }}</p>
                                <p class="text-xs text-white/50">{{ $exp->company }} · {{ $exp->period }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card p-7 lg:col-span-4" data-reveal>
                <div class="mb-5 flex items-center justify-between">
                    <p class="label">{{ __('Képességek') }}</p>
                    <a href="{{ route('skills') }}" class="text-xs text-white/50 hover:text-white">{{ __('Mind') }} →</a>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($skillGroups->flatMap->skills->sortByDesc('level')->take(14) as $skill)
                        <span class="chip">{{ $skill->name }}</span>
                    @endforeach
                </div>
            </div>

            @if ($profile->quote)
                <div class="card-red flex flex-col justify-between p-7 lg:col-span-3" data-reveal>
                    <span class="font-impact text-6xl leading-none text-white/80">“</span>
                    <p class="font-display text-sm leading-snug text-white">{{ $profile->quote }}</p>
                    @if ($profile->quote_author) <p class="mt-5 text-sm text-white/70">— {{ $profile->quote_author }}</p> @endif
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="mx-auto mt-20 max-w-7xl px-5" data-reveal>
        <div class="card flex flex-col items-start justify-between gap-6 p-8 md:flex-row md:items-center md:p-12">
            <h2 class="page-title text-5xl md:text-7xl">{{ __('Dolgozzunk') }} <span class="red-text">{{ __('együtt') }}</span></h2>
            <a href="{{ route('contact') }}" class="btn btn-primary">{{ __('Írj nekem') }} →</a>
        </div>
    </section>
@endsection
