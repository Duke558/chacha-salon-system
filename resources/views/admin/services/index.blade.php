<x-admin-layout>
    @push('styles')
    <style>
        .services-page {
            padding: 40px 0;
            background: #FDF4F8;
            min-height: calc(100vh - 60px);
        }

        .data-table {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        table th, table td {
            vertical-align: middle;
        }

        .action-btn {
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            transition: 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-active {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-inactive {
            background: #ffebee;
            color: #c62828;
        }

        .btn-primary {
            background-color: #D63384;
            border: 2px solid #D63384;
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .btn-primary:hover {
            background-color: transparent;
            color: #D63384;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(214, 51, 132, 0.3);
        }

        tr:hover {
            background: rgba(214, 51, 132, 0.05);
        }

        /* Optional: make description full visible */
        .service-description {
            font-size: 13px;
            color: #555;
        }
    </style>
    @endpush

    @section('content')
    <div class="services-page">
        <div class="container">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Manage Services</h1>
                <div class="actions">
                    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Service
                    </a>
                </div>
            </div>

            <!-- Services Table -->
            <div class="data-table">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Category</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Bookings</th>
                                <th>Revenue</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-start">
                                        @if($service->image)
                                            <img src="{{ asset('storage/' . $service->image) }}" 
                                                 alt="{{ $service->name }}" 
                                                 style="width:60px; height:45px; object-fit:cover; border-radius:8px; margin-right:10px;">
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $service->name }}</div>
                                            <div class="service-description">{{ $service->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $service->category->name }}</td>
                                <td>
                                    @php
                                        $hours = intdiv($service->duration, 60);
                                        $minutes = $service->duration % 60;
                                    @endphp
                                    @if($hours > 0) {{ $hours }} hr @endif
                                    @if($minutes > 0) {{ $minutes }} min @endif
                                    @if($hours == 0 && $minutes == 0) 0 min @endif
                                </td>
                                <td>₱{{ number_format($service->price, 2) }}</td>
                                <td>{{ $service->bookings_count }}</td>
                                <td>₱{{ number_format($service->revenue ?? 0, 2) }}</td>
                                <td>
                                    <span class="status-badge status-{{ $service->status ? 'active' : 'inactive' }}">
                                        {{ $service->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.services.edit', $service->id) }}"
                                           class="btn btn-sm btn-outline-primary action-btn me-2">
                                           Edit
                                        </a>

                                        <!-- Delete Button triggers modal -->
                                        <button type="button" class="btn btn-sm btn-outline-danger action-btn"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $service->id }}">
                                            Delete
                                        </button>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $service->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $service->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $service->id }}">Delete Service</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete the service "<strong>{{ $service->name }}</strong>"?
                                                        @if($service->bookings_count > 0)
                                                            <br><span class="text-danger">Warning: This service has bookings. Deleting it may affect records.</span>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Delete Modal -->

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No services found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <!-- Bootstrap JS (make sure it's included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endpush
</x-admin-layout>