<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order</title>
    <style>
        @font-face {
            font-family: 'ARIALNB';
            src: url("{{ public_path('fonts/ARIALNB.TTF') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        body {
            font-family: 'ARIALNB', Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.45;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .page-border {
            border: 2px solid #2b3a7a;
        }

        .pad {
            padding: 14px 16px 18px;
        }

        .bar {
            background: #2b3a7a;
            color: #fff;
            font-weight: bold;
            font-size: 10.5px;
            text-transform: uppercase;
            padding: 6px 10px;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
            color: #2b3a7a;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .po-title {
            font-size: 24px;
            font-weight: bold;
            color: #2b3a7a;
            text-transform: uppercase;
            text-align: right;
            margin-bottom: 8px;
        }

        .meta-box td {
            border: 1px solid #2b3a7a;
            padding: 6px 10px;
            font-size: 11px;
        }

        .meta-box .lbl {
            font-weight: bold;
            color: #2b3a7a;
            width: 70px;
        }

        .party-body {
            padding: 10px 12px;
            font-size: 10.5px;
            line-height: 1.55;
            vertical-align: top;
        }

        .party-name {
            font-weight: bold;
            color: #2b3a7a;
            font-size: 11px;
        }

        .items-table th {
            background: #2b3a7a;
            color: #fff;
            font-weight: bold;
            font-size: 8.5px;
            text-transform: uppercase;
            padding: 7px 4px;
            border: 1px solid #2b3a7a;
            text-align: center;
        }

        .items-table td {
            border: 1px solid #2b3a7a;
            padding: 7px 5px;
            font-size: 10.5px;
            vertical-align: middle;
        }

        .totals td {
            border: 1px solid #2b3a7a;
            padding: 7px 10px;
            font-size: 11px;
        }

        .totals .lbl {
            font-weight: bold;
            text-align: right;
            color: #2b3a7a;
        }

        .totals .val {
            text-align: right;
        }

        .totals .grand td {
            background: #2b3a7a;
            color: #fff;
            font-weight: bold;
            font-size: 12px;
        }

        .comments-body {
            padding: 10px 12px;
            font-size: 10.5px;
            vertical-align: top;
        }

        .doc-footer {
            margin-top: 14px;
            padding-top: 10px;
            border-top: 3px double #888;
            text-align: center;
            font-size: 9.5px;
            color: #515650;
            line-height: 1.5;
        }
    </style>
</head>
@php
    $usdRate = (float) ($usd_rate ?? 3.67);

    $poDate = $order->order_date
        ? \Carbon\Carbon::parse($order->order_date)->format('d-m-Y')
        : now()->format('d-m-Y');

    $poNumber = $order->invoice_number ?? '[PO Number]';

    $companyName = $company->name ?? 'SHAMS GLOBAL TRADING FZ LLC';
    $defaultCompanyAddress = "AL fattan plaza\nOffice no: 904\noffice building Al garhood\nDubai\nU.A.E";
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

    $items = $order->items ?? collect();
    $blankRows = max(0, 5 - $items->count());

    $contactName = optional($staffMember)->name ?? '—';
    $contactEmail = $company->email ?? 'sufiyanjetham@shamsglobalfzllc.ae';
    $logoSrc = $receipt_logo_src ?? null;

    $footerLine1 = 'Mob: ' . $companyPhone . ', ' . $companyName . ', ' . str_replace(["\r\n", "\r", "\n"], ', ', $companyAddress);
    $footerLine2 = 'Email: ' . $contactEmail . ' | Website: ' . $companyWebsite;
@endphp
<body>

<table width="100%" cellspacing="0" cellpadding="0" class="page-border">
    <tr>
        <td class="pad">

            {{-- Logo --}}
            <table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:12px; border-bottom:2px solid #2b3a7a;">
                <tr>
                    <td style="padding-bottom:10px;">
                        @if(!empty($logoSrc))
                            <img src="{{ $logoSrc }}" alt="Logo" width="100%" style="max-width:100%;">
                        @else
                            <img src="http://72.61.173.29/uploads/companies/company_uu4qzlfzgwfhqvi9ty5z.jpg" alt="Logo" width="100%" style="max-width:100%;">
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Company + PO meta --}}
            <table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:12px;">
                <tr>
                    <td width="52%" valign="top" style="font-size:10.5px; line-height:1.55;">
                        @if(empty($logoSrc))
                            <div class="company-name">{{ $companyName }}</div>
                        @endif
                        {!! nl2br(e($companyAddress)) !!}<br><br>
                        Phone: {{ $companyPhone }}<br>
                        Website: {{ $companyWebsite }}
                    </td>
                    <td width="48%" valign="top" align="right">
                        <div class="po-title">Purchase Order</div>
                        <table cellspacing="0" cellpadding="0" class="meta-box" align="right">
                            <tr>
                                <td class="lbl">DATE:</td>
                                <td>{{ $poDate }}</td>
                            </tr>
                            <tr>
                                <td class="lbl">PO #:</td>
                                <td>{{ $poNumber }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- Supplier | Ship To --}}
            <table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:12px;">
                <tr>
                    <td width="50%" valign="top" style="padding-right:5px;">
                        <table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #2b3a7a;">
                            <tr><td class="bar">Supplier</td></tr>
                            <tr>
                                <td class="party-body">
                                    <span class="party-name">{{ $supplierName }}</span><br>
                                    @if($supplierAddress)
                                        {!! nl2br(e($supplierAddress)) !!}<br>
                                    @endif
                                    @if($supplierPhone)
                                        <strong>Phone:</strong> {{ $supplierPhone }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" valign="top" style="padding-left:5px;">
                        <table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #2b3a7a;">
                            <tr><td class="bar">Ship To</td></tr>
                            <tr>
                                <td class="party-body">
                                    @if($shipToName)
                                        {{ $shipToName }}<br>
                                    @endif
                                    <span class="party-name">{{ $shipToCompany }}</span><br>
                                    {!! nl2br(e($shipToAddress)) !!}<br>
                                    @if($shipToPhone)
                                        <strong>Phone:</strong> {{ $shipToPhone }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- Items --}}
            <table width="100%" cellspacing="0" cellpadding="0" class="items-table" style="margin-bottom:12px;">
                <thead>
                    <tr>
                        <th width="11%">Marks &amp; Nos.</th>
                        <th width="28%">Description</th>
                        <th width="11%">Qty in Cases</th>
                        <th width="14%">Rate in USD</th>
                        <th width="14%">Rate in Dirhams</th>
                        <th width="14%">Total (Dirhams)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $qty = (float) ($item->quantity ?? 0);
                            $rateUsd = (float) ($item->unit_price ?? 0);
                            $rateAed = (float) ($item->total_tax ?? 0);
                            if ($rateAed <= 0 && $rateUsd > 0) {
                                $rateAed = $rateUsd * $usdRate;
                            }
                            $lineTotalAed = (float) ($item->subtotal ?? 0);
                            if ($lineTotalAed <= 0 && $qty > 0 && $rateAed > 0) {
                                $lineTotalAed = $qty * $rateAed;
                            }
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
                        <tr>
                            <td align="center">—</td>
                            <td align="left">—</td>
                            <td align="center">—</td>
                            <td align="right">—</td>
                            <td align="right">—</td>
                            <td align="right">—</td>
                        </tr>
                    @endforelse
                    @for($i = 0; $i < $blankRows; $i++)
                        <tr>
                            <td align="center">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            {{-- Comments + Totals --}}
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="58%" valign="top" style="padding-right:6px;">
                        <table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #2b3a7a;">
                            <tr><td class="bar">Comments or Special Instructions</td></tr>
                            <tr><td class="comments-body">{{ $comments }}</td></tr>
                        </table>
                    </td>
                    <td width="42%" valign="top">
                        <table width="100%" cellspacing="0" cellpadding="0" class="totals">
                            <tr>
                                <td class="lbl" width="58%">SUBTOTAL</td>
                                <td class="val" width="42%">AED {{ number_format($subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="lbl">TAX</td>
                                <td class="val">{{ $tax > 0 ? 'AED ' . number_format($tax, 2) : '—' }}</td>
                            </tr>
                            <tr>
                                <td class="lbl">SHIPPING</td>
                                <td class="val">{{ $shipping > 0 ? 'AED ' . number_format($shipping, 2) : '—' }}</td>
                            </tr>
                            <tr>
                                <td class="lbl">OTHER</td>
                                <td class="val">{{ $other > 0 ? 'AED ' . number_format($other, 2) : '—' }}</td>
                            </tr>
                            <tr class="grand">
                                <td class="lbl">TOTAL (DIRHAMS)</td>
                                <td class="val">AED {{ number_format($grandTotal, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div class="doc-footer">
                <p>If you have any questions about this purchase order, please contact {{ $contactName }}, {{ $companyPhone }}, {{ $contactEmail }}</p>
                <p>{{ $footerLine1 }}</p>
                <p>{{ $footerLine2 }}</p>
            </div>

        </td>
    </tr>
</table>

</body>
</html>
