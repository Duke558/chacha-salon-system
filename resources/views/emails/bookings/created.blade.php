@component('mail::message')
# Booking Received

Hello {{ $booking->full_name }},

Thank you for booking with **Cha-cha Salon**  

Here are your booking details:

**Service:** {{ $booking->service->name }}  
**Date:** {{ $booking->appointment_date }}  
**Time:** {{ $booking->appointment_time }}  
**Status:** Pending Approval  

Our team will review your booking and notify you once it is confirmed.

Thanks,  
**{{ config('app.name') }}**
@endcomponent
