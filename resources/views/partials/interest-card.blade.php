<div class="card flex min-h-[18rem] flex-col p-7" data-reveal>
    <div class="relative mb-6 flex h-28 items-center justify-center overflow-hidden rounded-xl bg-white/[.03]" aria-hidden="true">
        @switch($item['theme'] ?? 'other')
            @case('chess')
                <div class="checker-mini absolute inset-0 opacity-55"></div>
                <span class="relative text-6xl text-white">♞</span>
                @break
            @case('math')
                <span class="grad-text font-serif text-3xl italic md:text-4xl">e<sup>iπ</sup>+1=0</span>
                @break
            @case('astronomy')
                <div class="absolute h-24 w-24 rounded-full border border-dashed border-white/25"><span class="absolute left-1/2 top-0 h-2.5 w-2.5 -translate-x-1/2 -translate-y-1/2 rounded-full" style="background: var(--accent-to)"></span></div>
                <div class="absolute h-14 w-14 rounded-full border border-white/20"><span class="absolute bottom-0 left-1/2 h-2 w-2 -translate-x-1/2 translate-y-1/2 rounded-full" style="background: var(--accent-from)"></span></div>
                <span class="relative text-3xl">☉</span>
                @break
            @case('history')
                <span class="grad-text font-display text-3xl font-bold tracking-widest">S·P·Q·R</span>
                @break
            @case('music')
                <div class="absolute inset-x-8 top-1/2 -translate-y-1/2 space-y-2 opacity-40">
                    @for ($i = 0; $i < 5; $i++)<div class="h-px bg-white"></div>@endfor
                </div>
                <span class="relative text-6xl grad-text">𝄞</span>
                <span class="relative ml-3 text-4xl text-white/80">♪</span>
                @break
            @default
                <span class="text-5xl">✦</span>
        @endswitch
    </div>
    <h3 class="font-display text-xl font-semibold">{{ $item['title'] ?? '' }}</h3>
    <p class="mt-3 text-sm leading-relaxed text-white/60">{{ $item['text'] ?? '' }}</p>
</div>
