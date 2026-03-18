<x-admin-layout>
    @push('styles')
    <style>
        .categories-page {
            padding: 40px 0;
            background: #FDF4F8;
            min-height: calc(100vh - 60px);
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(214, 51, 132, 0.1);
        }

        .form-label {
            font-weight: 600;
        }

        .form-control,
        .form-check-input {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            font-size: 14px;
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

        .text-end {
            margin-top: 20px;
        }

        .btn-outline-secondary {
            border-radius: 30px;
            padding: 6px 20px;
            transition: 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #D63384;
            color: white;
            border-color: #D63384;
        }
    </style>
    @endpush

    @section('content')
    <div class="categories-page">
        <div class="container">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Edit Category</h1>
                <div class="actions">
                    <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Categories
                    </a>
                </div>
            </div>

            <!-- Category Form -->
            <div class="form-card">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <!-- Category Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Start Price -->
                        <div class="col-md-6">
                            <label for="start_price" class="form-label">Start Price (PHP)</label>
                            <input type="number" class="form-control @error('start_price') is-invalid @enderror"
                                id="start_price" name="start_price" 
                                value="{{ old('start_price', $category->start_price) }}" min="0" step="0.01" required>
                            @error('start_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="3" required>{{ old('description', $category->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                                    {{ old('status', $category->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Active Status
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
</x-admin-layout>