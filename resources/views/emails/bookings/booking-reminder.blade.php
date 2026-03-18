<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:8px;">
        <h2 style="color:#d63384;">Appointment Reminder</h2>

        <p>Hello <strong>{{ $booking->full_name ?? 'Valued Customer' }}</strong>,</p>

        <p>This is a friendly reminder of your upcoming appointment at <strong>Cha-cha Salon</strong>.</p>

        <hr>

        <p><strong>Service:</strong> {{ $booking->service->name ?? 'N/A' }}</p>
        <p><strong>Date:</strong> {{ isset($booking->appointment_date) ? \Carbon\Carbon::parse($booking->appointment_date)->format('F d, Y') : 'N/A' }}</p>
        <p><strong>Time:</strong> {{ $booking->appointment_time ?? 'N/A' }}</p>

        <hr>

        <p>Please arrive at least <strong>10 minutes early</strong>.</p>

        <p>We look forward to seeing you!</p>

        <p style="margin-top:20px;"><strong>Cha-cha Salon</strong></p>
    </div>
</body>
</html>