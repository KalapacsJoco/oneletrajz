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
            <div class="relative z-10 flex justify-center" x-data="photoIntro">
                @if ($photoUrl)
                    <figure x-ref="figure" class="w-60 aspect-square sm:w-72 lg:w-[23rem] hero-photo mt-2 lg:mt-0" style="visibility:hidden">
                        <img src="{{ $photoUrl }}" alt="{{ $profile->name }}" class="h-full w-full object-cover object-top">
                    </figure>
                    <script>if (sessionStorage.getItem('photoIntroSeen') || document.referrer.startsWith(location.origin)) document.currentScript.previousElementSibling.style.visibility = '';</script>
                    <template x-teleport="body">
                    <div x-show="visible" x-cloak x-transition.opacity.duration.500ms class="fixed bottom-3 left-3 z-[9999] w-[15rem] overflow-hidden rounded-lg border border-[#3c3c3c] bg-[#1e1e1e] text-left font-mono text-[10px] leading-4 text-[#d4d4d4] opacity-100 shadow-2xl shadow-black/70 sm:bottom-4 sm:left-4 sm:w-[30rem] sm:text-[13px] sm:leading-6" aria-hidden="true">
                        <div class="relative hidden h-8 items-center justify-center bg-[#323233] text-xs text-[#cccccc] sm:flex">
                            <span class="absolute left-3 flex gap-1.5"><i class="size-3 rounded-full bg-[#ff5f56]"></i><i class="size-3 rounded-full bg-[#ffbd2e]"></i><i class="size-3 rounded-full bg-[#27c93f]"></i></span>
                            home.blade.php — oneletrajz — Visual Studio Code
                        </div>
                        <div class="flex h-6 items-end bg-[#252526] text-[10px] sm:h-9 sm:text-xs">
                            <div class="flex h-6 items-center gap-2 border-t border-t-[#007acc] bg-[#1e1e1e] px-3 text-white sm:h-9">
                                <span class="font-bold text-[#e44d26]">&lt;/&gt;</span>
                                <span>home.blade.php</span>
                                <span class="ml-2 inline-block size-2 rounded-full" :class="saved ? 'bg-transparent' : 'bg-[#c5c5c5]'"></span>
                            </div>
                        </div>
                        <div class="hidden border-b border-[#2b2b2b] px-4 py-0.5 text-[11px] text-[#a9a9a9] sm:block">resources › views › pages › home.blade.php</div>
                        <div class="px-0 py-1 sm:py-2">
                            <div class="flex"><span class="w-7 shrink-0 select-none pr-2 text-right text-[#858585] sm:w-10 sm:pr-4">18</span><span><span class="text-[#808080]">&lt;</span><span class="text-[#569cd6]">figure</span></span></div>
                            <div class="flex"><span class="w-7 shrink-0 select-none pr-2 text-right text-[#858585] sm:w-10 sm:pr-4">19</span><span class="pl-2 sm:pl-4"><span class="text-[#9cdcfe]">class</span><span class="text-[#d4d4d4]">=</span><span class="text-[#ce9178]">"</span></span></div>
                            <template x-for="(c, i) in typed" :key="c">
                                <div class="flex" :class="i < typed.length - 2 && 'max-sm:hidden'"><span class="w-7 shrink-0 select-none pr-2 text-right text-[#858585] sm:w-10 sm:pr-4" x-text="20 + i"></span><span class="pl-4 text-[#ce9178] sm:pl-8" x-text="c"></span></div>
                            </template>
                            <div class="flex bg-[#2a2d2e]"><span class="w-7 shrink-0 select-none pr-2 text-right text-white sm:w-10 sm:pr-4" x-text="20 + typed.length"></span><span class="pl-4 text-[#ce9178] sm:pl-8"><span x-text="current"></span><span class="ml-px inline-block h-3 w-[2px] translate-y-0.5 animate-pulse bg-[#aeafad] sm:h-4"></span></span></div>
                            <div class="flex"><span class="w-7 shrink-0 select-none pr-2 text-right text-[#858585] sm:w-10 sm:pr-4" x-text="21 + typed.length"></span><span class="pl-2 sm:pl-4"><span class="text-[#ce9178]">"</span><span class="text-[#808080]">&gt;</span></span></div>
                        </div>
                        <div class="flex h-5 items-center justify-between bg-[#007acc] px-3 font-sans text-[10px] text-white sm:h-6 sm:text-[11px]">
                            <span>⎇ main</span>
                            <span x-text="saved ? 'Saved' : 'Ln ' + (20 + typed.length)"></span>
                        </div>
                    </div>
                    </template>
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
