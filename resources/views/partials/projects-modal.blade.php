@props(['projects', 'media' => null, 'open' => false, 'label' => null, 'compact' => false])

@php
    $label ??= __('Projektjeim');
    $media ??= collect();
    $referenceCount = $projects->isNotEmpty() ? $projects->count() : $media->count();
@endphp

<div x-data="{ show: @js($open) }" @keydown.escape.window="show = false" @class(['inline-block' => $compact])>
    @if ($compact)
        <button type="button" @click="show = true" class="btn btn-ghost">
            {{ $label }} ({{ $referenceCount }}) <span class="ml-1 text-[var(--accent-from)]">↗</span>
        </button>
    @else
    <button type="button" @click="show = true" class="card card-hover flex w-full items-center justify-between gap-4 p-6 text-left">
        <span>
            <span class="label">{{ $label }}</span>
            <span class="mt-1 block font-display text-xl font-semibold">{{ __(':count projekt megtekintése', ['count' => $projects->count()]) }}</span>
        </span>
        <span class="text-2xl text-[var(--accent-from)]">↗</span>
    </button>
    @endif

    <template x-teleport="body">
        <div x-show="show" x-cloak x-transition.opacity class="fixed inset-0 z-[100] flex items-start justify-center overflow-y-auto bg-black/80 p-4 backdrop-blur-sm sm:p-8" @click.self="show = false" role="dialog" aria-modal="true" aria-label="{{ __('Projektek') }}">
            <div class="relative w-full max-w-6xl rounded-2xl border border-white/10 bg-[#0d0d0f] p-6 sm:p-8">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="section-title text-3xl">{{ $label }}</h2>
                    <button type="button" @click="show = false" class="grid h-10 w-10 place-items-center rounded-lg border border-white/10 text-xl hover:bg-white/5" aria-label="{{ __('Bezárás') }}">×</button>
                </div>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        @include('partials.project-card', ['project' => $project, 'reveal' => false])
                    @endforeach
                    @foreach ($media as $image)
                        <figure class="card overflow-hidden">
                            <img src="{{ $image->getUrl('web') }}" alt="{{ $label }}" loading="lazy" class="aspect-[16/10] h-full w-full object-cover">
                        </figure>
                    @endforeach
                    @if ($projects->isEmpty() && $media->isEmpty())
                        <p class="text-white/50">{{ __('Még nincs projekt felvéve.') }}</p>
                    @endif
                </div>
                <div class="mt-8 flex justify-center">
                    <button type="button" @click="show = false" class="btn btn-ghost">{{ __('Bezárás') }}</button>
                </div>
            </div>
        </div>
    </template>
</div>
