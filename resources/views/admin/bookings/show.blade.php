<x-admin-layout>
    @push('styles')
    <style>
        /* Page Background */
        .booking-page { padding: 40px 0; background: #FDF4F8; min-height: calc(100vh - 60px); }

        /* Card Style */
        .booking-card { background: #ffffff; border-radius: 16px; padding: 35px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); border: 1px solid #f3e6f0; }

        /* Page Title */
        .page-title { font-weight: 700; font-size: 24px; color: #1f2937; }

        /* Status Badge */
        .status-badge { padding: 6px 16px; border-radius: 30px; font-size: 12px; font-weight: 600; text-transform: uppercase; display: inline-block; }
        .status-pending { background: #ff91bd; color: #ffe7f3; }
        .status-confirmed { background: #c4ffb5; color: #2e7d32; }
        .status-cancelled { background: #ffe6e6; color: #c62828; }
        .status-completed { background: #e3f2fd; color: #1565c0; }
        .status-rejected { background: #f3f4f6; color: #374151; }
        .status-paid { background: #e8f5e9; color: #2e7d32; }
        .status-unpaid { background: #fff3e0; color: #e65100; }

        /* Section Titles */
        .section-title { font-weight: 700; font-size: 18px; margin-bottom: 20px; color: #111827; border-bottom: 2px solid #f3e6f0; padding-bottom: 8px; }

        /* Details */
        .detail-row { margin-bottom: 18px; }
        .detail-label { font-size: 13px; font-weight: 600; color: #6b7280; margin-bottom: 4px; }
        .detail-value { font-size: 15px; font-weight: 500; color: #111827; word-break: break-word; }

        .info-box { background: #FDF4F8; padding: 20px; border-radius: 12px; border: 1px solid #f3e6f0; }

        /* Action Buttons */
        .booking-actions { margin-top: 35px; display: flex; flex-wrap: wrap; gap: 12px; }
        .booking-actions .btn { border-radius: 30px; padding: 10px 26px; font-weight: 600; font-size: 14px; background-color: #d63384; color: #fff; border: none; transition: 0.2s ease; }
        .booking-actions .btn:hover { background-color: #b71c50; transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.1); }

        @media (max-width: 768px) { .booking-actions { flex-direction: column; } }
    </style>
    @endpush

    @section('content')
    <div class="booking-page">
        <div class="container-fluid">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title">Booking Details</h2>
                <a href="{{ route('admin.bookings') }}" class="btn btn-outline-dark">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="booking-card">

                        <!-- STATUS -->
                        <div class="mb-4">
                            <span class="status-badge {{ $booking->status === 'completed' ? 'status-paid' : 'status-'.$booking->status }}">
                                {{ $booking->status === 'completed' ? 'Paid' : ucfirst($booking->status) }}
                            </span>
                        </div>

                        <!-- CUSTOMER INFO -->
                        <div class="section-title">Customer Information</div>
                        <div class="row info-box mb-4">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">Full Name</div>
                                    <div class="detail-value">{{ $booking->full_name }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Email</div>
                                    <div class="detail-value">{{ $booking->email }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Phone</div>
                                    <div class="detail-value">{{ $booking->phone }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- SERVICE INFO -->
                        <div class="section-title">Service Information</div>
                        <div class="row info-box mb-4">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">Category</div>
                                    <div class="detail-value">{{ optional($booking->category)->name ?? 'N/A' }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Service</div>
                                    <div class="detail-value">{{ optional($booking->service)->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">Appointment Date</div>
                                    <div class="detail-value">{{ \Carbon\Carbon::parse($booking->appointment_date)->format('F j, Y') }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Appointment Time</div>
                                    <div class="detail-value">{{ \Carbon\Carbon::parse($booking->appointment_time)->format('g:i A') }}</div>
                                </div>
                            </div>
                        </div>

                        @if($booking->special_requirements)
                        <div class="section-title">Special Notes</div>
                        <div class="info-box mb-4">
                            <div class="detail-value">{{ $booking->special_requirements }}</div>
                        </div>
                        @endif

                        <!-- PAYMENT INFO -->
                        <div class="section-title">Payment Information</div>
                        <div class="row info-box">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">Base Price</div>
                                    <div class="detail-value">₱{{ number_format($booking->base_price ?? 0, 2) }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Total Amount</div>
                                    <div class="detail-value fw-bold">₱{{ number_format($booking->total_price ?? 0, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">Payment Status</div>
                                    <div class="detail-value">
                                        <span class="status-badge {{ $booking->status === 'completed' ? 'status-paid' : 'status-'.$booking->payment_status }}">
                                            {{ $booking->status === 'completed' ? 'Paid' : ucfirst($booking->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ACTION BUTTONS -->
                        @if(!in_array($booking->status, ['completed','cancelled','rejected']))
                        <div class="booking-actions">
                            @if($booking->status === 'pending')
                                <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST">@csrf
                                    <button type="submit" class="btn">Confirm</button>
                                </form>

                                <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST">@csrf
                                    <button type="submit" class="btn">Reject</button>
                                </form>
                            @endif

                            <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">@csrf
                                <button type="submit" class="btn">Cancel</button>
                            </form>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
    @endsection
</x-admin-layout>