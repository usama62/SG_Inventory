<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt Confirmation</title>
    <style>
        @include('pdf.partials.shams-letterhead-styles')
        @include('pdf.partials.shams-watermark-overlay-styles')
        @include('pdf.partials.shams-transparent-cells')

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .receipt-table td {
            border: 1px solid #111;
            padding: 8px 10px;
            vertical-align: middle;
            font-size: 12px;
            text-align: center;
        }

        .receipt-table .label-cell { width: 40%; }

        .receipt-table .title-row td {
            font-size: 22px;
            padding: 10px 8px;
        }

        .received-by-cell {
            padding: 10px 12px;
            text-align: left;
        }

        .received-by-inner,
        .received-by-inner td {
            border: none;
            padding: 0;
            text-align: left;
            background-color: transparent;
        }

        .receipt-stamp-cell {
            padding: 16px 0 0 0;
        }

        .receipt-stamp-cell img {
            width: 80px;
            height: 80px;
            margin-left: 72px;
            display: block;
        }

        .receipt-signature-block {
            padding: 0 0 8px 72px;
            text-align: left;
        }

        .receipt-signature-room {
            height: 36px;
        }

        .receipt-signature-label {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .receipt-signature-line {
            border-bottom: 1px solid #111;
            width: 180px;
            margin: 0 0 4px 0;
        }

        .receipt-signature-date {
            font-size: 10px;
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
    $stampSrc = $stamp_src ?? \App\Classes\Common::getCompanyStampDataUri($company);

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

    @include('pdf.partials.shams-letterhead-open')
    @include('pdf.partials.shams-letterhead-watermark')

    <div class="doc-body">
        <table class="receipt-table wm-table" cellspacing="0" cellpadding="0">
            <tr class="title-row">
                <td colspan="2">PAYMENT RECEIPT CONFIRMATION</td>
            </tr>
            <tr>
                <td class="label-cell">DATE: {{ $receiptDate }}</td>
                <td class="label-cell">RECEIPT NO: {{ $order->invoice_number ?? 'N/A' }}</td>
            </tr>
            <tr style="height: 48px;">
                <td class="label-cell">PAYMENT RECEIVED FROM</td>
                <td>{{ $payerName }}</td>
            </tr>
            <tr style="height: 48px;">
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
                        <tr>
                            <td class="receipt-signature-block">
                                @if(!empty($stampSrc))
                                <div class="receipt-signature-room"></div>
                                @endif
                                <div class="receipt-signature-label">SIGNATURE &amp; DATE:</div>
                                <div class="receipt-signature-line"></div>
                                <div class="receipt-signature-date">{{ $receiptDate }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    @include('pdf.partials.shams-letterhead-footer')

</body>
</html>
