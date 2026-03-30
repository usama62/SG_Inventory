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
        /* DomPDF: avoid display:flex on body (can hang); keep layout same visually */
        body {
            font-family: 'ARIALNB', Arial, sans-serif;
            margin: 0;
            text-align: center;
        }
        .container {
            display: inline-block;
            width: 100%;
            /* height: 11.69in; */
            padding: 10px;
            box-sizing: border-box;
            /* border: 1px solid #ccc; */
            position: relative;
            text-align: left;
            vertical-align: top;
        }
        header {
            margin-bottom: 20px;
        }
        header img {
            width: 100%;
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
            width: 100%;
            margin: 0 auto;
            border-top: 4px double #2d2d2d;
            padding-top: 10px;
            text-align: center;
            font-size: 16px;
            position: absolute;
            bottom: 0;
            /* left: 10%;
            right: 10%; */
            color: #515650;
        }
        footer p {
            margin: 0 0 5px 0;
            line-height: 1.4;
        }
    </style>
</head>
@php
    function receiptNumberToWords($num) {
        $ones = [
            0 => "", 1 => "One", 2 => "Two", 3 => "Three", 4 => "Four", 5 => "Five",
            6 => "Six", 7 => "Seven", 8 => "Eight", 9 => "Nine", 10 => "Ten",
            11 => "Eleven", 12 => "Twelve", 13 => "Thirteen", 14 => "Fourteen",
            15 => "Fifteen", 16 => "Sixteen", 17 => "Seventeen", 18 => "Eighteen", 19 => "Nineteen"
        ];
        $tens = [
            2 => "Twenty", 3 => "Thirty", 4 => "Forty", 5 => "Fifty",
            6 => "Sixty", 7 => "Seventy", 8 => "Eighty", 9 => "Ninety"
        ];
        if ($num == 0) return "Zero";
        $num = number_format((float) $num, 2, ".", "");
        $split = explode(".", $num);
        $integerPart = (int) $split[0];
        $words = "";
        $levels = ["", "Thousand", "Million", "Billion"];
        $i = 0;
        while ($integerPart > 0) {
            $chunk = $integerPart % 1000;
            if ($chunk) {
                $chunkWords = "";
                $hundreds = intval($chunk / 100);
                $remainder = $chunk % 100;
                if ($hundreds) {
                    $chunkWords .= $ones[$hundreds] . " Hundred ";
                }
                if ($remainder) {
                    if ($remainder < 20) {
                        $chunkWords .= $ones[$remainder] . " ";
                    } else {
                        $chunkWords .= $tens[intval($remainder / 10)] . " " . $ones[$remainder % 10] . " ";
                    }
                }
                $words = trim($chunkWords) . " " . $levels[$i] . " " . $words;
            }
            $integerPart = intval($integerPart / 1000);
            $i++;
        }
        return trim($words);
    }

    $currencyCode = $company->currency->code ?? 'AED';
    $currencyNamePlural = $currencyCode === 'AED'
        ? 'Dirhams'
        : (optional($company->currency)->name ?: 'Dirhams');
    $payerName = optional($customer)->name ?? optional($order->user)->name ?? 'N/A';
    $orderTotal = (float) ($order->total ?? 0);
    $amountWords = receiptNumberToWords($orderTotal);
    $amountFigure = $currencyCode . ' ' . number_format($orderTotal, 0, '.', ',');
    $amountWordsLine = '(' . $amountWords . ' ' . $currencyNamePlural . '.)';
    $paymentAgainst = $order->payment_terms
        ?? ($order->notes !== null && $order->notes !== '' ? $order->notes : '—');
    $receiptDate = \Carbon\Carbon::parse($order->order_date)->format('d-m-Y');
    $receivedByName = optional($staffMember)->name ?? '________________';
    $receivedByCompany = $company->name ?? 'SHAMS GLOBAL TRADING FZ LLC';

    $footerCompanyLine = ($company->name ?: 'SHAMS GLOBAL TRADING FZ LLC') . ' , '
        . ($company->address ?: 'BIZ00648, Compass Building, Al Shohada Road, Al Hamra Industrial Zone-FZ, Ras Al Khaimah, UAE');
    $footerLine1 = 'Mob: ' . ($company->phone ?: '+971 56 409 0798') . ', ' . $footerCompanyLine;
    $footerWebsite = $company->website ?: 'https://shamsglobalfzllc.ae';
    $footerLine2 = 'Email: ' . ($company->email ?: 'sufiyanjetham@shamsglobalfzllc.ae')
        . ' Website: ' . $footerWebsite;
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
                    {{ $amountFigure }}<br>
                    {{ $amountWordsLine }}
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
                    <strong>RECEIVED BY:</strong><br>
                    {{ $receivedByName }}<br>
                    {{ $receivedByCompany }}
                </td>
            </tr>
        </table>

        <footer>
            <p>Mob: +971 56 409 0798, SHAMS GLOBAL TRADING FZ LLC , BIZ00648, Compass Building, Al Shohada Road, Al Hamra Industrial Zone-FZ, Ras Al Khaimah, UAE</p>
            <p>Email: sufiyanjetham@shamsglobalfzllc.ae Website: https://shamsglobalfzllc</p>
        </footer>
    </div>
</body>
</html>