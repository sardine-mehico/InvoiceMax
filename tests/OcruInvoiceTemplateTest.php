<?php

use Tests\TestCase;

uses(TestCase::class);

it('preserves note line breaks in the ocru invoice template', function () {
    $currency = (object) [
        'precision' => 2,
        'decimal_separator' => '.',
        'thousand_separator' => ',',
        'swap_currency_symbol' => false,
        'symbol' => '$',
    ];

    $invoice = (object) [
        'invoice_number' => 'INV-000001',
        'formattedInvoiceDate' => '2026-03-28',
        'formattedDueDate' => '2026-04-27',
        'po_number' => null,
        'sub_total' => 10000,
        'total' => 10000,
        'items' => collect([
            (object) [
                'name' => 'Service Fee',
                'description' => null,
                'total' => 10000,
            ],
        ]),
        'taxes' => collect(),
        'customer' => (object) [
            'currency' => $currency,
        ],
    ];

    $notes = "Account Name: OCRU<br />BSB: 123-456<br />Account No: 12345678";

    $html = view('app.pdf.invoice.ocru', [
        'invoice' => $invoice,
        'billing_address' => 'Customer Name<br />Street Address',
        'notes' => $notes,
        'logo' => null,
    ])->render();

    expect($html)
        ->toContain('EFT/Bank Details:')
        ->toContain('Account Name: OCRU')
        ->toContain('BSB: 123-456')
        ->toContain('Account No: 12345678')
        ->toContain('<br');
});
