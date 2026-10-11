@extends('layouts.site')

@section('title', __('Szakmák').' – '.$profile->name)

@section('content')
    @include('partials.page-head', ['label' => __('Amiben jártas vagyok'), 'title' => __('Szakmák'), 'lead' => __('Több területen dolgozom – kattints egy szakmára a részletekért: hol dolgoztam és milyen tapasztalatom van.')])

    <section class="mx-auto max-w-4xl space-y-4 px-5" x-data="{ openId: null }">
        @forelse ($items as $d)
            <div class="card overflow-hidden" data-reveal>
                <button type="button" class="flex w-full items-center gap-5 p-6 text-left md:p-8" @click="openId = openId === {{ $d->id }} ? null : {{ $d->id }}" :aria-expanded="openId === {{ $d->id }}">
                    <span class="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-[var(--accent-from)]/15 font-display text-2xl text-[var(--accent-from)]">{{ $d->icon ?: '◆' }}</span>
                    <span class="flex-1">
                        <span class="page-title block text-3xl md:text-4xl">{{ $d->name }}</span>
                        <span class="mt-1 block text-sm text-white/55">{{ $d->tagline }}</span>
                    </span>
                    <span class="text-2xl text-white/50 transition" :class="openId === {{ $d->id }} && 'rotate-180'">⌄</span>
                </button>

                <div x-show="openId === {{ $d->id }}" x-collapse x-cloak class="border-t border-white/10 p-6 md:p-8">
                    @if ($d->description)
                        <p class="text-sm leading-relaxed text-white/65">{{ $d->description }}</p>
                    @endif

                    @if ($d->experiences->isNotEmpty())
                        <h3 class="label mb-4 mt-8">{{ __('Hol dolgoztam') }}</h3>
                        <div class="space-y-4">
                            @foreach ($d->experiences as $exp)
                                <div class="flex flex-wrap items-start justify-between gap-2 border-b border-white/5 pb-4 last:border-0">
                                    <div>
                                        <p class="font-display font-semibold">{{ $exp->position }}</p>
                                        <p class="text-sm text-white/55"><span class="red-text">{{ $exp->company }}</span>@if ($exp->location) · {{ $exp->location }}@endif</p>
                                        @if ($exp->description) <p class="mt-2 text-sm text-white/55">{{ $exp->description }}</p> @endif
                                    </div>
                                    <span class="chip">{{ $exp->period }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-6 text-sm text-white/45">{{ __('Még nincs munkahely felvéve ehhez a szakmához.') }}</p>
                    @endif

                    @if ($d->projects->isNotEmpty())
                        <div class="mt-8">@include('partials.projects-modal', ['projects' => $d->projects])</div>
                    @endif

                    <a href="{{ route('discipline', $d->slug) }}" class="mt-8 inline-block text-sm font-medium text-[var(--accent-from)]">{{ __('Teljes oldal') }} →</a>
                </div>
            </div>
        @empty
            <p class="text-white/50">{{ __('Még nincs szakma felvéve.') }}</p>
        @endforelse
    </section>
@endsection
