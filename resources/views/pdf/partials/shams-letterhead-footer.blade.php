@php
    $letterhead = $letterhead ?? \App\Classes\Common::buildShamsLetterheadContext($company ?? null);
    extract($letterhead, EXTR_SKIP);
@endphp

<div class="doc-footer">
    <div class="footer-rule"></div>
    <p>Mob: {{ $footerPhone }} | Email: {{ $footerEmail }} | Website: {{ $footerWebsite }}</p>
    <p><strong>{{ strtoupper($footerCompanyName) }}</strong></p>
    <p>{{ $footerAddress }}</p>
</div>
