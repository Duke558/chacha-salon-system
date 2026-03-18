<x-admin-layout>
    @push('styles')
    <style>
        .services-page {
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

        /* Image preview */
        .current-service-image {
            width: 180px;
            height: 130px;
            object-fit: cover;
            border-radius: 15px;
            margin-right: 15px;
            margin-bottom: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .current-service-image:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 25px rgba(214, 51, 132, 0.3);
        }

        /* Buttons */
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
    </style>
    @endpush

    @section('content')
    <div class="services-page">
        <div class="container">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Edit Service</h1>
                <div class="actions">
                    <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Services
                    </a>
                </div>
            </div>

            <!-- Service Form -->
            <div class="form-card">
                <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Service Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Service Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $service->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Category -->
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $service->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Description -->
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="3" required>{{ old('description', $service->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Duration + Price -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Service Duration</label>
                            <div class="d-flex gap-2">
                                <select name="hours" class="form-select @error('hours') is-invalid @enderror" required>
                                    @for ($i = 0; $i <= 11; $i++)
                                        <option value="{{ $i }}" {{ old('hours', $hours) == $i ? 'selected' : '' }}>{{ $i }} hr</option>
                                    @endfor
                                </select>
                                <input type="number" name="minutes" class="form-control @error('minutes') is-invalid @enderror"
                                    placeholder="Minutes" min="0" max="59" value="{{ old('minutes', $minutes) }}" required>
                            </div>
                            @error('hours')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            @error('minutes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price (PHP)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                id="price" name="price" value="{{ old('price', $service->price) }}" min="0" step="0.01" required>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                                    {{ old('status', $service->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active Status</label>
                            </div>
                        </div>

                        <!-- Image Upload + Preview -->
                        <div class="col-md-12 mb-3">
                            <label for="image" class="form-label">Service Image</label>

                            <!-- Current Image -->
                            @if($service->image)
                                <div class="mb-3">
                                    <p class="mb-2 text-muted">Current Image:</p>
                                    <img src="{{ asset('storage/' . $service->image) }}" 
                                         class="current-service-image" id="currentImagePreview">
                                </div>
                            @endif

                            <!-- Upload New Image -->
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                id="image" name="image" accept="image/*">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror

                            <!-- Preview New Image -->
                            <div class="mt-3">
                                <img id="imagePreview" class="current-service-image d-none">
                            </div>
                        </div>

                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if(file){
                    imagePreview.src = URL.createObjectURL(file);
                    imagePreview.classList.remove('d-none');
                } else {
                    imagePreview.src = '';
                    imagePreview.classList.add('d-none');
                }
            });
        });
    </script>
    @endpush
    @endsection
</x-admin-layout>