<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $payment->reference }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1f2937; font-size: 12px; margin: 0; padding: 24px; }
        .header { border-bottom: 2px solid #15806c; padding-bottom: 14px; margin-bottom: 18px; }
        .clinic-name { font-size: 18px; font-weight: bold; color: #12443d; margin: 0; }
        .tagline { font-size: 11px; color: #15806c; margin: 2px 0 0; }
        .stamp { display: inline-block; border: 2px solid #15806c; color: #15806c; font-weight: bold; padding: 4px 12px; border-radius: 4px; font-size: 13px; letter-spacing: 1px; float: right; }
        table.details { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.details td { padding: 6px 0; vertical-align: top; }
        table.details td.label { color: #6b7280; width: 40%; }
        table.details td.value { font-weight: bold; color: #111827; }
        .amount-box { background: #eefbf6; border: 1px solid #adebd4; border-radius: 6px; padding: 14px; margin-top: 18px; text-align: center; }
        .amount-box .label { font-size: 10px; text-transform: uppercase; color: #136759; letter-spacing: 1px; }
        .amount-box .amount { font-size: 24px; font-weight: bold; color: #12443d; margin-top: 4px; }
        .footer { margin-top: 28px; border-top: 1px solid #e5e7eb; padding-top: 12px; font-size: 9px; color: #9ca3af; }
        .reference { font-family: monospace; font-size: 11px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <span class="stamp">{{ strtoupper($payment->statusLabel()) }}</span>
        <p class="clinic-name">{{ $siteSettings['clinic_name'] ?? 'Lifestyle Sanitarium Clinic' }}</p>
        <p class="tagline">{{ $siteSettings['tagline'] ?? 'Afya Bora, Maisha Bora.' }}</p>
        <p class="reference">Receipt {{ $payment->reference }}</p>
    </div>

    <table class="details">
        <tr>
            <td class="label">Patient</td>
            <td class="value">{{ $payment->patient->name }}</td>
        </tr>
        <tr>
            <td class="label">Phone</td>
            <td class="value">{{ $payment->patient->phone }}</td>
        </tr>
        <tr>
            <td class="label">Service</td>
            <td class="value">{{ $payment->appointment->service->name }}</td>
        </tr>
        <tr>
            <td class="label">Branch</td>
            <td class="value">{{ $payment->appointment->branch->name }}</td>
        </tr>
        <tr>
            <td class="label">Appointment Reference</td>
            <td class="value">{{ $payment->appointment->reference }}</td>
        </tr>
        <tr>
            <td class="label">Payment Method</td>
            <td class="value">{{ $payment->methodLabel() }}</td>
        </tr>
        @if ($payment->gateway_reference)
            <tr>
                <td class="label">Transaction ID</td>
                <td class="value">{{ $payment->gateway_reference }}</td>
            </tr>
        @endif
        <tr>
            <td class="label">Date Paid</td>
            <td class="value">{{ $payment->paid_at?->format('d M Y, g:i A') ?? '—' }}</td>
        </tr>
    </table>

    <div class="amount-box">
        <div class="label">Amount Paid</div>
        <div class="amount">{{ number_format($payment->amount, 2) }}</div>
    </div>

    <div class="footer">
        {{ $siteSettings['medical_disclaimer'] ?? 'Information provided is intended for general health education and is not a substitute for professional medical consultation.' }}
        <br><br>
        This is a computer-generated receipt for {{ $siteSettings['clinic_name'] ?? 'Lifestyle Sanitarium Clinic' }}.
        @if (!empty($siteSettings['phone_primary'] ?? null))
            Contact: {{ $siteSettings['phone_primary'] }}
        @endif
    </div>
</body>
</html>
