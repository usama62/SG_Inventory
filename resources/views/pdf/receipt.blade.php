<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt Confirmation</title>
    <style>
        @font-face {
            font-family: 'ARIALNB';
            src: url("{{ public_path('fonts/ARIALNB.TTF') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        /* DomPDF: avoid display:flex and position:absolute (can create blank pages) */
        @page {
            size: A4;
            margin: 10mm;
        }
        body {
            font-family: 'ARIALNB', Arial, sans-serif;
            margin: 0;
            padding: 0 10px 95px 10px;
        }
        .container {
            width: 100%;
            padding: 10px 0;
            box-sizing: border-box;
            text-align: left;
        }
        header {
            margin-bottom: 20px;
        }
        header img {
            width: 100%;
            max-height: 90px;
            height: auto;
            display: block;
        }
        .logo {
            font-size: 60px;
            font-weight: bold;
            color: #2b3a7a;
        }
        .logo .s {
            display: block;
            margin-bottom: -15px;
        }
        .logo .g {
            margin-left: -5px;
        }
        .company-info {
            text-align: right;
            width: 70%;
        }
        .company-name-ar {
            font-size: 24px;
            color: #2b3a7a;
            font-weight: bold;
            margin-bottom: 5px;
            direction: rtl;
        }
        .company-name-en {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #2b3a7a;
            padding-bottom: 3px;
        }
        h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: bold;
            font-family: 'ARIALNB', Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
            text-align: center;
        }
        td {
            border: 1px solid #000;
            padding: 10px;
            vertical-align: top;
            font-size: 14px;
            font-family: 'ARIALNB', Arial, sans-serif;
        }
        .label-cell {
            font-weight: bold;
            width: 40%;
            vertical-align: middle;
        }
        .received-from-label,
        .amount-label,
        .payment-against-label {
            text-align: center;
        }
        .investment-cell {
            vertical-align: middle;
        }
        .received-by-cell {
            padding: 10px;
            font-size: 14px;
            text-align: left;
        }
        .blank-row td {
            height: 200px;
            border-top: none;
        }
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            border-top: 4px double #2d2d2d;
            padding: 10px 10px 8px 10px;
            text-align: center;
            font-size: 13px;
            color: #515650;
            background: #fff;
        }
        footer p {
            margin: 0 0 4px 0;
            line-height: 1.35;
        }
        footer .footer-address {
            margin-top: 6px;
            font-size: 13px;
            line-height: 1.4;
        }
        .received-by-inner td {
            border: none;
            padding: 0;
            font-size: 14px;
        }
        .receipt-stamp-cell {
            border: none;
            padding: 45px 0 10px 0;
            vertical-align: top;
            text-align: left;
            min-height: 90px;
        }
        .receipt-stamp-cell img {
            width: 90px;
            height: 90px;
            margin-left: 70px;
            margin-top: 30px;
            display: block;
        }
    </style>
</head>
@php
    $payerName = optional($customer)->name ?? optional($order->user)->name ?? 'N/A';
    $paymentAgainst = $order->payment_terms
        ?? ($order->notes !== null && $order->notes !== '' ? $order->notes : '—');
    $receiptDate = \Carbon\Carbon::parse($order->order_date)->format('d-m-Y');
    $receivedByName = optional($staffMember)->name ?? '________________';
    $receivedByCompany = $company->name ?? 'SHAMS GLOBAL TRADING FZ LLC';

    $defaultCompanyAddress = "Al Fattan Plaza, Office no: 904, Al Garhood, Dubai, U.A.E";
    $companyAddressRaw = $company->address ?: $defaultCompanyAddress;
    $footerAddress = trim(preg_replace('/\s+/', ' ', str_replace(["\r\n", "\r", "\n"], ', ', $companyAddressRaw)));
    $footerCompanyName = $company->name ?: 'SHAMS GLOBAL TRADING FZ LLC';
    $footerWebsite = $company->website ?: 'https://shamsglobalfzllc.ae';
    $footerContactLine = 'Mob: ' . ($company->phone ?: '+97143358029')
        . ' | Email: ' . ($company->email ?: 'sufiyanjetham@shamsglobalfzllc.ae')
        . ' | Website: ' . $footerWebsite;
    $stampSrc = $stamp_src ?? null;

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
    <div class="container">
        <header>
            @if(!empty($receipt_logo_src))
                <img src="{{ $receipt_logo_src }}" alt="logo" />
            @endif
        </header>

        <table>
            <tr><td colspan="2"><span style="font-size: 30px; font-weight: bold;">PAYMENT RECEIPT CONFIRMATION</span></td></tr>
            <tr>
                <td class="label-cell">DATE: {{ $receiptDate }}</td>
                <td class="label-cell">RECEIPT NO: {{ $order->invoice_number ?? 'N/A' }}</td>
            </tr>
            <tr style="height: 60px;">
                <td class="label-cell">
                    <div class="received-from-label">PAYMENT RECEIVED FROM</div>
                </td>
                <td style="vertical-align: middle;">{{ $payerName }}</td>
            </tr>
            <tr style="height: 60px;">
                <td class="label-cell">
                    <div class="amount-label">Amount</div>
                </td>
                <td style="vertical-align: middle;">
                    {{ $displayAmountFigure }}<br>
                    {{ $displayAmountWordsLine }}
                </td>
            </tr>
            <tr>
                <td class="label-cell" style="vertical-align: middle;">
                    <div class="payment-against-label">Payment Against</div>
                </td>
                <td class="investment-cell">{{ $paymentAgainst }}</td>
            </tr>
            <tr>
                <td colspan="2" class="received-by-cell">
                    <table width="100%" cellspacing="0" cellpadding="0" class="received-by-inner">
                        <tr>
                            <td valign="top" colspan="2" style="text-align:left;">
                                <strong>RECEIVED BY:</strong><br>
                                {{ $receivedByName }}<br>
                                {{ $receivedByCompany }}
                            </td>
                        </tr>
                        @if(!empty($stampSrc))
                        <tr>
                            <td valign="top" align="left" colspan="2" class="receipt-stamp-cell">
                                <img src="{{ $stampSrc }}" alt="Company Stamp">
                            </td>
                        </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>

        <footer>
            <p>{{ $footerContactLine }}</p>
            <p class="footer-address"><strong>{{ $footerCompanyName }}</strong><br>{{ $footerAddress }}</p>
        </footer>
    </div>
</body>
</html>