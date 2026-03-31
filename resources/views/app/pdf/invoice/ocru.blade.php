<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@lang('pdf_invoice_label') - {{ $invoice->invoice_number }}</title>
    <style type="text/css">
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 10px, 20px, 10px, 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        p {
            margin: 0 0 10px 0;
            padding: 0;
        }

        .header-table {
            margin-bottom: 40px;
        }

        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #55547a;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }

        .bill-to-section {
            width: 50%;
            float: left;
            margin-bottom: 40px;
            margin-top: 30px;
        }

        .bill-to-heading {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #55547a;
            text-transform: uppercase;
        }

        .meta-section {
            width: 35%;
            float: right;
            margin-bottom: 40px;
            margin-top: 30px;
        }

        .meta-table td {
            padding: 4px 0;
        }

        .meta-label {
            font-weight: bold;
            color: #55547a;
            text-align: left;
            width: 50%;
        }

        .meta-value {
            text-align: right;
            width: 50%;
        }

        .clear {
            clear: both;
        }

        /* Items Table */
        .items-table {
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
        }

        .items-table th {
            text-align: left;
            padding: 10px 5px;
            border-bottom: 2px solid #55547a;
            color: #55547a;
            font-weight: bold;
        }

        .items-table td {
            padding: 10px 5px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .item-desc {
            font-size: 11px;
            color: #666;
            white-space: pre-wrap;
        }

        .text-right {
            text-align: right !important;
        }

        /* Totals section */
        .totals-table {
            margin-left: 70%;
            width: 30%;
            margin-bottom: 40px;
        }

        .totals-table td {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .totals-table tr:last-child td {
            border-bottom: none;
        }

        .totals-label {
            text-align: left;
            font-weight: bold;
            color: #55547a;
        }

        .totals-value {
            text-align: right;
        }

        .grand-total td {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            border-top: 2px solid #55547a;
            padding-top: 3px;
        }

        .notes-section {
            margin-top: 30px;
            font-size: 12px;
            line-height: 1.5;
            text-align: left;
        }

        .notes-heading {
            font-weight: bold;
            color: #55547a;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .footer-section {
            margin-top: 30px;
            font-size: 12px;
            line-height: 1.5;
            page-break-inside: avoid;
        }

        .footer-heading {
            font-weight: bold;
            color: #55547a;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td>
                <!-- Logo can be added here if needed -->
                @if ($logo)
                    <img style="max-height: 120px;" src="{{ \App\Space\ImageUtils::toBase64Src($logo) }}"
                        alt="Company Logo">
                @endif
            </td>
            <td class="text-right">
                <h1 class="header-title">Tax Invoice</h1>
            </td>
        </tr>
    </table>

    <div>
        <div class="bill-to-section">
            <div class="bill-to-heading">Bill To</div>
            <div style="line-height: 1.2;">
                {!! $billing_address !!}
            </div>
        </div>

        <div class="meta-section">
            <table class="meta-table">
                <tr>
                    <td class="meta-label">Invoice No :</td>
                    <td class="meta-value">{{ $invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Invoice Date :</td>
                    <td class="meta-value">{{ $invoice->formattedInvoiceDate }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Due Date :</td>
                    <td class="meta-value">{{ $invoice->formattedDueDate }}</td>
                </tr>
                @if($invoice->po_number)
                    <tr>
                        <td class="meta-label">PO Number :</td>
                        <td class="meta-value">{{ $invoice->po_number }}</td>
                    </tr>
                @endif
            </table>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Item & Description</th>
                <th width="30%" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $item->name }}</div>
                        @if($item->description)
                            <div class="item-desc">{!! nl2br(e($item->description)) !!}</div>
                        @endif
                    </td>
                    <td class="text-right">{!! format_money_pdf($item->total, $invoice->customer->currency) !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="clear"></div>

    <div>
        <table class="totals-table">
            <tr>
                <td class="totals-label">Sub Total</td>
                <td class="totals-value">{!! format_money_pdf($invoice->sub_total, $invoice->customer->currency) !!}
                </td>
            </tr>
            @foreach($invoice->taxes as $tax)
                <tr>
                    <td class="totals-label">{{ $tax->name }} ({{ $tax->percent }}%)</td>
                    <td class="totals-value">{!! format_money_pdf($tax->amount, $invoice->customer->currency) !!}</td>
                </tr>
            @endforeach
            <tr class="grand-total">
                <td class="totals-label">Total</td>
                <td class="totals-value">{!! format_money_pdf($invoice->total, $invoice->customer->currency) !!}
                </td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>

    <!-- Bank Details from notes -->
    @if ($notes)
        <div class="notes-section">
            <div class="notes-heading">EFT/Bank Details:</div>
            <div style="line-height: 1.5;">
                {!! $notes !!}
            </div>
        </div>
    @endif

    <!-- Terms and conditions from terms -->
    <div class="footer-section">
        <div class="footer-heading">Terms</div>
        <div>Please quote your invoice number when making a payment. <br>A search fee of $15
            will be charged if funds cannot be properly allocated to your account. <br>For service terms and conditions
            please see your agreement.</div>
    </div>

</body>

</html>