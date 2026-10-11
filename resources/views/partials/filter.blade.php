@if ($navDisciplines->count() > 1)
    <div class="mx-auto flex max-w-7xl flex-wrap gap-2 px-5 pb-8" data-reveal>
        <a href="{{ route($route) }}" class="chip {{ $current ? '' : 'chip-active' }}">{{ __('Mind') }}</a>
        @foreach ($navDisciplines as $d)
            <a href="{{ route($route, ['szakma' => $d->slug]) }}" class="chip {{ $current?->id === $d->id ? 'chip-active' : '' }}">{{ $d->icon }} {{ $d->name }}</a>
        @endforeach
    </div>
@endif
