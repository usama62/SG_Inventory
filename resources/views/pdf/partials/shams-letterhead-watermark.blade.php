@php
    $letterhead = $letterhead ?? \App\Classes\Common::buildShamsLetterheadContext($company ?? null);
    $watermarkSrc = $letterhead['watermarkSrc'] ?? null;
@endphp

@if(!empty($watermarkSrc))
    <div class="page-watermark-back">
        <img src="{{ $watermarkSrc }}" width="390" alt="">
    </div>
@endif
