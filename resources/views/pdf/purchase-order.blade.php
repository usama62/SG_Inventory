<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order</title>
    <style>
        @include('pdf.partials.shams-letterhead-styles')
        @include('pdf.partials.shams-watermark-overlay-styles')
        @include('pdf.partials.shams-transparent-cells')

        body { font-size: 11px; line-height: 1.4; color: #222; }

        .po-bar {
            background: #2b3a7a;
            color: #fff;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            padding: 7px 10px;
            border: 0;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
            color: #2b3a7a;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .po-title {
            font-size: 20px;
            font-weight: bold;
            color: #2b3a7a;
            text-transform: uppercase;
            text-align: right;
            margin: 0 0 8px 0;
        }

        .meta-box { border-collapse: collapse; }
        .meta-box td {
            border: 1px solid #2b3a7a;
            padding: 6px 8px;
            font-size: 10.5px;
            line-height: 1.3;
            vertical-align: middle;
        }
        .meta-box .lbl {
            font-weight: bold;
            color: #2b3a7a;
            width: 68px;
            white-space: nowrap;
        }

        .party-box {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #2b3a7a;
            table-layout: fixed;
        }
        .party-body {
            padding: 8px 10px;
            font-size: 10px;
            line-height: 1.45;
            vertical-align: top;
            word-wrap: break-word;
        }
        .party-name { font-weight: bold; color: #2b3a7a; font-size: 10.5px; }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 8px;
        }
        .items-table th {
            background: #2b3a7a;
            color: #fff;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            padding: 6px 3px;
            border: 1px solid #2b3a7a;
            text-align: center;
            vertical-align: middle;
        }
        .items-table td {
            border: 1px solid #2b3a7a;
            padding: 6px 4px;
            font-size: 10px;
            vertical-align: middle;
        }

        .totals { width: 100%; border-collapse: collapse; }
        .totals td {
            border: 1px solid #2b3a7a;
            padding: 6px 8px;
            font-size: 10.5px;
            vertical-align: middle;
        }
        .totals .lbl { font-weight: bold; text-align: right; color: #2b3a7a; }
        .totals .val { text-align: right; }
        .totals .grand td { background: #2b3a7a; color: #fff; font-weight: bold; font-size: 11px; }

        .comments-body {
            padding: 8px 10px;
            font-size: 10px;
            vertical-align: top;
        }

        .po-contact-note {
            margin-top: 8px;
            font-size: 9.5px;
            line-height: 1.35;
            color: #333;
        }
    </style>
</head>
@php
    $poDate = $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') : now()->format('d-m-Y');
    $poNumber = $order->invoice_number ?? '[PO Number]';
    $companyName = $company->name ?? 'SHAMS UNIVERSAL TRADING FZ-LLC';
    $defaultCompanyAddress = "Al Fattan Plaza, Office # 904\nOffice Building, Al Garhood\nDubai\nU.A.E";
    $companyAddress = $company->address ?? $defaultCompanyAddress;
    $companyPhone = $company->phone ?? '+971 56 409 0798';
    $companyWebsite = $company->website ?? 'https://shamsglobalfzllc.ae';
    $supplierName = $order->supplier_company_name ?? optional($supplier)->name ?? optional($customer)->name ?? '—';
    $supplierAddress = $order->supplier_address ?? optional($supplier)->address ?? optional($customer)->address ?? '';
    $supplierPhone = $order->supplier_phone ?? optional($supplier)->phone ?? optional($customer)->phone ?? '';
    $marksAndNos = $order->marks_and_nos ?? optional($supplier)->marks_and_nos ?? optional($customer)->marks_and_nos ?? '';
    $shipToName = $order->ship_to_name ?? '';
    $shipToCompany = $order->ship_to_company_name ?? $companyName;
    $shipToAddress = $order->ship_to_address ?? $defaultCompanyAddress;
    $shipToPhone = $order->ship_to_phone ?? $companyPhone;
    $comments = $order->notes ?? '';
    $subtotal = (float) ($order->subtotal ?? 0);
    $tax = (float) ($order->tax_amount ?? 0);
    $shipping = (float) ($order->shipping ?? 0);
    $other = (float) ($order->other_charges ?? 0);
    $grandTotal = (float) ($order->total ?? ($subtotal + $tax + $shipping + $other));
    $orderCurrency = \App\Classes\Common::resolveOrderCurrency($order, 'purchase-orders');
    $formatOrderAmount = fn (float $amount) => \App\Classes\Common::formatAmountByCurrencyCode($amount, $orderCurrency);
    $items = $order->items ?? collect();
    $blankRows = max(0, 3 - $items->count());
    $contactName = optional($staffMember)->name ?? '—';
    $contactEmail = $company->email ?? 'sufiyanjetham@shamsglobalfzllc.ae';
    $stampSrc = $stamp_src ?? \App\Classes\Common::getCompanyStampDataUri($company);
@endphp
<body>

@include('pdf.partials.shams-letterhead-open')
@include('pdf.partials.shams-letterhead-watermark')

<div class="doc-body">
    <table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:8px;">
        <tr>
            <td width="52%" valign="top" style="font-size:10px; line-height:1.45; padding-right:6px;">
                <div class="company-name">{{ $companyName }}</div>
                {!! nl2br(e($companyAddress)) !!}<br>
                Phone: {{ $companyPhone }}<br>
                Website: {{ $companyWebsite }}
            </td>
            <td width="48%" valign="top" align="right" style="padding-left:6px;">
                <div class="po-title">Purchase Order</div>
                <table cellspacing="0" cellpadding="0" class="meta-box wm-table" align="right">
                    <tr><td class="lbl">DATE:</td><td>{{ $poDate }}</td></tr>
                    <tr><td class="lbl">PO #:</td><td>{{ $poNumber }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:8px; table-layout:fixed;">
        <tr>
            <td width="50%" valign="top" style="padding-right:4px;">
                <table cellspacing="0" cellpadding="0" class="party-box wm-table">
                    <tr><td class="po-bar">Supplier</td></tr>
                    <tr><td class="party-body">
                        <span class="party-name">{{ $supplierName }}</span><br>
                        @if($supplierAddress){!! nl2br(e($supplierAddress)) !!}<br>@endif
                        @if($supplierPhone)<strong>Phone:</strong> {{ $supplierPhone }}@endif
                    </td></tr>
                </table>
            </td>
            <td width="50%" valign="top" style="padding-left:4px;">
                <table cellspacing="0" cellpadding="0" class="party-box wm-table">
                    <tr><td class="po-bar">Ship To</td></tr>
                    <tr><td class="party-body">
                        @if($shipToName){{ $shipToName }}<br>@endif
                        <span class="party-name">{{ $shipToCompany }}</span><br>
                        {!! nl2br(e($shipToAddress)) !!}<br>
                        @if($shipToPhone)<strong>Phone:</strong> {{ $shipToPhone }}@endif
                    </td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%" cellspacing="0" cellpadding="0" class="items-table wm-table">
        <tr>
            <th width="11%">Marks &amp; Nos.</th>
            <th width="28%">Description</th>
            <th width="11%">Qty in Cases</th>
            <th width="14%">Rate in USD</th>
            <th width="14%">Rate in Dirhams</th>
            <th width="14%">Total (Dirhams)</th>
        </tr>
        @forelse($items as $item)
            @php
                $qty = (float) ($item->quantity ?? 0);
                $itemCurrency = \App\Classes\Common::resolveOrderItemPriceCurrency($item, $order->order_type ?? 'purchase-orders');
                $rateNative = (float) ($item->single_unit_price ?? $item->unit_price ?? 0);
                $rateAed = \App\Classes\Common::convertProductPriceToAed($rateNative, $itemCurrency);
                $rateUsd = \App\Classes\Common::convertProductPriceToUsd($rateNative, $itemCurrency);
                $lineTotalNative = (float) ($item->subtotal ?? 0);
                if ($lineTotalNative <= 0 && $qty > 0 && $rateNative > 0) { $lineTotalNative = $qty * $rateNative; }
                $lineTotalAed = \App\Classes\Common::convertProductPriceToAed($lineTotalNative, $itemCurrency);
                $productName = optional($item->product)->name ?? '—';
            @endphp
            <tr>
                <td align="center">{{ $marksAndNos ?: '—' }}</td>
                <td align="left">{{ $productName }}</td>
                <td align="center">{{ number_format($qty, 2) }}</td>
                <td align="right">{{ number_format($rateUsd, 2) }}</td>
                <td align="right">{{ number_format($rateAed, 2) }}</td>
                <td align="right">{{ number_format($lineTotalAed, 2) }}</td>
            </tr>
        @empty
            <tr><td align="center">—</td><td align="left">—</td><td align="center">—</td><td align="right">—</td><td align="right">—</td><td align="right">—</td></tr>
        @endforelse
        @for($i = 0; $i < $blankRows; $i++)
            <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
        @endfor
    </table>

    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td width="58%" valign="top" style="padding-right:4px;">
                <table width="100%" cellspacing="0" cellpadding="0" class="party-box wm-table">
                    <tr><td class="po-bar">Comments or Special Instructions</td></tr>
                    <tr><td class="comments-body">{{ $comments ?: '—' }}</td></tr>
                </table>
                @if(!empty($stampSrc))
                <table width="100%" cellspacing="0" cellpadding="0" style="margin-top:6px;">
                    <tr><td align="left" style="border:none; padding:4px 0 0 4px;">
                        <img src="{{ $stampSrc }}" width="75" height="75" alt="Company Stamp">
                    </td></tr>
                </table>
                @endif
            </td>
            <td width="42%" valign="top">
                <table width="100%" cellspacing="0" cellpadding="0" class="totals wm-table">
                    <tr><td class="lbl" width="58%">SUBTOTAL</td><td class="val" width="42%">{{ $formatOrderAmount($subtotal) }}</td></tr>
                    <tr><td class="lbl">TAX</td><td class="val">{{ $tax > 0 ? $formatOrderAmount($tax) : '—' }}</td></tr>
                    <tr><td class="lbl">SHIPPING</td><td class="val">{{ $shipping > 0 ? $formatOrderAmount($shipping) : '—' }}</td></tr>
                    <tr><td class="lbl">OTHER</td><td class="val">{{ $other > 0 ? $formatOrderAmount($other) : '—' }}</td></tr>
                    <tr class="grand"><td class="lbl">TOTAL ({{ $orderCurrency }})</td><td class="val">{{ $formatOrderAmount($grandTotal) }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <p class="po-contact-note">If you have any questions about this purchase order, please contact {{ $contactName }}, {{ $companyPhone }}, {{ $contactEmail }}</p>
</div>

@include('pdf.partials.shams-letterhead-footer')

</body>
</html>
