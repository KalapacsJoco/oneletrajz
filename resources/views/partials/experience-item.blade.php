<article class="card card-hover p-6 md:p-7" data-reveal x-data="{ open: false }">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h3 class="font-display text-xl font-semibold">{{ $exp->position }}</h3>
            <p class="mt-1 text-sm text-white/65">
                <span class="red-text">{{ $exp->company }}</span>@if ($exp->location) · {{ $exp->location }}@endif
            </p>
        </div>
        <div class="flex flex-col items-end gap-2">
            <span class="chip">{{ $exp->period }}</span>
            @if ($exp->discipline) <span class="text-xs text-white/40">{{ $exp->discipline->icon }} {{ $exp->discipline->name }}</span> @endif
        </div>
    </div>
    @if ($exp->description) <p class="mt-4 text-sm leading-relaxed text-white/60">{{ $exp->description }}</p> @endif
    @if (! empty($exp->highlights))
        <ul class="mt-4 space-y-2 text-sm text-white/70">
            @foreach ($exp->highlights as $h)
                <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[var(--accent-to)]"></span>{{ $h }}</li>
            @endforeach
        </ul>
    @endif

    @php $referenceImages = $exp->getMedia('reference-image'); @endphp
    @if ($exp->projects->isNotEmpty() || $referenceImages->isNotEmpty())
        <div class="mt-5">
            @include('partials.projects-modal', ['projects' => $exp->projects, 'media' => $referenceImages, 'label' => __('Referenciamunkáim'), 'compact' => true])
        </div>
    @endif

    @if (filled(strip_tags((string) $exp->details)))
        <div x-show="open" x-collapse x-cloak>
            <div class="prose-about mt-5 border-t border-white/10 pt-5 text-sm">
                {!! str($exp->details)->sanitizeHtml() !!}
            </div>
        </div>
        <button type="button" @click="open = !open" :aria-expanded="open" class="mt-5 inline-flex items-center gap-2 text-sm font-medium text-[var(--accent-from)] transition hover:text-white">
            <span x-text="open ? @js(__('Kevesebb')) : @js(__('Bővebben'))"></span>
            <svg class="h-4 w-4 transition-transform duration-300" :class="open && 'rotate-180'" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.22 7.22a.75.75 0 011.06 0L10 10.94l3.72-3.72a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.22 8.28a.75.75 0 010-1.06z" clip-rule="evenodd"/></svg>
        </button>
    @endif
</article>
