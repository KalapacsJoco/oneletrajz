@extends('layouts.site')

@section('title', ($profile->interests_title ?: __('Szenvedélyek')).' – '.$profile->name)

@section('content')
    @include('partials.page-head', ['label' => __('Szenvedélyek'), 'title' => $profile->interests_title ?: __('Ami hajt')])

    <section class="mx-auto grid max-w-7xl gap-5 px-5 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($profile->interests ?: [] as $item)
            @include('partials.interest-card', ['item' => $item])
        @empty
            <p class="text-white/50">{{ __('Add meg az érdeklődési köröket az admin felületen.') }}</p>
        @endforelse
    </section>
@endsection
