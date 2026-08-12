<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Payment;
use App\Models\WebsiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

trait GeneratesReceiptPdf
{
    /**
     * Shared by the admin and patient-portal receipt controllers so the
     * PDF layout only exists in one place.
     */
    protected function receiptPdfResponse(Payment $payment): Response
    {
        $payment->loadMissing(['patient', 'appointment.service', 'appointment.branch']);

        $pdf = Pdf::loadView('receipts.show', [
            'payment' => $payment,
            'siteSettings' => WebsiteSetting::allSettings(),
        ])->setPaper('a5');

        return $pdf->stream("receipt-{$payment->reference}.pdf");
    }
}
