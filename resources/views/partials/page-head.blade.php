<div class="mx-auto max-w-7xl px-5 pb-10 pt-14 md:pt-20" data-reveal>
    <p class="label mb-4">{{ $label }}</p>
    <h1 class="page-title text-6xl sm:text-7xl md:text-8xl">{{ $title }}</h1>
    @isset($lead)
        <p class="mt-5 max-w-2xl text-lg leading-relaxed text-white/55">{{ $lead }}</p>
    @endisset
</div>
