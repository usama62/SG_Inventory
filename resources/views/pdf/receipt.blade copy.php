<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt - {{ $order->invoice_number ?? '' }}</title>
    <style>
        @page { margin: 20px; }
        body {
            font-family: "DejaVu Sans", "Arial", sans-serif;
            font-size: 13px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            border: 2px solid #000;
            padding: 15px;
            background: #fff;
        }
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        table.main {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.main td {
            padding: 8px 10px;
            vertical-align: top;
            border: 1px solid #000;
        }
        table.main td.label {
            font-weight: bold;
            width: 180px;
            background: #fff;
        }
        .amount-row, .payment-for-row {
            background: #fff !important;
        }
        .amount-text {
            font-size: 12px;
            font-style: italic;
            margin-top: 3px;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .stamp {
            border-top: 1px solid #000;
            padding-top: 5px;
            font-weight: bold;
            width: 200px;
            text-align: center;
        }
        .stamp img { width: 30%; height: auto; }
    </style>
</head>
@php
function numberToWords($num) {
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

    $num = number_format($num, 2, ".", "");
    $split = explode(".", $num);
    $integerPart = (int)$split[0];
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
@endphp

<body>
<div class="container">

    <!-- Header -->
    <div class="header">PAYMENT RECEIPT</div>

    <!-- Date & Receipt No -->
    <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:bold; margin-bottom:10px;">
        <div>DATE : {{ \Carbon\Carbon::parse($order->order_date)->format('d-M-Y') }}</div>
        <div>RECEIPT NO : {{ $order->invoice_number ?? 'N/A' }}</div>
    </div>

    <!-- Main Table -->
    <table class="main">
        <tr>
            <td class="label">Received From</td>
            <td>
                {{ $order->user->name ?? 'N/A' }}<br>
                <small>{{ $order->final_destination ?? $order->user->address ?? 'N/A' }}</small>
            </td>
        </tr>
        <tr class="amount-row">
            <td class="label">Amount</td>
            <td>
                <strong>{{ $company->currency->code ?? 'AED' }} {{ number_format($order->total ?? 0, 2) }}</strong>
                <div class="amount-text">
                    ({{ ucfirst($order->amount_in_words ?? numberToWords($order->total ?? 0)) }} only)

                </div>
            </td>
        </tr>
        <tr class="payment-for-row">
            <td class="label">Payment For</td>
            <td>{{ $order->notes ?? 'Advance payment for goods/services' }}</td>
        </tr>
        <tr>
            <td class="label">Received By</td>
            <td>{{ $staffMember->name ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="stamp">
            <img src="http://72.61.173.29/images/stamp.png" alt="Stamp" />
            <div>STAMP AND SIGNATURE</div>
        </div>
        <div style="width: 200px;"></div>
    </div>

</div>
</body>
</html>
