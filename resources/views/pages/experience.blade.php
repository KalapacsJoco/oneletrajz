@extends('layouts.site')

@section('title', __('Tapasztalat').' – '.$profile->name)

@section('content')
    @include('partials.page-head', ['label' => __('Tapasztalat'), 'title' => __('Eddigi utam')])
    @include('partials.filter', ['route' => 'experience'])

    <section class="mx-auto grid max-w-7xl gap-10 px-5 lg:grid-cols-[1.6fr_1fr]">
        <div class="space-y-5">
            @forelse ($experiences as $exp)
                @include('partials.experience-item', ['exp' => $exp])
            @empty
                <p class="text-white/50">{{ __('Ebben a kategóriában még nincs bejegyzés.') }}</p>
            @endforelse
        </div>

        <aside>
            <h2 class="section-title mb-5 text-2xl">{{ __('Tanulmányok') }}</h2>
            <div class="space-y-4">
                @foreach ($education as $ed)
                    <div class="card p-6" data-reveal>
                        <h3 class="font-display font-semibold">{{ $ed->degree }}</h3>
                        <p class="mt-1 text-sm text-white/65"><span class="red-text">{{ $ed->school }}</span>@if ($ed->location) · {{ $ed->location }}@endif</p>
                        @if ($ed->start_year || $ed->end_year)
                            <p class="mt-2 text-xs text-white/40">{{ $ed->start_year }}{{ $ed->start_year && $ed->end_year ? ' – ' : '' }}{{ $ed->end_year }}</p>
                        @endif
                        @if ($ed->description) <p class="mt-3 text-sm text-white/55">{{ $ed->description }}</p> @endif
                    </div>
                @endforeach
            </div>
        </aside>
    </section>
@endsection
