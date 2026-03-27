<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Proforma Invoice</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Arial:wght@400;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Arial', sans-serif;
      background: #fff;
      color: #000;
      padding: 15px;
      font-size: 11.5px;
      line-height: 1.4;
    }
    .container {
      width: 100%;
      max-width: 900px;
      margin: auto;
      border: 2px solid #000;
      padding: 18px;
      position: relative;
      min-height: 900px;
    }
    .header {
      text-align: center;
      font-weight: bold;
      font-size: 18px;
      text-transform: uppercase;
      margin-bottom: 12px;
    }
    .info-table, .product-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 11px;
    }
    .info-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: auto; /* important for automatic width adjustment */
}

.info-table td {
    padding: 5px 6px;
    vertical-align: top;
}
    .info-table .label {
      font-weight: bold;
      width: 150px;
      white-space: nowrap;
    }
    .info-table .value { width: 25%; }
    .info-table .right-align { text-align: left ; }
    .product-table th {
      background: #000;
      color: #fff;
      padding: px 3px;
      font-weight: normal;
      font-size: 10px;
      text-align: center;
    }
    .product-table td {
      border: 1px solid #000;
      padding: 5px 3px;
      text-align: center;
    }
    .product-table .desc { text-align: left; padding-left: 8px; }
    .product-table .total-row td {
      font-weight: bold;
      background: #f0f0f0;
    }
    .amount-words {
      margin: 12px 0;
      font-weight: bold;
      font-size: 11px;
      text-align: left;
    }
    .bank, .declaration { margin-top: 12px; font-size: 11px; }
    .bank strong, .declaration strong { display: block; margin-bottom: 3px; }
    .bank td { padding: 1px 0; border: none; }
    .signature {
      margin-top: 25px;
      text-align: right;
      font-size: 11px;
    }
    .signature-line {
      border-bottom: 1px solid #000;
      width: 180px;
      margin: 15px 0 3px auto;
    }
    .stamp {
      position: absolute;
      bottom: 330px;
      right: 20px;
      width: 130px;
      opacity: 0.9;
      z-index: 10;
    }
    .stamp img { width: 100%; height: auto; }
    .invoice-footer {
      position: absolute;
      bottom: 60px; /* distance from bottom edge */
      left: 0;
      width: 100%;
      text-align: center;
      font-size: 13px;
      color: #000;
      border-top: 3px double #7f7f7f;
      padding-top: 5px;
    }
    @media print {
      body { padding: 0; background: #fff; }
      .container { border: 2px solid #000; padding: 18px; }
      .stamp { opacity: 1; }
    }
    .info-table td,
    .info-table tr {
        border: 1px solid black;
        padding: 6px;
    }
  </style>
</head>
<body>
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

    $aed = $order->total;
    $usdRate = 3.67; // yahan apna rate laga dena

    $usd = $aed / $usdRate;
@endphp


<div class="container">

  <!-- Logo -->
  <img src="http://72.61.173.29/uploads/companies/company_uu4qzlfzgwfhqvi9ty5z.jpg"
       alt="logo"
       style="width: 100%;  height: auto; display: block;">

  <div class="header">PROFORMA INVOICE</div>

  <!-- Info Table -->
 <table class="info-table">
    <tr>
        @if($warehouse->name)
        <td><span class="label">SHIPPER:</span></td>
        <td class="value">{{ $warehouse->name }}</td>
        @endif

        @if($order->invoice_number || $order->order_date)
        <td class="right-align"><span class="label">INVOICE NO. & DATE:</span></td>
        <td class="value">
            {{ $order->invoice_number ?? '' }}
            @if($order->order_date)
                / {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}
            @endif
        </td>
        @endif
    </tr>

    <tr>
        @if($warehouse->address)
        <td><span class="label">Contact No#:</span></td>
        <td class="value">{{ $warehouse->address }}</td>
        @endif

        @if(optional($customer)->terms_of_delivery || optional($order)->terms_of_delivery)
        <td class="right-align"><span class="label">TERMS OF DELIVERY:</span></td>
        <td class="value">{{ optional($customer)->terms_of_delivery ?? optional($order)->terms_of_delivery ?? '' }}</td>
        @endif
    </tr>

    <tr>
        @if(optional($customer)->name || optional($order)->buyer_name)
        <td><span class="label">BUYER:</span></td>
        <td class="value">{{ optional($customer)->name ?? optional($order)->buyer_name ?? '' }}</td>
        @endif

        @if(optional($customer)->payment_terms || optional($order)->payment_terms)
        <td class="right-align"><span class="label">PAYMENT TERMS:</span></td>
        <td class="value">{{ optional($customer)->payment_terms ?? optional($order)->payment_terms ?? '' }}</td>
        @endif
    </tr>

    <tr>
        @if(optional($customer)->address || optional($order)->address)
        <td><span class="label">ADDRESS:</span></td>
        <td class="value">{{ optional($customer)->address ?? optional($order)->address ?? '' }}</td>
        @endif

        @if(optional($customer)->country_of_origin_of_goods || optional($order)->country_of_origin_of_goods)
        <td class="right-align"><span class="label">COUNTRY OF<br> ORIGIN OF GOODS:</span></td>
        <td class="value">{{ optional($customer)->country_of_origin_of_goods ?? optional($order)->country_of_origin_of_goods ?? '' }}</td>
        @endif
    </tr>

    <tr>
        @if(optional($customer)->marks_and_nos || optional($order)->marks_and_nos)
        <td><span class="label">MARKS & NOS.:</span></td>
        <td class="value">{{ optional($customer)->marks_and_nos ?? optional($order)->marks_and_nos ?? '' }}</td>
        @endif

        @if(optional($customer)->final_destination || optional($order)->final_destination)
        <td class="right-align"><span class="label">FINAL DESTINATION:</span></td>
        <td class="value">{{ optional($customer)->final_destination ?? optional($order)->final_destination ?? '' }}</td>
        @endif
    </tr>
</table>


  <!-- Product Table -->
  <table class="product-table">
    <thead>
      <tr>
        <th>MARKS & NOS.</th>
        <th>DESCRIPTION OF GOODS</th>
        <th>PACKING</th>
        <th>QTY (IN CS)</th>
        <th>RATE IN USD PER CS</th>
        <th>RATE IN AED PER CS</th>
        <th>AMOUNT IN AED</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->items as $item)
        <tr>
          <td>{{ $customer->marks_and_nos ?? '' }}</td>
          <td class="desc">{{ $item->product->name ?? '' }}</td>
          <td>{{ $item->unit->name ?? '' }}</td>
          <td>{{ number_format($item->quantity, 2) }}</td>
          <td>{{ number_format($item->unit_price, 2) }}</td>
          <td>{{ number_format($item->total_tax, 2) }}</td>
          <td>{{ number_format($item->subtotal + $item->total_tax, 2) }}</td>
        </tr>
      @endforeach
      <tr class="total-row">
        <td colspan="3" style="text-align:right;">TOTAL</td>
        <td>{{ number_format($order->total_quantity, 2) }}</td>
        <td></td>
        <td></td>
        <td>{{ number_format($order->total, 2) }}</td>
      </tr>
    </tbody>
  </table>

  <!-- Amount in Words -->
  <div class="amount-words">
    AMOUNT (IN WORDS):<br>
    AED {{ strtoupper(numberToWords($order->total)) }}
  </div>
   <!-- Amount in Words -->
  <div class="amount-words">
    AMOUNT IN :<br>
    AED {{$order->total}}
     <br>
    (USD {{ number_format($usd, 2) }})
  </div>

  <!-- Bank Details -->
  <div class="bank">
    <strong>BANK DETAILS:</strong>
    <table>
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

  <!-- Declaration -->
  <div class="declaration">
    <strong>DECLARATION:</strong>
    WE CERTIFY THAT THE ORIGIN OF THE GOODS AND CONTENTS TO BE TRUE & CORRECT.
  </div>

  <!-- Signature -->
  <div class="signature">
    <div>SIGNATURE & DATE:</div>
    <div class="signature-line"></div>
    <div style="font-size:10px; margin-top:2px;">{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}</div>
  </div>

  <!-- Stamp -->
  <div class="stamp">
    <img src="http://72.61.173.29/images/stamp.png" alt="Company Stamp" />
  </div>

  <!-- Footer -->
  <div class="invoice-footer">
    <p>
      Mob: +971 56 409 0798, BIZ00648, Compass Building, Al Shohada Road Al Hamra,<br>
      Industrial Zone-FZ, Ras Al Khaimah, UAE
    </p>
  </div>

</div>
</body>
</html>
