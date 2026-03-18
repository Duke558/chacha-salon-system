<x-admin-layout>
    @push('styles')
    <style>
        .categories-page {
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

        .btn-outline-primary, .btn-outline-danger {
            border-radius: 30px;
            padding: 5px 15px;
            font-size: 12px;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .btn-outline-primary:hover, .btn-outline-danger:hover {
            transform: translateY(-2px);
        }

        .badge {
            font-weight: 500;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .badge-success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .badge-danger {
            background: #ffebee;
            color: #c62828;
        }

        tr:hover {
            background: rgba(214, 51, 132, 0.05);
        }

        .btn-group .ms-2 {
            margin-left: 8px !important;
        }
    </style>
    @endpush

    @section('content')
    <div class="categories-page">
        <div class="container">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Service Categories</h1>
                <div class="actions">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Category
                    </a>
                </div>
            </div>

            <!-- Categories Table -->
            <div class="data-table">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Start Price (PHP)</th>
                                <th>Services Count</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ Str::limit($category->description, 50) }}</td>
                                <td>₱ {{ number_format($category->start_price, 2) }}</td>
                                <td>{{ $category->services_count }}</td>
                                <td>
                                    <span class="badge {{ $category->status ? 'badge-success' : 'badge-danger' }}">
                                        {{ $category->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                            class="btn btn-outline-primary">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this category?');"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger ms-2">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No categories found</td>
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