@extends('layouts.site')

@section('title', 'Projektek – '.$profile->name)

@section('content')
    @include('partials.page-head', ['label' => 'Projektek', 'title' => 'Munkáim'])
    @include('partials.filter', ['route' => 'projects'])

    <section class="mx-auto grid max-w-7xl gap-5 px-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($projects as $project)
            @include('partials.project-card', ['project' => $project])
        @empty
            <p class="text-white/50">Ebben a kategóriában még nincs projekt.</p>
        @endforelse
    </section>
@endsection
