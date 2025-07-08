<!-- /*
* Template Name: Property
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Untree.co" />
    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap5" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <link rel="icon" type="image/x-icon" href="{{ asset('front/images/imgae.jpeg') }}">
    <link rel="stylesheet" href="{{asset('front/fonts/icomoon/style.css')}}" />
    <link rel="stylesheet" href="{{asset('front/fonts/flaticon/font/flaticon.css')}}" />

    <link rel="stylesheet" href="{{asset('front/css/tiny-slider.css')}}" />
    <link rel="stylesheet" href="{{asset('css/filament/filament/app.css')}}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}" />

<style>
    .site-nav {
        background-color: #2c3e50;
        padding: 15px 0;
    }

    .dropdown-toggle {
        display: flex;
        align-items: center;
        color: white;
        text-decoration: none;
        padding: 8px 15px;
    }

    .dropdown-toggle:hover {
        color: #f8f9fa;
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-radius: 8px;
        padding: 10px 0;
    }

    .dropdown-item {
        padding: 8px 20px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #2c3e50;
    }

    .dropdown-divider {
        margin: 5px 0;
    }

    .site-menu > li {
        display: inline-block;
        position: relative;
    }

    .site-menu > li > a {
        color: white;
        padding: 8px 15px;
        display: inline-block;
        text-decoration: none;
        transition: all 0.3s;
    }

    .site-menu > li > a:hover {
        color: #f8f9fa;
    }

    .active > a {
        font-weight: bold;
        color: #fff !important;
        position: relative;
    }

    .active > a:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 15px;
        right: 15px;
        height: 2px;
        background-color: #fff;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Bootstrap CSS loaded:', !!document.querySelector('link[href*="bootstrap"]'));
    console.log('Bootstrap Icons loaded:', !!document.querySelector('link[href*="bootstrap-icons"]'));
});
</script>
    {{-- this used when need to add custom style to only page --}}
    @stack('styles')
    <title>
        @yield('title', config('app.name'))
    </title>
</head>

<body>
    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close">
                <span class="icofont-close js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>

<nav class="site-nav">
    <div class="container">
        <div class="menu-bg-wrap">
            <div class="site-navigation">
                <a href="{{ route('home') }}" class="logo m-0 float-start fs-2 text-white">Your Home</a>

                <ul class="js-clone-nav d-none d-lg-inline-block text-start site-menu float-end">
                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                    <li class="{{ request()->routeIs('estates.index') ? 'active' : '' }}">
                        <a href="{{ route('estates.index') }}">Estates</a>
                    </li>
                    @auth
                    <li class="{{ request()->routeIs('cart.index') ? 'active' : ''}}"><a href="{{ route('cart.index') }}">Interests</a></li>
                    <li class="{{ request()->routeIs('reservation.index') ? 'active' : ''}}"><a href="{{ route('reservation.index') }}">Reservations</a></li>
                    @endauth

                    @guest
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @endguest

                    @auth
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> {{ Auth::user()->name }}</a></li>
                                {{-- <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li> --}}
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</nav>


    <div class="hero page-inner overlay" style="background-image: url('/front/images/commercial.webp')">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-center mt-5">
                    <h1 class="heading fs-1" data-aos="fade-up">Your Home</h1>

                    <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                        <ol class="breadcrumb text-center justify-content-center">
                            @section('breadcrumb')
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            @show
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
