<x-admin-layout>

    @section('content')
    <div class="container py-4">
        <!-- Page Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Manage Feedbacks</h2>
        </div>

        

        <!-- Feedbacks Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Service</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feedbacks as $feedback)
                            <tr>
                                <td>{{ $feedback->id }}</td>
                                <td>{{ $feedback->user->name }}</td>
                                <td>{{ $feedback->booking->service->name }}</td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </td>
                                <td>{{ \Str::limit($feedback->comment, 50) }}</td>
                                <td>{{ $feedback->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge {{ $feedback->is_published ? 'bg-success' : 'bg-warning' }}">
                                        {{ $feedback->is_published ? 'Published' : 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.feedback.toggle-publish', $feedback) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $feedback->is_published ? 'btn-warning' : 'btn-success' }}">
                                            <i class="fas {{ $feedback->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                            {{ $feedback->is_published ? 'Unpublish' : 'Publish' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No feedbacks found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3 d-flex justify-content-end">
                    {{ $feedbacks->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .badge {
            font-weight: 500;
            padding: 0.4em 0.7em;
            font-size: 0.85rem;
        }

        /* Responsive for small devices */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .table th, .table td {
                font-size: 0.85rem;
            }

            .btn {
                font-size: 0.75rem;
                padding: 4px 8px;
            }
        }
    </style>
    @endsection

</x-admin-layout>