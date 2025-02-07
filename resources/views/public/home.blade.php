@extends('layouts.public')

@section('hero')
    @include('partials.hero')
@endsection

@section('content')
    <section class="carousel-section mt-8">
        @include('partials.carousel', ['carouselItems' => $recentArtworks])
    </section>

    <section class="events mt-16">
        @include('partials.events')
    </section>
@endsection
