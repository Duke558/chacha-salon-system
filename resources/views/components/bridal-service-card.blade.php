<div class="service-package-card">
    <!-- Image at top in a circle -->
    @if($image)
    <div class="package-image mx-auto mb-4">
        <img src="{{ asset($image) }}" alt="{{ $title }}">
    </div>
    @endif

    <div class="package-header">
        <h3>{{ $title }}</h3>
        <span class="price">Starting from {{ $price }}</span>
        <span class="duration">Duration: {{ $duration }}</span>
    </div>
    <div class="package-content">
        <ul>
            @foreach($features as $feature)
            <li>
                <i class="fas fa-check"></i> {{ $feature }}
            </li>
            @endforeach
        </ul>
        <a href="{{ route('booking') }}?service={{ $serviceId }}" class="btn btn-book" data-package="{{ $packageType }}">
            Book Package
        </a>
    </div>
</div>

@push('styles')
<style>
    .service-package-card {
        border-radius: 15px;
        padding: 30px;
        height: 100%;
        transition: transform 0.3s ease;
        background-color: #1a1a1a; /* just to make dark bg consistent */
    }

    .service-package-card:hover {
        transform: translateY(-10px);
    }

    .package-image img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        display: block;
    }

    .package-header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 215, 0, 0.2);
    }

    .package-header h3 {
        color: #ffd700;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .package-header .price {
        display: block;
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .package-header .duration {
        color: #999;
        font-size: 0.9rem;
    }

    .package-content ul {
        list-style: none;
        padding: 0;
        margin: 0 0 25px 0;
    }

    .package-content ul li {
        color: #ffffff;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
    }

    .package-content ul li i {
        color: #ffd700;
        margin-right: 10px;
        font-size: 0.8rem;
    }

    .btn-book {
        width: 100%;
        color: #000;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-book:hover {
        background-color: #fff;
        color: #000;
        transform: translateY(-2px);
    }
</style>
@endpush
