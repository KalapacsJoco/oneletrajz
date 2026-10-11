@extends('layouts.site')

@section('title', __('Képességek').' – '.$profile->name)

@section('content')
    @include('partials.page-head', ['label' => __('Képességek'), 'title' => __('Amit tudok')])
    @include('partials.filter', ['route' => 'skills'])

    @php
        $groupsByDiscipline = $skillGroups->groupBy('discipline_id');
        $blocks = $navDisciplines
            ->filter(fn ($d) => $groupsByDiscipline->has($d->id))
            ->map(fn ($d) => ['title' => trim($d->icon.' '.$d->name), 'groups' => $groupsByDiscipline[$d->id]])
            ->values();
        if ($groupsByDiscipline->has('')) {
            $blocks->push(['title' => __('Általános'), 'groups' => $groupsByDiscipline['']]);
        }
    @endphp

    @forelse ($blocks as $block)
        <section class="mx-auto max-w-7xl px-5 {{ $loop->first ? '' : 'mt-14' }}">
            <h2 class="section-title mb-6 text-2xl md:text-3xl" data-reveal>{{ $block['title'] }}</h2>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($block['groups'] as $group)
                    @include('partials.skill-group', ['group' => $group])
                @endforeach
            </div>
        </section>
    @empty
        <section class="mx-auto max-w-7xl px-5">
            <p class="text-white/50">{{ __('Ebben a kategóriában még nincs képesség.') }}</p>
        </section>
    @endforelse

    @if ($languages->isNotEmpty())
        <section class="mx-auto mt-14 max-w-7xl px-5">
            <h2 class="section-title mb-6 text-3xl">{{ __('Nyelvek') }}</h2>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($languages as $lang)
                    <div class="card p-6" data-reveal>
                        <div class="mb-3 flex justify-between"><span class="font-display text-lg font-semibold">{{ $lang->name }}</span><span class="text-sm text-white/50">{{ $lang->level_label }}</span></div>
                        <div class="h-1 overflow-hidden rounded-full bg-white/10"><div data-bar class="grad-bg h-full origin-left rounded-full" style="width: {{ $lang->level }}%"></div></div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection
