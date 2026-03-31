<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$invoice = App\Models\Invoice::where('invoice_number', 'INV-000003')->first();
$invoice->template_name = 'ocru';
$invoice->save();
try {
    $pdf = $invoice->getGeneratedPDFOrStream('invoice');
    file_put_contents(__DIR__.'/public/test2.pdf', $pdf->getContent());
    echo "SUCCESS\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
