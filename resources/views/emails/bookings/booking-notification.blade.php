<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Booking Received</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:10px;">
        <h2 style="color:#d63384;">New Booking Received</h2>

        <p>Hello Admin,</p>

        <p>A new booking has been received. Please review the details below:</p>

        <hr>

        <p><strong>Customer Name:</strong> {{ $booking->full_name }}</p>
        <p><strong>Email:</strong> {{ $booking->email }}</p>
        <p><strong>Service:</strong> {{ $booking->service->name }}</p>

        <p>
            <strong>Date:</strong>
            {{ \Carbon\Carbon::parse($booking->appointment_date)->format('F d, Y') }}
        </p>

        <p>
            <strong>Time:</strong>
            {{ \Carbon\Carbon::parse($booking->appointment_time)->format('h:i A') }}
        </p>

        <p><strong>Status:</strong> Pending</p>

        <hr>

        <p>Please login to the admin panel to confirm or reject this booking.</p>

        <p style="margin-top:20px;">
            <strong>— Cha-cha Salon System</strong>
        </p>
    </div>
</body>
</html>
