<article class="card group flex h-full flex-col overflow-hidden" @if ($reveal ?? true) data-reveal @endif>
    @php $images = $project->getMedia('image')->map(fn ($m) => $m->getUrl('web'))->values(); @endphp
    <div class="relative aspect-[16/10] overflow-hidden bg-[#141416]"
         @if ($images->count() > 1)
         x-data="{
             i: 0, n: {{ $images->count() }}, timer: null,
             go(to) { this.i = (to + this.n) % this.n; this.restart(); },
             restart() { clearInterval(this.timer); this.timer = setInterval(() => { this.i = (this.i + 1) % this.n }, 5000); },
             init() { this.restart(); }
         }"
         x-on:keydown.left.prevent="go(i - 1)" x-on:keydown.right.prevent="go(i + 1)" tabindex="0"
         role="group" aria-roledescription="carousel" aria-label="{{ $project->title }}"
         @endif>
        @if ($images->count() > 1)
            @foreach ($images as $k => $img)
                <img src="{{ $img }}" alt="{{ $project->title }} {{ $k + 1 }}" loading="lazy" x-show="i === {{ $k }}" x-transition.opacity.duration.400ms @if ($k > 0) x-cloak @endif class="absolute inset-0 h-full w-full object-cover">
            @endforeach
            <button type="button" @click="go(i - 1)" class="absolute left-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full bg-black/55 text-lg text-white backdrop-blur transition hover:bg-[var(--accent-from)]" aria-label="{{ __('Előző') }}">‹</button>
            <button type="button" @click="go(i + 1)" class="absolute right-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full bg-black/55 text-lg text-white backdrop-blur transition hover:bg-[var(--accent-from)]" aria-label="{{ __('Következő') }}">›</button>
            <div class="absolute inset-x-0 bottom-2 flex justify-center gap-1.5">
                @foreach ($images as $k => $img)
                    <button type="button" @click="go({{ $k }})" class="h-2 w-2 rounded-full transition" :class="i === {{ $k }} ? 'bg-[var(--accent-from)]' : 'bg-white/50'" aria-label="{{ $k + 1 }}"></button>
                @endforeach
            </div>
        @elseif ($images->count() === 1)
            <img src="{{ $images[0] }}" alt="{{ $project->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
        @else
            <div class="relative flex h-full w-full items-center justify-center overflow-hidden">
                <div class="checker-mini absolute inset-0 opacity-20"></div>
                <span class="relative font-impact text-7xl text-[var(--accent-from)]">{{ mb_strtoupper(mb_substr($project->title, 0, 1)) }}</span>
            </div>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-6">
        <p class="label">{{ $project->subtitle ?: ($project->discipline?->name ?? __('Projekt')) }}</p>
        <h3 class="mt-2 font-display text-xl font-semibold">{{ $project->title }}</h3>
        <p class="mt-3 flex-1 text-sm leading-relaxed text-white/55">{{ $project->description }}</p>
        <div class="mt-5 flex flex-wrap gap-2">
            @foreach ($project->tags ?? [] as $tag) <span class="chip">{{ $tag }}</span> @endforeach
        </div>
        @if ($project->url || $project->repo_url)
            <div class="mt-5 flex gap-5 text-sm font-medium">
                @if ($project->url) <a href="{{ $project->url }}" target="_blank" rel="noopener" class="red-text                 hover:underline">{{ __('Megnézem') }} ↗</a> @endif
                                @if ($project->repo_url) <a href="{{ $project->repo_url }}" target="_blank" rel="noopener" class="text-white/55 hover:text-white">{{ __('Kód') }} ↗</a> @endif
            </div>
        @endif
    </div>
</article>
