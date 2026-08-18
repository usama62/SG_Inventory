<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <title>Proforma Invoice</title>
  <style>
    @include('pdf.partials.shams-letterhead-styles')
    @include('pdf.partials.shams-watermark-overlay-styles')
    @include('pdf.partials.shams-transparent-cells')

    .doc-title {
      text-align: center;
      font-weight: bold;
      font-size: 16px;
      text-transform: uppercase;
      margin: 0 0 6px 0;
    }

    .info-table, .product-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10.5px;
      margin-top: 6px;
    }

    .info-table td {
      border: 1px solid #111;
      padding: 4px 5px;
      vertical-align: top;
    }

    .info-table .label {
      font-weight: bold;
      width: 150px;
      white-space: nowrap;
    }

    .info-table .value { width: 25%; }

    .product-table th {
      background: #111;
      color: #fff;
      padding: 4px 3px;
      font-weight: bold;
      font-size: 9px;
      text-align: center;
      border: 1px solid #111;
    }

    .product-table td {
      border: 1px solid #111;
      padding: 4px 3px;
      text-align: center;
      font-size: 10px;
    }

    .product-table .desc { text-align: left; padding-left: 8px; }

    .product-table .total-row td {
      font-weight: bold;
    }

    .amount-words {
      margin: 6px 0;
      font-weight: bold;
      font-size: 10px;
      text-align: left;
      line-height: 1.3;
    }

    .bank, .declaration { margin-top: 6px; font-size: 10px; line-height: 1.3; }

    .bank strong, .declaration strong { display: block; margin-bottom: 3px; }

    .bank td { padding: 1px 0; border: none; background-color: transparent; }

    .signature-stamp-block {
      margin-top: 10px;
      text-align: right;
    }

    .invoice-stamp {
      margin-bottom: 0;
      text-align: right;
    }

    .invoice-stamp img {
      width: 75px;
      height: 75px;
      display: inline-block;
    }

    .signature-room {
      height: 6px;
    }

    .signature {
      text-align: right;
      font-size: 10px;
    }

    .signature-image-wrap {
      width: 180px;
      height: 48px;
      margin: 2px 0 -2px auto;
      text-align: left;
    }

    .signature-handwritten {
      width: 118px;
      height: auto;
      display: block;
      margin: 0 0 0 12px;
    }

    .signature-line {
      border-bottom: 1px solid #111;
      width: 180px;
      margin: 0 0 2px auto;
    }
  </style>
</head>
@php
function numberToWords($num)
{
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

            if ($hundreds > 0) {
                $chunkWords .= $ones[$hundreds] . " Hundred ";
            }

            if ($remainder > 0) {
                if ($remainder < 20) {
                    $chunkWords .= $ones[$remainder] . " ";
                } else {
                    $chunkWords .= $tens[intval($remainder / 10)] . " ";
                    $chunkWords .= $ones[$remainder % 10] . " ";
                }
            }

            $words = $chunkWords . $levels[$i] . " " . $words;
        }

        $integerPart = intval($integerPart / 1000);
        $i++;
    }

    return trim($words) . " Only";
}

    $orderCurrency = \App\Classes\Common::resolveOrderCurrency($order, $order->order_type ?? 'sales');
    $orderTotalNative = (float) ($order->total ?? 0);
    $orderTotalAed = \App\Classes\Common::convertProductPriceToAed($orderTotalNative, $orderCurrency);
    $orderTotalUsd = \App\Classes\Common::convertProductPriceToUsd($orderTotalNative, $orderCurrency);
    $stampSrc = $stamp_src ?? \App\Classes\Common::getCompanyStampDataUri($company);
    $signatureSrc = $signature_src ?? \App\Classes\Common::getInvoiceSignatureDataUri();

    $shipperName = optional($warehouse)->name ?? '';
    $invoiceNo = $order->invoice_number ?? '';
    $invoiceDate = $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') : '';
    $invoiceNoAndDate = trim($invoiceNo . ($invoiceDate ? ' / ' . $invoiceDate : ''));

    $contactNo = optional($warehouse)->phone
        ?? optional($company)->phone
        ?? '+97143358029';
    $termsOfDelivery = optional($customer)->terms_of_delivery ?? ($order->terms_of_delivery ?? '');

    $buyer = optional($customer)->name ?? ($order->buyer_name ?? '');
    $paymentTerms = optional($customer)->payment_terms ?? ($order->payment_terms ?? '');

    $address = optional($customer)->address ?? ($order->address ?? '');
    $countryOfOrigin = optional($customer)->country_of_origin_of_goods ?? ($order->country_of_origin_of_goods ?? '');

    $marksNos = optional($customer)->marks_and_nos ?? ($order->marks_and_nos ?? '');
    $finalDestination = optional($customer)->final_destination ?? ($order->final_destination ?? '');
@endphp
<body>

@include('pdf.partials.shams-letterhead-open')
@include('pdf.partials.shams-letterhead-watermark')

<div class="doc-body">

  <div class="doc-title">PROFORMA INVOICE</div>

  <table class="info-table wm-table" cellspacing="0" cellpadding="0">
    <tr>
      <td><span class="label">SHIPPER:</span></td>
      <td class="value">{{ $shipperName }}</td>
      <td><span class="label">INVOICE NO. & DATE:</span></td>
      <td class="value">{{ $invoiceNoAndDate }}</td>
    </tr>
    <tr>
      <td><span class="label">Contact No#:</span></td>
      <td class="value">{{ $contactNo }}</td>
      <td><span class="label">TERMS OF DELIVERY:</span></td>
      <td class="value">{{ $termsOfDelivery }}</td>
    </tr>
    <tr>
      <td><span class="label">BUYER:</span></td>
      <td class="value">{{ $buyer }}</td>
      <td><span class="label">PAYMENT TERMS:</span></td>
      <td class="value">{{ $paymentTerms }}</td>
    </tr>
    <tr>
      <td><span class="label">ADDRESS:</span></td>
      <td class="value">{{ $address }}</td>
      <td><span class="label">COUNTRY OF<br> ORIGIN OF GOODS:</span></td>
      <td class="value">{{ $countryOfOrigin }}</td>
    </tr>
    <tr>
      <td><span class="label">MARKS & NOS.:</span></td>
      <td class="value">{{ $marksNos }}</td>
      <td><span class="label">FINAL DESTINATION:</span></td>
      <td class="value">{{ $finalDestination }}</td>
    </tr>
  </table>

  <table class="product-table wm-table" cellspacing="0" cellpadding="0">
      <tr>
        <th>MARKS & NOS.</th>
        <th>DESCRIPTION OF GOODS</th>
        <th>PACKING</th>
        <th>QTY (IN CS)</th>
        <th>RATE IN USD PER CS</th>
        <th>RATE IN AED PER CS</th>
        <th>AMOUNT IN AED</th>
      </tr>
      @foreach($order->items as $item)
        @php
          $qty = (float) ($item->quantity ?? 0);
          $itemCurrency = \App\Classes\Common::resolveOrderItemPriceCurrency($item, $order->order_type ?? 'sales');
          $rateNative = (float) ($item->single_unit_price ?? $item->unit_price ?? 0);
          $rateAed = \App\Classes\Common::convertProductPriceToAed($rateNative, $itemCurrency);
          $rateUsd = \App\Classes\Common::convertProductPriceToUsd($rateNative, $itemCurrency);
          $lineTotalNative = (float) ($item->subtotal ?? 0);
          if ($lineTotalNative <= 0 && $qty > 0 && $rateNative > 0) {
              $lineTotalNative = $qty * $rateNative;
          }
          $lineTotalAed = \App\Classes\Common::convertProductPriceToAed($lineTotalNative, $itemCurrency);
        @endphp
        <tr>
          <td>{{ $customer->marks_and_nos ?? '' }}</td>
          <td class="desc">{{ $item->product->name ?? '' }}</td>
          <td>{{ $item->unit->name ?? '' }}</td>
          <td>{{ number_format($qty, 2) }}</td>
          <td>{{ number_format($rateUsd, 2) }}</td>
          <td>{{ number_format($rateAed, 2) }}</td>
          <td>{{ number_format($lineTotalAed, 2) }}</td>
        </tr>
      @endforeach
      <tr class="total-row">
        <td colspan="3" style="text-align:right;">TOTAL</td>
        <td>{{ number_format($order->total_quantity, 2) }}</td>
        <td></td>
        <td></td>
        <td>{{ number_format($orderTotalAed, 2) }}</td>
      </tr>
  </table>

  <div class="amount-words">
    AMOUNT (IN WORDS):<br>
    {{ strtoupper($orderCurrency) }} {{ strtoupper(numberToWords($orderTotalNative)) }}
  </div>
  <div class="amount-words">
    AMOUNT IN :<br>
    {{ \App\Classes\Common::formatAmountByCurrencyCode($orderTotalNative, $orderCurrency) }}
     <br>
    ({{ \App\Classes\Common::formatAmountByCurrencyCode($orderTotalUsd, 'USD') }})
  </div>

  <div class="bank">
    <strong>BANK DETAILS:</strong>
    <table cellspacing="0" cellpadding="0">
    <tr>
        <td><strong>BENEFICIARY NAME:</strong></td>
        <td>{{ $customer->beneficiary_name ?? $order->beneficiary_name ?? '' }}</td>
    </tr>
    <tr>
        <td><strong>BANK NAME:</strong></td>
        <td>{{ $customer->bank_name ?? $order->bank_name ?? '' }}</td>
    </tr>
    <tr>
        <td><strong>ACCOUNT NO.:</strong></td>
        <td>{{ $customer->account_no ?? $order->account_no ?? '' }}</td>
    </tr>
    <tr>
        <td><strong>IBAN NO.:</strong></td>
        <td>{{ $customer->iban_no ?? $order->iban_no ?? '' }}</td>
    </tr>
    <tr>
        <td><strong>SWIFT CODE:</strong></td>
        <td>{{ $customer->swift_code ?? $order->swift_code ?? '' }}</td>
    </tr>
    <tr>
        <td><strong>BRANCH:</strong></td>
        <td>{{ $customer->branch ?? $order->branch ?? '' }}</td>
    </tr>
    </table>
  </div>

  <div class="declaration">
    <strong>DECLARATION:</strong>
    WE CERTIFY THAT THE ORIGIN OF THE GOODS AND CONTENTS TO BE TRUE & CORRECT.
  </div>

  <div class="signature-stamp-block">
    @if(!empty($stampSrc))
    <div class="invoice-stamp">
      <img src="{{ $stampSrc }}" alt="Company Stamp" />
    </div>
    <div class="signature-room"></div>
    @endif

    <div class="signature">
      <div>SIGNATURE & DATE:</div>
      @if(!empty($signatureSrc))
      <div class="signature-image-wrap">
        <img class="signature-handwritten" src="{{ $signatureSrc }}" alt="Signature">
      </div>
      @endif
      <div class="signature-line"></div>
      <div style="font-size:10px; margin-top:2px;">{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}</div>
    </div>
  </div>

</div>

@include('pdf.partials.shams-letterhead-footer')

</body>
</html>
