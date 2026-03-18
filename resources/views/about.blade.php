<x-app-layout>
    @section("content")

    <!-- Google Luxury Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
    /* ===== HERO SECTION ===== */
    .hero-section {
        position: relative;
        margin-top: 90px;
        padding: 140px 0 120px;
        background-image: url('{{ asset("assets/img/about/salon-interior.jpg") }}');
        background-size: cover;
        background-position: center;
        text-align: center;
        color: #fff;
    }
    .hero-section::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45));
        z-index: 1;
    }
    .hero-section .container { position: relative; z-index: 2; }
    .hero-section h1 { font-family: 'Great Vibes', cursive; font-size: 4rem; margin-bottom: 15px; }
    .hero-section p { font-family: 'Playfair Display', serif; font-size: 1.4rem; letter-spacing: 1px; opacity: 0.95; }
    @media (max-width: 768px) {
        .hero-section { margin-top: 80px; padding: 100px 20px; }
        .hero-section h1 { font-size: 2.6rem; }
        .hero-section p { font-size: 1.1rem; }
    }
    </style>

    <!-- Hero Section -->
    <section class="about-hero hero-section">
        <div class="container">
            <h1>About ChaCha Salon</h1>
            <p>Your Beauty, Our Passion</p>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="our-story py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="{{ asset('assets/img/about/salon-interior.jpg') }}" alt="Salon Interior" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6">
                    <h2>Our Story</h2>
                    <p>
                        Founded in Nov. 8, 2017, Chacha Salon has grown from a modest beginning to
                        become one of the most trusted names in beauty care in Ormoc City.
                        Our journey began with a simple vision: to provide exceptional
                        beauty services that make every client feel confident and beautiful.
                    </p>
                    <h3>Our Mission</h3>
                    <p>
                        To deliver outstanding beauty services that enhance our clients'
                        natural beauty while maintaining the highest standards of
                        professionalism, hygiene, and customer satisfaction.
                    </p>
                    <h3>Our Vision</h3>
                    <p>
                        To be the leading beauty salon in Ormoc City, recognized for our
                        expertise, innovation, and commitment to excellence in beauty care.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Reviews Section -->
<section class="customer-reviews py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">What Our Customers Say</h2>

        <div class="row" id="reviewsContainer">
            @foreach($reviews->take(6) as $review) <!-- show first 6 by default -->
            <div class="col-md-4 mb-4 review-item">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="card-text">{{ $review->comment }}</p>
                        <footer class="blockquote-footer mt-3">
                            {{ $review->user->name ?? 'Anonymous' }}
                            <cite><small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small></cite>
                        </footer>
                        <p class="text-muted mt-2">
                            <strong>Service:</strong> {{ $review->booking->service->name ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($totalReviews > 6)
        <div class="text-center mt-4 d-flex justify-content-center gap-2">
    <!-- Load More Button -->
    <button id="loadMoreBtn" 
            class="btn rounded-pill px-4 py-2 shadow-sm" 
            style="background-color: #D63384; color: #fff; border: none;">
        Load More Reviews
    </button>

    <!-- Minimize / Show Less Button -->
    <button id="showLessBtn" 
            class="btn rounded-pill px-4 py-2 shadow-sm" 
            style="background-color: #fff; color: #D63384; border: 2px solid #D63384; display: none;">
        Show Less
    </button>
</div>
        @endif
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let skip = 6; // initial number of reviews shown
    const loadBtn = document.getElementById('loadMoreBtn');
    const showLessBtn = document.getElementById('showLessBtn');
    const container = document.getElementById('reviewsContainer');
    const totalReviews = {{ $totalReviews }};

    if(loadBtn){
        loadBtn.addEventListener('click', function(){
            fetch(`{{ route('reviews.loadMore') }}?skip=${skip}`)
            .then(res => res.json())
            .then(data => {
                if(data.length > 0){
                    data.forEach(review => {
                        const stars = Array.from({length: 5}, (_, i) => i < review.rating 
                            ? '<i class="fas fa-star text-warning"></i>' 
                            : '<i class="far fa-star text-warning"></i>').join('');

                        const serviceName = review.booking && review.booking.service 
                                            ? review.booking.service.name 
                                            : 'N/A';

                        const reviewHtml = `
                        <div class="col-md-4 mb-4 review-item">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="mb-3">${stars}</div>
                                    <p class="card-text">${review.comment}</p>
                                    <footer class="blockquote-footer mt-3">
                                        ${review.user ? review.user.name : 'Anonymous'}
                                        <cite><small class="text-muted">${new Date(review.created_at).toLocaleDateString()}</small></cite>
                                    </footer>
                                    <p class="text-muted mt-2"><strong>Service:</strong> ${serviceName}</p>
                                </div>
                            </div>
                        </div>`;
                        container.insertAdjacentHTML('beforeend', reviewHtml);
                    });

                    skip += data.length;

                    // Show less button
                    showLessBtn.style.display = 'inline-block';

                    // hide load more if all reviews shown
                    if(skip >= totalReviews) {
                        loadBtn.style.display = 'none';
                    }
                }
            });
        });
    }

    // Show Less / Minimize button functionality
    if(showLessBtn){
        showLessBtn.addEventListener('click', function(){
            // keep only first 6 reviews
            const reviewItems = container.querySelectorAll('.review-item');
            reviewItems.forEach((item, index) => {
                if(index >= 6){
                    item.remove();
                }
            });

            // Reset skip counter and buttons
            skip = 6;
            loadBtn.style.display = 'inline-block';
            showLessBtn.style.display = 'none';
        });
    }
});
</script>
@endpush

    <!-- Our Values Section -->
    <section class="our-values py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Values</h2>
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="value-card text-center">
                        <i class="fas fa-gem fa-3x mb-3"></i>
                        <h3>Quality Service</h3>
                        <p>We maintain the highest standards in all our services, using premium products and advanced techniques.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="value-card text-center">
                        <i class="fas fa-heart fa-3x mb-3"></i>
                        <h3>Customer First</h3>
                        <p>Your satisfaction is our priority. We listen, understand, and deliver exactly what you desire.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="value-card text-center">
                        <i class="fas fa-shield-alt fa-3x mb-3"></i>
                        <h3>Safety & Hygiene</h3>
                        <p>We maintain strict hygiene protocols and use sanitized equipment for every service.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="value-card text-center">
                        <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                        <h3>Continuous Learning</h3>
                        <p>We regularly update our skills and knowledge to bring you the latest trends and techniques.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="facilities py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Our Facilities</h2>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3>Modern & Comfortable Environment</h3>
                    <ul class="facility-list">
                        <li><i class="fas fa-check-circle"></i> State-of-the-art equipment and tools</li>
                        <li><i class="fas fa-check-circle"></i> Comfortable seating and relaxation area</li>
                        <li><i class="fas fa-check-circle"></i> Private treatment rooms for personal services</li>
                        <li><i class="fas fa-check-circle"></i> Well-ventilated and clean spaces</li>
                        <li><i class="fas fa-check-circle"></i> Convenient location with parking facilities</li>
                    </ul>
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <img src="{{ asset('assets/img/about/service-1.jpg') }}" alt="Salon Facilities" class="img-fluid rounded shadow" />
                </div>
            </div>
        </div>
    </section>

    @endsection

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let skip = {{ $reviews->count() }};
        const loadBtn = document.getElementById('loadMoreBtn');
        const container = document.getElementById('reviewsContainer');

        if(loadBtn){
            loadBtn.addEventListener('click', function(){
                fetch(`{{ route('reviews.loadMore') }}?skip=${skip}`)
                .then(res => res.json())
                .then(data => {
                    if(data.length > 0){
                        data.forEach(review => {
                            const stars = Array.from({length: 5}, (_, i) => i < review.rating 
                                ? '<i class="fas fa-star text-warning"></i>' 
                                : '<i class="far fa-star text-warning"></i>').join('');

                            const serviceName = review.booking && review.booking.service 
                                                ? review.booking.service.name 
                                                : 'N/A';

                            const reviewHtml = `
                            <div class="col-md-4 mb-4 review-item">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body">
                                        <div class="mb-3">${stars}</div>
                                        <p class="card-text">${review.comment}</p>
                                        <footer class="blockquote-footer mt-3">
                                            ${review.user ? review.user.name : 'Anonymous'}
                                            <cite><small class="text-muted">${new Date(review.created_at).toLocaleDateString()}</small></cite>
                                        </footer>
                                        <p class="text-muted mt-2"><strong>Service:</strong> ${serviceName}</p>
                                    </div>
                                </div>
                            </div>`;
                            container.insertAdjacentHTML('beforeend', reviewHtml);
                        });

                        skip += data.length;
                        if(data.length < 6) loadBtn.style.display = 'none';
                    } else {
                        loadBtn.style.display = 'none';
                    }
                });
            });
        }
    });
    </script>
    @endpush

</x-app-layout>