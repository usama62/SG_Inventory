@php
    $dompdfFontsDir = \App\Classes\Common::dompdfFontsPath();
    $arialBoldFont = $dompdfFontsDir . DIRECTORY_SEPARATOR . 'Arial-Bold.ttf';
    $geSsTvFont = $dompdfFontsDir . DIRECTORY_SEPARATOR . 'GE-SS-TV-Bold.ttf';
@endphp
@if(is_readable($arialBoldFont))
@font-face {
    font-family: 'Arial Bold';
    src: url("{{ $arialBoldFont }}") format('truetype');
    font-weight: bold;
    font-style: normal;
}
@endif
@if(is_readable($geSsTvFont))
@font-face {
    font-family: 'GE SS TV';
    src: url("{{ $geSsTvFont }}") format('truetype');
    font-weight: bold;
    font-style: normal;
}
@endif

@page {
    size: A4 portrait;
    margin: 0 0 22mm 0;
}

* { box-sizing: border-box; }

html, body {
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Arial Bold', 'DejaVu Sans', Arial, sans-serif;
    font-weight: bold;
    font-size: 12px;
    line-height: 1.35;
    color: #111;
}

.shams-header-banner {
    width: 100%;
    max-width: 100%;
    display: block;
    height: auto;
    margin: 0;
    padding: 0;
    border: 0;
}

.shams-header,
.shams-header td {
    border: 0;
    border-collapse: collapse;
    vertical-align: middle;
}

.shams-header .gold-bar {
    background: #C59D5F;
    height: 11px;
    line-height: 0;
    font-size: 0;
}

.shams-header .header-left {
    background: #fff;
    padding: 10px 10px 12px 16px;
}

.shams-header .header-right {
    background: #231F20;
    padding: 10px 16px 10px 10px;
}

.shams-header .logo-cell {
    width: 86px;
    padding-right: 12px;
}

.shams-header .logo-cell img {
    width: 80px;
    display: block;
}

.shams-header .name-ar {
    font-family: 'GE SS TV', 'DejaVu Sans', sans-serif;
    font-size: 11px;
    font-weight: bold;
    color: #000;
    direction: rtl;
    text-align: left;
    line-height: 1.25;
    margin-bottom: 3px;
}

.shams-header .name-main {
    font-family: 'Arial Bold', 'DejaVu Sans', Arial, sans-serif;
    font-size: 24px;
    font-weight: bold;
    color: #000;
    line-height: 1;
    letter-spacing: 0.4px;
    margin-bottom: 2px;
}

.shams-header .name-sub {
    font-family: 'Arial Bold', 'DejaVu Sans', Arial, sans-serif;
    font-size: 11px;
    font-weight: bold;
    color: #000;
    letter-spacing: 3.6px;
    line-height: 1.1;
}

.shams-header .contact-table td {
    color: #C59D5F;
    font-family: 'Arial Bold', 'DejaVu Sans', Arial, sans-serif;
    font-size: 9px;
    font-weight: bold;
    line-height: 1.45;
    padding: 1px 0;
    vertical-align: top;
    border: 0;
}

.shams-header .contact-table .icon-cell {
    width: 18px;
    padding-right: 6px;
}

.shams-header .contact-table .icon-cell img {
    width: 13px;
    height: 13px;
    display: block;
}

.shams-header .contact-table .addr-indent {
    padding-left: 19px;
}

.doc-body {
    padding: 8px 12mm 0 12mm;
}

.doc-footer {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    padding: 0 12mm 6mm 12mm;
    text-align: center;
    background: #fff;
}

.doc-footer .footer-rule {
    border-top: 3px double #C59D5F;
    margin: 0 0 6px 0;
    height: 0;
    line-height: 0;
}

.doc-footer p {
    margin: 0 0 3px 0;
    font-size: 9px;
    font-weight: bold;
    color: #C59D5F;
    line-height: 1.25;
}
