@extends('layouts.app')

@section('title', 'About Us - Zopa Food Drop')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            About Us
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    <p class="text-center text-muted mt-3">Bringing Fresh, Home-Cooked Meals to Your Doorstep</p>

    <div class="row align-items-center mt-4">
        <div class="col-md-6 order-md-1 order-2">
            <p>At <strong>Zopa Food Drop</strong>, we believe that everyone deserves nutritious and delicious meals, just like home. Our journey began with a simple mission: to provide fresh, homemade food to office workers and busy individuals who crave healthy and flavorful meals without the hassle of cooking.</p>
            <p>Our meals are crafted with love, using high-quality ingredients sourced from trusted local suppliers. We prioritize hygiene, taste, and convenience to ensure that every bite brings you comfort and satisfaction.</p>
            <p>As we grow, our goal is to expand our offerings beyond meal delivery, bringing you an even wider selection of fresh and wholesome food options. Thank you for choosing Zopa Food Drop – your trusted partner for home-cooked goodness!</p>
            <h3>Our Story</h3>
        <p>At Zopa Food Drop, we started with a simple mission: to bring fresh, home-cooked meals to busy professionals and families. Our journey began with a passion for food and a deep understanding of the need for convenient, healthy, and delicious meal options. Today, we take pride in delivering nutritious meals right to your doorstep, prepared with love and care.</p>
        </div>
        <div class="col-md-6 order-md-2 order-1 text-center">
            <img src="{{ asset('front/images/about_us.jpeg') }}" alt="About Zopa Food Drop" class="img-fluid rounded shadow">
        </div>
    </div>

    <section class="mt-4">
        <h3>What We Offer</h3>
        <ul class="list-unstyled" style="line-height: 1.8;">
            <li><i class="fa-solid fa-circle text-success"></i> <strong>Daily Subscription Plans</strong> – Enjoy wholesome meals every day without the hassle of cooking.</li>
            <li><i class="fa-solid fa-circle text-success"></i> <strong>Single Meal Orders</strong> – Perfect for those who need a quick and satisfying meal on demand.</li>
            <li><i class="fa-solid fa-circle text-success"></i> <strong>Customizable Meal Plans</strong> – Choose your preferred dishes and meal frequency to fit your lifestyle.</li>
        </ul>
    </section>

    <section class="mt-4">
        <h3>Why Choose Us?</h3>
        <ul class="list-unstyled" style="line-height: 1.8;">
            <li><i class="fa-solid fa-circle text-success"></i> <strong>Quality Ingredients</strong> – We use only the freshest and highest quality ingredients to ensure great taste and nutrition.</li>
            <li><i class="fa-solid fa-circle text-success"></i> <strong>Authentic Home-Cooked Taste</strong> – Our meals are crafted with traditional recipes to give you the warmth of homemade food.</li>
            <li><i class="fa-solid fa-circle text-success"></i> <strong>Convenient & Hassle-Free</strong> – Delivered straight to your doorstep, saving you time and effort.</li>
            <li><i class="fa-solid fa-circle text-success"></i> <strong>Affordable Pricing</strong> – Enjoy restaurant-quality meals at budget-friendly prices.</li>
        </ul>
    </section>

    <section class="mt-4 mb-5">
        <h3>Our Commitment</h3>
        <p>We believe that food is more than just sustenance – it’s about nourishment, joy, and connection. That’s why we strive to make every meal we deliver a delightful experience. Our team is dedicated to maintaining the highest standards of hygiene, quality, and customer satisfaction.</p>
        <p>Join us on this journey to better eating habits and healthier living. At Zopa Food Drop, we don’t just serve food; we serve love, one meal at a time.</p>
        <h4>Stay healthy. Eat fresh. Enjoy home-cooked goodness with Zopa Food Drop!</h4>
    </section>
</div>
@endsection
