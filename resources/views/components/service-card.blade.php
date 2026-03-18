<div class="service-card bg-dark rounded-4 overflow-hidden h-100 d-flex flex-column">

    {{-- =========================
        IMAGE
    ========================== --}}
    @if(!empty($image))
        <div class="service-image position-relative">

            <img 
                src="{{ asset($image) }}" 
                alt="{{ $title ?? 'Service Image' }}"
                class="service-img w-100"
                onerror="this.onerror=null;this.src='{{ asset('images/default-service.png') }}';"
            >

            {{-- Overlay --}}
            <div class="service-overlay"></div>

        </div>
    @endif


    {{-- =========================
        CONTENT
    ========================== --}}
    <div class="service-content text-center px-4 pb-4 pt-3 d-flex flex-column flex-grow-1">

        {{-- TITLE --}}
        <h4 class="text-white mb-2">
            {{ $title ?? 'Service Title' }}
        </h4>

        {{-- DESCRIPTION --}}
        <p class="text-white-50 small mb-3 service-desc">
            {{ $description ?? 'No description available.' }}
        </p>

        {{-- PRICE --}}
        <div class="service-price mb-3">
            <span class="text-pink fw-bold fs-5">
                {{ $price ?? '₱0.00' }}
            </span>
            <br>
            <small class="text-white-50">
                {{ $duration ?? '' }}
            </small>
        </div>

        {{-- BOOK BUTTON --}}
        @if(!empty($serviceId))
            <a href="{{ route('booking', ['service' => $serviceId]) }}" 
               class="btn btn-book rounded-pill px-4 mt-auto">
                Book Now
            </a>
        @endif

    </div>

</div>