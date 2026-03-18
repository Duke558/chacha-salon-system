<x-admin-layout>

@push('styles')
<style>
    .admin-dashboard {
        padding: 40px 0;
        background: #FDF4F8;
        min-height: calc(100vh - 60px);
    }

    /* Header & Filter */
    .dashboard-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .dashboard-header h1 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 600;
        color: #333;
    }
    .dashboard-header form {
        display: flex;
        gap: 10px;
    }

    /* Statistic Cards */
    .stat-card {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 160px;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 10px;
        color: #fff;
    }
    .stat-icon.bookings { background: #1976d2; }
    .stat-icon.revenue { background: #9c27b0; }
    .stat-icon.services { background: #ff9800; }
    .stat-icon.users { background: #009688; }
    .stat-icon.feedbacks { background: #f44336; }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }
    .stat-label {
        font-size: 14px;
        color: #6c757d;
    }

    /* Data Table Cards */
    .data-table {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        margin-bottom: 25px;
    }
    .data-table h3 {
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        font-size: 18px;
    }
    .action-btn {
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        font-weight: 600;
    }

    /* Status Badges */
    .status-badge {
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 500;
        display: inline-block;
    }
    .status-pending { background: #fff3e0; color: #f57c00; }
    .status-confirmed { background: #e8f5e9; color: #2e7d32; }
    .status-cancelled { background: #ffebee; color: #c62828; }

    /* Tables */
    table thead th {
        background: #f8f9fa;
        font-weight: 600;
        font-size: 0.875rem;
        color: #6c757d;
    }
    table td {
        vertical-align: middle;
        font-size: 0.875rem;
        color: #495057;
    }
    .table-hover tbody tr:hover {
        background: #f1f3f5;
    }

    /* Responsive */
    @media (max-width: 1200px) { .stat-card { min-height: 150px; } }
    @media (max-width: 992px) { .stat-card { min-height: 140px; } }
    @media (max-width: 768px) {
        .stat-card { width: 100%; height: auto; margin-bottom: 20px; }
        .stat-value { font-size: 22px; }
        .stat-label { font-size: 13px; }
        .action-btn { font-size: 10px; padding: 3px 8px; }
        .table-responsive { overflow-x: auto; }
    }
</style>
@endpush

@section('content')
<div class="admin-dashboard">
    <div class="container">

        <!-- Dashboard Header with Date Filter -->
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <input type="date" name="date" value="{{ request('date', now()->toDateString()) }}" class="form-control form-control-sm">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            </form>
        </div>

        <!-- Statistic Cards -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="stat-card">
                    <div class="stat-icon bookings"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-value">{{ $todayBookings }}</div>
                    <div class="stat-label">Bookings</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="stat-card">
                    <div class="stat-icon revenue"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="stat-value">₱{{ number_format($totalRevenue, 2) }}</div>
                    <div class="stat-label">Revenue</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="stat-card">
                    <div class="stat-icon services"><i class="fas fa-cut"></i></div>
                    <div class="stat-value">{{ $totalServices }}</div>
                    <div class="stat-label">Services</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="stat-card">
                    <div class="stat-icon users"><i class="fas fa-users"></i></div>
                    <div class="stat-value">{{ $totalCustomers }}</div>
                    <div class="stat-label">Customers</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="stat-card">
                    <div class="stat-icon feedbacks"><i class="fas fa-comments"></i></div>
                    <div class="stat-value">{{ \App\Models\Feedback::count() }}</div>
                    <div class="stat-label">Feedbacks</div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="data-table">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Recent Bookings</h3>
                <a href="{{ route('admin.bookings') }}" class="btn btn-link">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>{{ $booking->full_name }}</td>
                            <td>{{ $booking->service->name }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($booking->appointment_date)->format('M d, Y') }}<br>
                                <small>{{ \Carbon\Carbon::parse($booking->appointment_time)->format('g:i A') }}</small>
                            </td>
                            <td>₱{{ number_format($booking->total_price, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary action-btn">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No recent bookings found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Popular Services -->
        <div class="data-table">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Popular Services</h3>
                <a href="{{ route('admin.services') }}" class="btn btn-link">Manage Services</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Bookings</th>
                            <th>Revenue</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($popularServices as $service)
                        <tr>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->category->name }}</td>
                            <td>₱{{ number_format($service->price, 2) }}</td>
                            <td>{{ $service->bookings_count }}</td>
                            <td>₱{{ number_format($service->revenue, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-outline-primary action-btn">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No services found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

</x-admin-layout>