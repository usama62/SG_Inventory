<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt Confirmation</title>
    <style>
        @font-face {
            font-family: 'Arial Bold';
            src: url("{{ public_path('fonts/Arial-Bold.ttf') }}") format('truetype'),
                 url("{{ storage_path('fonts/ARIALBD.TTF') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @font-face {
            font-family: 'GE SS TV';
            src: url("{{ public_path('fonts/GE-SS-TV-Bold.ttf') }}") format('truetype'),
                 url("{{ storage_path('fonts/GE-SS-TV-Bold.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @page {
            size: A4 portrait;
            margin: 0 0 32mm 0;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0 0 34mm 0;
            font-family: 'Arial Bold', Arial, sans-serif;
            font-weight: bold;
            font-size: 12px;
            line-height: 1.35;
            color: #111;
        }

        .shams-header-banner {
            width: 100%;
            display: block;
            height: auto;
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
            font-family: 'Arial Bold', Arial, sans-serif;
            font-size: 24px;
            font-weight: bold;
            color: #000;
            line-height: 1;
            letter-spacing: 0.4px;
            margin-bottom: 2px;
        }

        .shams-header .name-sub {
            font-family: 'Arial Bold', Arial, sans-serif;
            font-size: 11px;
            font-weight: bold;
            color: #000;
            letter-spacing: 3.6px;
            line-height: 1.1;
        }

        .shams-header .contact-table td {
            color: #C59D5F;
            font-family: 'Arial Bold', Arial, sans-serif;
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

        .page-watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            width: 520px;
            max-width: 78%;
            text-align: center;
        }

        .page-watermark img {
            width: 100%;
            height: auto;
            display: block;
        }

        .shams-header-banner,
        .receipt-body,
        .doc-footer {
            position: relative;
            z-index: 1;
        }

        .receipt-body {
            padding: 18px 15mm 0 15mm;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .receipt-table td {
            border: 1px solid #111;
            padding: 10px 12px;
            vertical-align: middle;
            font-size: 13px;
            text-align: center;
            background-color: #fff;
        }

        .receipt-table .label-cell { width: 40%; }

        .receipt-table .title-row td {
            font-size: 26px;
            padding: 14px 10px;
        }

        .received-by-cell {
            padding: 12px 14px;
            text-align: left;
        }

        .received-by-inner,
        .received-by-inner td {
            border: none;
            padding: 0;
            text-align: left;
        }

        .receipt-stamp-cell {
            padding: 42px 0 6px 0;
        }

        .receipt-stamp-cell img {
            width: 90px;
            height: 90px;
            margin-left: 72px;
            margin-top: 28px;
            display: block;
        }

        .doc-footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            padding: 0 15mm 10mm 15mm;
            text-align: center;
            background: #fff;
        }

        .doc-footer .footer-rule {
            border-top: 3px double #C59D5F;
            margin: 0 0 9px 0;
        }

        .doc-footer p {
            margin: 0 0 5px 0;
            font-size: 9.5px;
            font-weight: bold;
            color: #C59D5F;
        }
    </style>
</head>
@php
    $payerName = optional($customer)->name ?? optional($order->user)->name ?? 'N/A';
    $paymentAgainst = $order->payment_terms
        ?? ($order->notes !== null && $order->notes !== '' ? $order->notes : '—');
    $receiptDate = \Carbon\Carbon::parse($order->order_date)->format('d-m-Y');
    $receivedByName = optional($staffMember)->name ?? '________________';
    $receivedByCompany = $company->name ?? 'SHAMS UNIVERSAL TRADING FZ-LLC';

    $defaultCompanyAddress = 'Al Fattan Plaza, Office # 904, Office Building, Al Garhood, Dubai-U.A.E';
    $companyAddressRaw = $company->address ?: $defaultCompanyAddress;
    $footerAddress = trim(preg_replace('/\s+/', ' ', str_replace(["\r\n", "\r", "\n"], ', ', $companyAddressRaw)));
    $footerCompanyName = $company->name ?: 'SHAMS UNIVERSAL TRADING FZ-LLC';
    $footerWebsite = $company->website ?: 'https://shamsglobalfzllc.ae';
    $footerWebsiteDisplay = preg_replace('#^https?://#', '', rtrim($footerWebsite, '/'));
    $footerPhone = $company->phone ?: '+971 56 409 0798';
    $footerEmail = $company->email ?: 'sufiyanjetham@shamsglobalfzllc.ae';

    $logoSrc = $receipt_logo_src ?? \App\Classes\Common::getReceiptLogoDataUri($company);
    $stampSrc = $stamp_src ?? null;
    $headerBannerSrc = $header_banner_src ?? \App\Classes\Common::getShamsHeaderBannerDataUri();
    $watermarkSrc = $watermark_src ?? \App\Classes\Common::getAssetDataUri(public_path('images/shams-watermark.png'));

    $companyNameAr = 'شمس يونيفرسال تريدينغ ش.م.ح-ذ.م.م';
    $companyNameMain = 'SHAMS UNIVERSAL';
    $companyNameSub = 'T R A D I N G F Z - L L C';
    $headerPhoneLandline = '+971 4 335 8029';
    $headerPhoneMobile = $footerPhone;
    $headerEmail = $footerEmail;
    $headerWebsiteDisplay = $footerWebsiteDisplay;
    $headerAddressLine1 = 'Al Fattan Plaza, Office # 904';
    $headerAddressLine2 = 'Office Building, Al Garhood, Dubai-U.A.E';

    $iconPhoneSrc = \App\Classes\Common::getAssetDataUri(public_path('images/icon-phone-gold.svg'));
    $iconMobileSrc = \App\Classes\Common::getAssetDataUri(public_path('images/icon-mobile-gold.svg'));
    $iconEmailSrc = \App\Classes\Common::getAssetDataUri(public_path('images/icon-email-gold.svg'));
    $iconWebSrc = \App\Classes\Common::getAssetDataUri(public_path('images/icon-web-gold.svg'));
    $iconLocationSrc = \App\Classes\Common::getAssetDataUri(public_path('images/icon-location-gold.svg'));

    $displayAmountFigure = $amount_figure
        ?? \App\Classes\Common::formatAmountByCurrencyCode(
            (float) ($order->total ?? 0),
            \App\Classes\Common::resolveOrderCurrency($order, $order->order_type ?? 'sales'),
            0
        );
    $displayAmountWordsLine = $amount_words_line
        ?? (\App\Classes\Common::buildReceiptAmounts($order)['amount_words_line'] ?? '');
@endphp
<body>

    @if(!empty($watermarkSrc))
        <div class="page-watermark">
            <img src="{{ $watermarkSrc }}" alt="">
        </div>
    @endif

    @if(!empty($headerBannerSrc))
        <img class="shams-header-banner" src="{{ $headerBannerSrc }}" alt="Shams Header">
    @else
        @include('pdf.partials.shams-header')
    @endif

    <div class="receipt-body">
        <table class="receipt-table" cellspacing="0" cellpadding="0">
            <tr class="title-row">
                <td colspan="2">PAYMENT RECEIPT CONFIRMATION</td>
            </tr>
            <tr>
                <td class="label-cell">DATE: {{ $receiptDate }}</td>
                <td class="label-cell">RECEIPT NO: {{ $order->invoice_number ?? 'N/A' }}</td>
            </tr>
            <tr style="height: 58px;">
                <td class="label-cell">PAYMENT RECEIVED FROM</td>
                <td>{{ $payerName }}</td>
            </tr>
            <tr style="height: 58px;">
                <td class="label-cell">Amount</td>
                <td>
                    {{ $displayAmountFigure }}<br>
                    {{ $displayAmountWordsLine }}
                </td>
            </tr>
            <tr>
                <td class="label-cell">Payment Against</td>
                <td>{{ $paymentAgainst }}</td>
            </tr>
            <tr>
                <td colspan="2" class="received-by-cell">
                    <table width="100%" cellspacing="0" cellpadding="0" class="received-by-inner">
                        <tr>
                            <td>
                                <strong>RECEIVED BY:</strong><br>
                                {{ $receivedByName }}<br>
                                {{ $receivedByCompany }}
                            </td>
                        </tr>
                        @if(!empty($stampSrc))
                        <tr>
                            <td class="receipt-stamp-cell">
                                <img src="{{ $stampSrc }}" alt="Company Stamp">
                            </td>
                        </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="doc-footer">
        <div class="footer-rule"></div>
        <p>Mob: {{ $footerPhone }} | Email: {{ $footerEmail }} | Website: {{ $footerWebsite }}</p>
        <p><strong>{{ strtoupper($footerCompanyName) }}</strong></p>
        <p>{{ $footerAddress }}</p>
    </div>

</body>
</html>
