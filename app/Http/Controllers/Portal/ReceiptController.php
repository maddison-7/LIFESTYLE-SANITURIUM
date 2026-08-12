<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Concerns\GeneratesReceiptPdf;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ReceiptController extends Controller
{
    use GeneratesReceiptPdf;

    public function __invoke(Payment $payment): Response
    {
        abort_unless($payment->patient_id === Auth::guard('patient')->id() && $payment->isPaid(), 404);

        return $this->receiptPdfResponse($payment);
    }
}
