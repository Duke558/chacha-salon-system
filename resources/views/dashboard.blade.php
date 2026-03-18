<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <!-- Add Bootstrap CSS if not already included in the layout -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-image {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 35px;
            height: 35px;
            background: #D4AF37;
            border: none;
            border-radius: 50%;
            color: #fff;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            background: #E6B800;
            transform: scale(1.1);
        }

        /* Dark theme input styles */
        .form-control {
            background-color: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.12);
            border-color: #D4AF37;
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
            color: #fff;
        }

        .form-control:disabled {
            background-color: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-group label {
            color: #fff;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        /* Stats cards styling */
        .action-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            height: 100%;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: all 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            background: #D4AF37;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .action-icon i {
            font-size: 1.5rem;
            color: #fff;
        }

        .action-card h4 {
            font-size: 1.5rem;
            color: #fff;
            margin: 0.5rem 0;
            font-weight: 600;
        }

        .action-card p {
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
            font-size: 0.9rem;
        }

        /* Status badges */
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 8px;
        }

        .status.pending {
            background: #fff3e0;
            color: #e65100;
        }

        .status.confirmed {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status.cancelled {
            background: #ffebee;
            color: #c62828;
        }

        .status.completed {
            background: #e3f2fd;
            color: #1565c0;
        }

        /* Action buttons */
        .btn-cancel {
            background: #ffebee;
            color: #c62828;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #ef5350;
            color: white;
        }

        .btn-reschedule {
            background: #e3f2fd;
            color: #1565c0;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .btn-reschedule:hover {
            background: #1e88e5;
            color: white;
        }

        .btn-review {
            background: #e8f5e9;
            color: #2e7d32;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .btn-review:hover {
            background: #43a047;
            color: white;
        }
        .btn-save {
            background-color: #ffffff;
            color: #d63384;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            background-color: #d63384;
            color: #ffffff;
        }
        /* Appointment Filter Buttons - Brand Pink */
        .btn-filter {
            background-color: rgba(255, 105, 180, 0.1);
            color: #ff69b4;
            border: 1px solid #ff69b4;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            background-color: #ff69b4;
            color: #ffffff;
        }

        .btn-filter.active {
            background-color: #ff69b4;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3);
        }
        /* Book Appointment Button - Brand Pink */
        .btn-book-appointment {
            background-color: #ff69b4;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-book-appointment:hover {
            background-color: #e055a1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3);
            color: #ffffff;
        }
        /* =========================================
   MOBILE RESPONSIVE FIX
========================================= */
@media (max-width: 768px) {

    /* Sidebar full width */
    .dashboard-sidebar {
        margin-bottom: 20px;
    }

    /* Dashboard header stack */
    .dashboard-header {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 10px;
    }

    .dashboard-header h2 {
        font-size: 1.3rem;
    }

    /* Action Cards smaller */
    .action-card {
        padding: 1rem;
        min-height: auto;
    }

    .action-card h4 {
        font-size: 1.2rem;
    }

    .action-card p {
        font-size: 0.8rem;
    }

    .action-icon {
        width: 40px;
        height: 40px;
    }

    .action-icon i {
        font-size: 1.2rem;
    }

    /* Appointment items stack vertically */
    .appointment-item {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 10px;
    }

    .appointment-date {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .appointment-info h4 {
        font-size: 1rem;
    }

    .appointment-info p {
        font-size: 0.85rem;
    }

    /* Filter buttons wrap */
    .appointment-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .btn-filter {
        font-size: 0.8rem;
        padding: 5px 12px;
    }

    /* Profile image smaller */
    .profile-image {
        width: 90px;
        height: 90px;
    }

    /* Buttons full width on mobile */
    .btn-save,
    .btn-book-appointment {
        width: 100%;
        justify-content: center;
    }
}




    </style>
    @endpush

    @push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================
       FIX: View All Tab Switch
    ========================== */
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            const targetTab = new bootstrap.Tab(this);
            targetTab.show();
        });
    });


    /* =========================
       Appointment Filtering
    ========================== */
    const filterButtons = document.querySelectorAll('.btn-filter');

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {

            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            const appointments = document.querySelectorAll('#appointments .appointment-item');

            appointments.forEach(item => {

                const statusElement = item.querySelector('.status');
                const status = statusElement ? statusElement.textContent.toLowerCase() : '';

                if (filter === 'all') {
                    item.style.display = 'flex';
                }
                else if (filter === 'upcoming' && (status === 'pending' || status === 'confirmed')) {
                    item.style.display = 'flex';
                }
                else if (filter === 'past' && status === 'completed') {
                    item.style.display = 'flex';
                }
                else if (filter === 'cancelled' && status === 'cancelled') {
                    item.style.display = 'flex';
                }
                else {
                    item.style.display = 'none';
                }
            });
        });
    });


    /* =========================
       Cancel Booking
    ========================== */
    $('.btn-cancel').click(function() {

        if (confirm('Are you sure you want to cancel this booking?')) {

            const appointmentItem = $(this).closest('.appointment-item');
            const bookingId = appointmentItem.data('booking-id');

            $.ajax({
                url: `/bookings/${bookingId}/cancel`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function() {
                    window.location.reload();
                },
                error: function() {
                    alert('Failed to cancel booking. Please try again.');
                }
            });
        }
    });


    /* =========================
       Reschedule Booking
    ========================== */
    $('.btn-reschedule').click(function(e) {

        e.preventDefault();

        const appointmentItem = $(this).closest('.appointment-item');
        const bookingId = appointmentItem.data('booking-id');

        if (confirm('Do you want to extend your appointment by 1 day?')) {

            $.ajax({
                url: `/bookings/${bookingId}/reschedule`,
                type: 'POST',
                data: {
                    extend_days: 1
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function() {
                    window.location.reload();
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.message) {
                        alert(error.responseJSON.message);
                    } else {
                        alert('Failed to reschedule booking. Please try again.');
                    }
                }
            });
        }
    });

});
</script>
@endpush




    @section('content')
    <!-- Dashboard Section -->
    <section class="dashboard-section">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3">
                    <div class="dashboard-sidebar">
                        <div class="text-center user-profile">
                            <form id="profile-photo-form" action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="profile-image">
                                    <img
                                        src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : asset('assets/img/default-avatar.jpg') }}"
                                        alt="Profile"
                                        class="img-fluid rounded-circle" />
                                    <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*" onchange="this.form.submit()">
                                    <button type="button" class="upload-btn" title="Change Photo" onclick="document.getElementById('profile_photo').click();">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                            </form>
                            @if(session('success'))
                            <div class="mt-2 alert alert-success">
                                {{ session('success') }}
                            </div>
                            @endif
                            @if($errors->any())
                            <div class="mt-2 alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                            @endif
                            <h4 class="mt-3">{{ $user->name }}</h4>
                            <p class="member-since">Member since {{ $user->created_at->format('F Y') }}</p>
                        </div>
                        <nav class="dashboard-nav">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a
                                        class="nav-link active"
                                        href="#overview"
                                        data-bs-toggle="tab">
                                        <i class="fas fa-th-large"></i> Dashboard Overview
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link"
                                        href="#appointments"
                                        data-bs-toggle="tab">
                                        <i class="fas fa-calendar-alt"></i> My Appointments
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#profile" data-bs-toggle="tab">
                                        <i class="fas fa-user-edit"></i> Profile Settings
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="tab-content">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview">
                            <div class="dashboard-header">
                                <h2>My Dashboard</h2>
                                <small class="text-light">
                                    Welcome back, {{ $user->name }}!
                                </small>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="quick-actions mb-4">
                                <div class="row g-4">

                                    <!-- Upcoming Appointments -->
                                    <div class="col-md-6 col-lg-3">
                                        <div class="action-card">
                                            <div class="action-icon" style="background: #2196F3;">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                            <h4>{{ $upcomingAppointments->count() }}</h4>
                                            <p>Upcoming Appointments</p>
                                        </div>
                                    </div>

                                    <!-- Past Appointments -->
                                    <div class="col-md-6 col-lg-3">
                                        <div class="action-card">
                                            <div class="action-icon" style="background: #9C27B0;">
                                                <i class="fas fa-history"></i>
                                            </div>
                                            <h4>{{ $pastAppointments->count() }}</h4>
                                            <p>Past Appointments</p>
                                        </div>
                                    </div>

                                    <!-- Total Visits -->
                                    <div class="col-md-6 col-lg-3">
                                        <div class="action-card">
                                            <div class="action-icon" style="background: #FF9800;">
                                                <i class="fas fa-user-check"></i>
                                            </div>

                                            <h4>{{ $totalVisits }}</h4>
                                            <p>Total Visits</p>

                                            <br>

                                            <h6>Visits Today: {{ $visitsToday }}</h6>
                                        </div>
                                    </div>

                                    <!-- Available Services -->
                                    <div class="col-md-6 col-lg-3">
                                        <div class="action-card">
                                            <div class="action-icon" style="background: #4CAF50;">
                                                <i class="fas fa-spa"></i>
                                            </div>
                                            <h4>{{ $servicesCount ?? 0 }}</h4>
                                            <p>Available Services</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Upcoming Appointments -->
                            <div class="mt-4 section-card">
                                <div class="card-header">
                                    <h3>Upcoming Appointments</h3>
                                    <a
                                        href="#appointments"
                                        class="view-all"
                                        data-bs-toggle="tab">View All</a>
                                </div>
                                <div class="appointment-list">
                                    @forelse($upcomingAppointments as $appointment)
                                    <div class="appointment-item" data-booking-id="{{ $appointment->id }}">
                                        <div class="appointment-date">
                                            <span class="date">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d') }}</span>
                                            <span class="month">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M') }}</span>
                                        </div>
                                        <div class="appointment-info">
                                            <h4>{{ $appointment->service->name }}</h4>
                                            <p><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
                                            <p>₱<i class="fas fa-money-bill"></i> {{ number_format($appointment->total_price, 2) }}</p>
                                            <span class="status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                                        </div>
                                        <div class="appointment-actions">
                                            @if($appointment->status === 'pending')
                                            <button class="btn btn-cancel">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                            @elseif($appointment->status === 'confirmed')
                                            <button class="btn btn-reschedule">
                                                <i class="fas fa-calendar-alt"></i> Reschedule
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="py-4 text-center">
                                        <p>No upcoming appointments</p>
                                        <a href="{{ route('services') }}" class="mt-2 btn btn-primary">Book Now</a>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Appointments Tab -->
                        <div class="tab-pane fade" id="appointments">
                            <div class="dashboard-header">
                                <h2>My Appointments</h2>
                                <div class="appointment-filters">
                                    <button class="btn btn-filter active" data-filter="all">All</button>
                                    <button class="btn btn-filter" data-filter="upcoming">Upcoming</button>
                                    <button class="btn btn-filter" data-filter="past">Past</button>
                                    <button class="btn btn-filter" data-filter="cancelled">Cancelled</button>
                                </div>
                            </div>
                            <div class="appointments-timeline">
                                <!-- Upcoming Appointments -->
                                <div class="mb-4 timeline-section">
                                    <h3>Upcoming Appointments</h3>
                                    @forelse($upcomingAppointments as $appointment)
                                    <div class="appointment-item" data-booking-id="{{ $appointment->id }}">
                                        <div class="appointment-date">
                                            <span class="date">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d') }}</span>
                                            <span class="month">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M') }}</span>
                                        </div>
                                        <div class="appointment-info">
                                            <h4>{{ $appointment->service->name }}</h4>
                                            <p><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
                                            <p>₱<i class="fas fa-money-bill"></i> {{ number_format($appointment->total_price, 2) }}</p>
                                            <span class="status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                                        </div>
                                        <div class="appointment-actions">
                                            @if($appointment->status === 'pending')
                                            <button class="btn btn-cancel">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                            @elseif($appointment->status === 'confirmed')
                                            <button class="btn btn-reschedule">
                                                <i class="fas fa-calendar-alt"></i> Reschedule
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="py-4 text-center">
                                        <p>No upcoming appointments</p>
                                        <a href="{{ route('services') }}" class="mt-2 btn btn-primary">Book Now</a>
                                    </div>
                                    @endforelse
                                </div>

                                <!-- Past Appointments -->
                                <div class="timeline-section">
                                    <h3>Past Appointments</h3>
                                    @forelse($pastAppointments as $appointment)
                                    <div class="appointment-item {{ $appointment->status }}" data-booking-id="{{ $appointment->id }}">
                                        <div class="appointment-date">
                                            <span class="date">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d') }}</span>
                                            <span class="month">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M') }}</span>
                                        </div>
                                        <div class="appointment-info">
                                            <h4>{{ $appointment->service->name }}</h4>
                                            <p><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
                                            <p>₱<i class="fas fa-money-bill"></i> {{ number_format($appointment->total_price, 2) }}</p>
                                            <span class="status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                                        </div>
                                        <div class="appointment-actions">
                                            @if($appointment->status === 'completed')
                                            @if(!$appointment->feedback)
                                            <a href="{{ route('feedback.create', $appointment->id) }}"
                                                class="btn btn-primary btn-sm me-2">
                                                <i class="fas fa-star"></i> Write Review
                                            </a>
                                            @else
                                            <span class="text-success"><i class="fas fa-check"></i> Review Submitted</span>
                                            @endif
                                            <button class="btn btn-secondary btn-sm btn-rebook">Book Again</button>
                                            @endif
                                        </div>
                                    </div>
                                    @empty
                                    <div class="py-4 text-center">
                                        <p>No past appointments</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Profile Tab -->
                        <div class="tab-pane fade" id="profile">
                            <div class="dashboard-header">
                                <h2>Profile Settings</h2>
                            </div>
                            <div class="section-card">
                                <form id="profileForm" class="profile-form">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="firstName">Name</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="firstName"
                                                    value="{{ $user->name }}"
                                                    disabled />
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email Address</label>
                                                <input
                                                    type="email"
                                                    class="form-control"
                                                    id="email"
                                                    value="{{ $user->email }}"
                                                    disabled />
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </form>
                            </div>

                            <!-- Password Change Section -->
                            <div class="mt-4 section-card">
                                <h3>Change Password</h3>
                                <form id="passwordForm" class="password-form">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="currentPassword">Current Password</label>
                                                <input
                                                    type="password"
                                                    class="form-control"
                                                    id="currentPassword" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="newPassword">New Password</label>
                                                <input
                                                    type="password"
                                                    class="form-control"
                                                    id="newPassword" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="confirmNewPassword">Confirm New Password</label>
                                                <input
                                                    type="password"
                                                    class="form-control"
                                                    id="confirmNewPassword" />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-save">
                                                Update Password
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
</x-app-layout>