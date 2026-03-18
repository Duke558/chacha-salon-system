<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Cancelled</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:8px;">
        
        @if($isAdmin)
            <h2 style="color:#d63384;">Booking Cancelled by Customer</h2>

            <p>A booking has been cancelled by the customer.</p>

            <ul>
                <li><strong>Customer Name:</strong> {{ $booking->full_name }}</li>
                <li><strong>Email:</strong> {{ $booking->email }}</li>
                <li><strong>Phone:</strong> {{ $booking->phone }}</li>
                <li><strong>Service:</strong> {{ $booking->service->name }}</li>
                <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->appointment_date)->format('F d, Y') }}</li>
                <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->appointment_time)->format('g:i A') }}</li>
            </ul>

            <p>Please take note and update your records.</p>

        @else
            <h2 style="color:#d63384;">Booking Cancelled</h2>

            <p>Hello <strong>{{ $booking->full_name }}</strong>,</p>

            <p>Your booking for <strong>{{ $booking->service->name }}</strong> on 
            <strong>{{ \Carbon\Carbon::parse($booking->appointment_date)->format('F d, Y') }} at {{ \Carbon\Carbon::parse($booking->appointment_time)->format('g:i A') }}</strong> 
            has been <strong>cancelled</strong>.</p>

            <p>If you have any questions, please contact us.</p>
        @endif

        <p style="margin-top:20px;"><strong>Cha-cha Salon</strong></p>
    </div>
</body>
</html>