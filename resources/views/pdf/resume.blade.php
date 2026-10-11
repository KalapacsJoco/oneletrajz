<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ $profile->name }} – {{ __('Önéletrajz') }}</title>
    <style>
        @page { margin: 30pt 34pt; }
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { margin: 0; color: #222; font-size: 8.4pt; line-height: 1.45; }
        h1 { margin: 0; font-size: 19pt; text-transform: uppercase; letter-spacing: .5pt; }
        .headline { margin-top: 2pt; font-size: 8.5pt; color: #b30a14; }
        .contact { text-align: right; font-size: 7.2pt; line-height: 1.6; }
        .top { border-bottom: 2pt solid #b30a14; padding-bottom: 8pt; margin-bottom: 10pt; }
        h2 { margin: 0 0 4pt; padding-bottom: 2pt; border-bottom: .8pt solid #b30a14; font-size: 9pt; text-transform: uppercase; color: #b30a14; letter-spacing: .3pt; }
        table { border-collapse: collapse; }
        td { vertical-align: top; padding: 0; }
        .period { width: 80pt; color: #555; font-size: 7.6pt; padding-right: 8pt; white-space: nowrap; }
        .row td { padding-bottom: 7pt; }
        .pos { font-weight: bold; font-size: 9pt; }
        .meta { color: #555; font-size: 7.6pt; }
        .txt p, .txt ul, .txt ol { margin: 1.5pt 0 0; }
        .txt ul, .txt ol { padding-left: 10pt; }
        .group { margin-bottom: 3pt; }
        .group b { color: #b30a14; font-size: 6.9pt; text-transform: uppercase; }
    </style>
</head>
<body>
@php
    $allowed = '<p><br><ul><ol><li><strong><b><em><i>';
    $locale = app()->getLocale();
    $monthFormat = $locale === 'hu' ? 'Y. m.' : 'm.Y';
    $birth = $profile->birth_date?->format($locale === 'hu' ? 'Y. m. d.' : 'd.m.Y');
@endphp
    <table class="top" width="527">
        <tr>
            @if ($photo)
                <td width="70" style="padding-right: 12pt"><img src="{{ $photo }}" width="60" height="60" alt=""></td>
            @endif
            <td>
                <h1>{{ $profile->name }}</h1>
                @if (filled($profile->headline))<div class="headline">{{ $profile->headline }}</div>@endif
            </td>
            <td class="contact" width="200">
                @if ($profile->location){{ $profile->location }}<br>@endif
                @if ($birth){{ __('Születési dátum') }}: {{ $birth }}<br>@endif
                @if ($profile->phone){{ $profile->phone }}<br>@endif
                @if ($profile->email){{ $profile->email }}@endif
            </td>
        </tr>
    </table>

    @if ($experiences->isNotEmpty())
        <h2>{{ __('Tapasztalat') }}</h2>
        <table width="527" style="margin-bottom: 6pt">
            @foreach ($experiences as $x)
                <tr class="row">
                    <td class="period">{{ $x->start_date->format($monthFormat) }} – {{ $x->is_current || ! $x->end_date ? __('Jelenleg') : $x->end_date->format($monthFormat) }}</td>
                    <td>
                        <span class="pos">{{ $x->position }}</span>
                        <span class="meta"> · {{ $x->company }}@if ($x->location), {{ $x->location }}@endif</span>
                        <div class="txt">
                            @if (filled($x->details)){!! strip_tags($x->details, $allowed) !!}
                            @elseif (filled($x->description))<p>{{ $x->description }}</p>@endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    <table width="527">
        <tr>
            <td width="250">
                @if ($education->isNotEmpty())
                    <h2>{{ __('Tanulmányok') }}</h2>
                    @foreach ($education as $e)
                        <div style="margin-bottom: 5pt">
                            <span class="pos">{{ $e->degree }}</span>
                            <div class="meta">{{ $e->school }}@if ($e->location), {{ $e->location }}@endif · {{ $e->start_year }}@if ($e->end_year) – {{ $e->end_year }}@endif</div>
                        </div>
                    @endforeach
                @endif
            </td>
            <td width="27"></td>
            <td width="250">
                @if ($skillGroups->isNotEmpty())
                    <h2>{{ __('Képességek') }}</h2>
                    @foreach ($skillGroups as $g)
                        <div class="group"><b>{{ $g->name }}:</b> {{ $g->skills->pluck('name')->implode(', ') }}</div>
                    @endforeach
                @endif

                @if ($languages->isNotEmpty())
                    <div class="group"><b>{{ __('Nyelvek') }}:</b> {{ $languages->map(fn ($l) => $l->name.(filled($l->level_label) ? ' ('.$l->level_label.')' : ''))->implode(', ') }}</div>
                @endif
                @if (filled($profile->driving_license))
                    <div class="group"><b>{{ __('Jogosítvány') }}:</b> {{ $profile->driving_license }}</div>
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
