@extends('layouts.site')

@section('title', $discipline->name.' – '.$profile->name)

@section('content')
    @include('partials.page-head', ['label' => __('Szakma'), 'title' => $discipline->name, 'lead' => $discipline->description ?: $discipline->tagline])

    <div class="mx-auto max-w-7xl px-5 pb-4" data-reveal>
        <a href="{{ route('disciplines') }}" class="text-sm text-white/50 hover:text-white">← {{ __('Összes szakma') }}</a>
    </div>

    @if ($skillGroups->isNotEmpty())
        <section class="mx-auto max-w-7xl px-5 pt-8">
            <h2 class="section-title mb-6 text-3xl">{{ __('Képességek') }}</h2>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($skillGroups as $group) @include('partials.skill-group', ['group' => $group]) @endforeach
            </div>
        </section>
    @endif

    @if ($experiences->isNotEmpty())
        <section class="mx-auto max-w-4xl px-5 pt-14">
            <h2 class="section-title mb-6 text-3xl">{{ __('Tapasztalat') }}</h2>
            <div class="space-y-5">
                @foreach ($experiences as $exp) @include('partials.experience-item', ['exp' => $exp]) @endforeach
            </div>
        </section>
    @endif

    @if ($projects->isNotEmpty())
        <section class="mx-auto max-w-4xl px-5 pt-14">
            @include('partials.projects-modal', ['projects' => $projects, 'open' => request()->boolean('projektek')])
        </section>
    @endif
@endsection
