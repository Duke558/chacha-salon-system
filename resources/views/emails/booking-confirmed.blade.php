<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmed</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:8px;">
    <h2 style="color:#d63384;">Hello {{ $booking->full_name }},</h2>
    <p>Your booking at Cha-cha Salon has been <strong>confirmed</strong>!</p>
    <p><strong>Service:</strong> {{ $booking->service->name }}</p>
    <p><strong>Date:</strong> {{ $booking->appointment_date }}</p>
    <p><strong>Time:</strong> {{ $booking->appointment_time }}</p>
     <p style="margin-top:20px;"><strong> Thank you for choosing Cha-cha Salon</strong></p>
    </div>
</body>
</html>
