@php
    $letterhead = $letterhead ?? \App\Classes\Common::buildShamsLetterheadContext($company ?? null);
    extract($letterhead, EXTR_SKIP);
@endphp

@if(!empty($headerBannerSrc))
    <img class="shams-header-banner" src="{{ $headerBannerSrc }}" width="595" alt="Shams Header">
@else
    @include('pdf.partials.shams-header')
@endif
