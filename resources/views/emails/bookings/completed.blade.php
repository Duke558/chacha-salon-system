<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Completed</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:10px;">
        <h2 style="color:#28a745;">Booking Completed </h2>

        <p>Hello <strong>{{ $booking->full_name }}</strong>,</p>

        <p>
            We’re happy to inform you that your booking has been
            <strong style="color:#28a745;">completed successfully</strong>.
        </p>

        <hr>

        <p><strong>Service:</strong> {{ $booking->service->name }}</p>

        <p>
            <strong>Date:</strong>
            {{ \Carbon\Carbon::parse($booking->appointment_date)->format('F d, Y') }}
        </p>

        <p>
            <strong>Time:</strong>
            {{ \Carbon\Carbon::parse($booking->appointment_time)->format('h:i A') }}
        </p>

        <hr>

        <p>
            Thank you for choosing <strong>Cha-cha Salon</strong>.
            We truly appreciate your trust and hope to see you again soon! 
        </p>

        <p style="margin-top:20px;">
            Warm regards,<br>
            <strong>{{ config('app.name') }}</strong>
        </p>
    </div>
</body>
</html>

