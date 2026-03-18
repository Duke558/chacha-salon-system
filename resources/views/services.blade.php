@extends('layouts.app')

@section('content')
<main class="bg-dark services-page">

    <!-- ===============================
        PAGE HEADER
    ================================ -->
    <header class="service-header"> 
        <div class="container"> 
            <div class="row justify-content-center"> 
                <div class="col-lg-8 text-center" data-aos="fade-up"> 
                    <h1 class="page-title">Our Beauty Services</h1> 
                    <p class="lead mt-3"> Experience luxury beauty treatments tailored just for you </p> 
                </div> 
            </div> 
        </div>
    </header>

    @push('styles')
    <style>
        /* =========================
           CARD DESCRIPTION STYLING
        ========================= */
        .service-card-description {
            font-size: 3px;        /* ultra-tiny on desktop */
            color: #aaa;
            max-height: 1.2em;     /* one-line only */
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2em;
            text-align: justify;    /* justified */
            transition: all 0.3s ease;
        }

        /* Expand on hover for desktop/tablet */
        .service-card:hover .service-card-description {
            max-height: none;
            color: #ccc;
        }

        /* Responsive: mobile */
        @media (max-width: 480px) {
            .service-card-description {
                font-size: 12px;    /* readable */
                max-height: none;    /* show full description */
                line-height: 1.4em;
                overflow: visible;
                text-align: justify;
            }
        }
    </style>
    @endpush

    <!-- ===============================
        SERVICE CATEGORIES
    ================================ -->
    @foreach($categories as $category)

        @php
            $bgClass = $loop->even ? 'section-alt' : '';

            $iconClass = match($category->name) {
                'Bridal Services' => 'fa-ring',
                'Facial Services' => 'fa-spa',
                'Hair Services' => 'fa-cut',
                'Makeup Services' => 'fa-magic',
                default => 'fa-star'
            };
        @endphp

        <section class="service-section {{ $bgClass }} py-5" id="{{ Str::slug($category->name) }}">
            <div class="container">

                <!-- Category Title -->
                <div class="row mb-5">
                    <div class="col-12 text-center" data-aos="fade-up">
                        <div class="section-title">
                            <span class="subtitle d-block mb-2 text-pink">
                                {{ $category->name }}
                            </span>

                            <h2 class="text-white">
                                {{ $category->description ?: $category->name }}
                            </h2>
                        </div>
                    </div>
                </div>

                <!-- Services Grid -->
                <div class="row g-4">
                    @foreach($category->services as $service)

                        @php
                            $hours = intdiv($service->duration, 60);
                            $minutes = $service->duration % 60;
                            $formattedDuration = ($hours > 0 ? $hours.' hr ' : '') . ($minutes > 0 ? $minutes.' mins' : '');

                            $imagePath = $service->primaryImage
                                ? asset('storage/' . $service->primaryImage->image_path)
                                : null;
                        @endphp

                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3" 
                             data-aos="fade-up" 
                             data-aos-delay="{{ $loop->index * 100 }}">

                            <x-service-card
                                :title="$service->name"
                                :description="$service->description"
                                :price="'₱ ' . number_format($service->price, 2)"
                                :duration="$formattedDuration"
                                :image="$imagePath"
                                :icon="$imagePath ? null : 'fas ' . $iconClass"
                                :service-id="$service->id" />

                        </div>

                    @endforeach
                </div>

            </div>
        </section>

    @endforeach

</main>
@endsection