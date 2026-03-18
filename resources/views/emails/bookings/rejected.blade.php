@component('mail::message')
# Booking Rejected

Hello {{ $booking->full_name }},

We’re sorry to inform you that your booking for:

**Service:** {{ $booking->service->name }}  
**Date:** {{ $booking->appointment_date }}  
**Time:** {{ $booking->appointment_time }}

has been **rejected** by the salon.

If you have questions or want to book again, please contact us.

Thanks,  
**{{ config('app.name') }}**
@endcomponent
