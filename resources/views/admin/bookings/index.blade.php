<x-admin-layout>

@push('styles')
<style>
    .bookings-page { padding:40px 0;background:#FDF4F8;min-height:calc(100vh - 60px); }
    .page-header { margin-bottom:30px; }
    .page-header h2 { font-weight:600;font-size:1.75rem;color:#1f2937; }
    .search-box { max-width:420px;width:100%; }
    .btn-main { background:#d63384;color:#fff;border-radius:8px;padding:8px 18px;font-weight:500;border:none; }
    .btn-main:hover { background:#b71c58; }
    .filter-section { background:#fff0f6;padding:20px;border-radius:12px;margin-bottom:25px; }
    .table-wrapper { background:#fff;border-radius:12px;overflow:hidden; }
    table th { background:#ffe6f0;font-weight:600;padding:14px;font-size:14px; }
    table td { padding:14px;font-size:14px; }
    tbody tr:nth-child(even) { background:#fff5f9; }
    tbody tr:hover { background:rgba(214,51,132,0.05); }
    .status-badge { padding:5px 12px;border-radius:20px;font-size:12px;font-weight:500;text-transform:uppercase; }
    .status-pending { background:#fff3cd;color:#856404; }
    .status-confirmed { background:#d4edda;color:#155724; }
    .status-completed { background:#cce5ff;color:#004085; }
    .status-cancelled { background:#f8d7da;color:#721c24; }
    .status-rejected { background:#f5c6cb;color:#721c24; }
    .status-paid { background:#d4edda;color:#155724; }
    .status-failed { background:#f8d7da;color:#721c24; }
    .btn-icon { width:36px;height:36px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;border:none; }
    .btn-view { background:#d63384; }
    .btn-confirm { background:#28a745; }
    .btn-reject { background:#dc3545; }
    .btn-cancel { background:#fd7e14; }
    .btn-complete { background:#007bff; }
</style>
@endpush


@section('content')

<div class="bookings-page">
    <div class="container-fluid">

        <!-- HEADER + SEARCH -->
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap">

            <h2>Manage Bookings</h2>

            <form method="GET"
                  action="{{ route('admin.bookings') }}"
                  class="d-flex gap-2 search-box">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search name, email or phone..."
                    class="form-control"
                >

                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="payment_status" value="{{ request('payment_status') }}">

                <button type="submit" class="btn btn-main">
                    Search
                </button>

            </form>

        </div>


        <!-- FILTER -->
        <div class="filter-section">

            <form method="GET"
                  action="{{ route('admin.bookings') }}"
                  class="row g-3">

                <input type="hidden"
                       name="search"
                       value="{{ request('search') }}">

                <div class="col-md-6">

                    <label class="form-label">
                        Booking Status
                    </label>

                    <select name="status" class="form-select">

                        <option value="">
                            All Bookings
                        </option>

                        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>
                            Pending
                        </option>

                        <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>
                            Confirmed
                        </option>

                        <option value="completed" {{ request('status')=='completed'?'selected':'' }}>
                            Completed
                        </option>

                        <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>
                            Cancelled
                        </option>

                        <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>
                            Rejected
                        </option>

                    </select>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Payment Status
                    </label>

                    <select name="payment_status" class="form-select">

                        <option value="">
                            All Payments
                        </option>

                        <option value="pending" {{ request('payment_status')=='pending'?'selected':'' }}>
                            Pending
                        </option>

                        <option value="paid" {{ request('payment_status')=='paid'?'selected':'' }}>
                            Paid
                        </option>

                        <option value="failed" {{ request('payment_status')=='failed'?'selected':'' }}>
                            Failed
                        </option>

                    </select>

                </div>


                <div class="col-12 d-flex gap-2">

                    <button type="submit" class="btn btn-main">
                        Filter
                    </button>

                    <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">
                        Reset
                    </a>

                </div>

            </form>

        </div>



        <!-- TABLE -->
        <div class="table-wrapper">
            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($bookings as $index => $booking)

                        <tr>

                            <td>
                                {{ $bookings->firstItem() + $index }}
                            </td>

                            <td>{{ $booking->full_name }}</td>

                            <td>{{ $booking->email }}</td>

                            <td>+{{ $booking->phone }}</td>

                            <td>
                                {{ $booking->service->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($booking->appointment_date)->format('M d, Y') }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($booking->appointment_time)->format('g:i A') }}
                            </td>

                            <td>
                                ₱{{ number_format($booking->total_price,2) }}
                            </td>


                            <td>
                                <span class="status-badge status-{{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>


                            <td>

                                <span class="status-badge
                                    {{ $booking->status=='completed'
                                        ? 'status-paid'
                                        : 'status-'.$booking->payment_status }}">

                                    {{ $booking->status=='completed'
                                        ? 'Paid'
                                        : ucfirst($booking->payment_status) }}

                                </span>

                            </td>


                            <td>

                                <div class="d-flex gap-2">

                                    <a href="{{ route('admin.bookings.show',$booking->id) }}"
                                       class="btn-icon btn-view">
                                        <i class="fas fa-eye"></i>
                                    </a>


                                    @if($booking->status=='pending')

                                        <form action="{{ route('admin.bookings.confirm',$booking->id) }}" method="POST">
                                            @csrf
                                            <button class="btn-icon btn-confirm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                    @endif



                                    @if($booking->status=='confirmed')

                                        <form action="{{ route('admin.bookings.complete',$booking->id) }}" method="POST">
                                            @csrf
                                            <button class="btn-icon btn-complete">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        </form>

                                    @endif



                                    @if(!in_array($booking->status,['completed','cancelled','rejected']))

                                        <form action="{{ route('admin.bookings.cancel',$booking->id) }}" method="POST">
                                            @csrf
                                            <button class="btn-icon btn-cancel">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="11" class="text-center py-4">
                                No bookings found
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>


        <div class="mt-3">
            {{ $bookings->links() }}
        </div>


    </div>
</div>

@endsection

</x-admin-layout>