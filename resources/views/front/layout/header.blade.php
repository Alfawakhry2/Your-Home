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
                            <a  href="{{ route('estates.index') }}">Estates</a>
                            {{-- <ul class="dropdown">
                                <li><a href="#">Buy Property</a></li>
                                <li><a href="#">Sell Property</a></li>
                                <li class="has-children">
                                    <a href="#">Dropdown</a>
                                    <ul class="dropdown">
                                        <li><a href="#">Sub Menu One</a></li>
                                        <li><a href="#">Sub Menu Two</a></li>
                                        <li><a href="#">Sub Menu Three</a></li>
                                    </ul>
                                </li>
                            </ul> --}}
                        </li>
                        <li><a href="services.html">Services</a></li>
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endguest

                        @auth
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <input class="btn btn-outline-danger btn-sm" type="submit" value="Logout">
                                </form>
                            @endauth
                        </li>
                    </ul>

                    <a href="#"
                        class="burger light me-auto float-end mt-1 site-menu-toggle js-menu-toggle d-inline-block d-lg-none"
                        data-toggle="collapse" data-target="#main-navbar">
                        <span></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="hero page-inner overlay" style="background-image: url('/front/images/commercial.webp')">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-9 text-center mt-5">
                    <h1 class="heading" data-aos="fade-up">Your Home</h1>

                    <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                        <ol class="breadcrumb text-center justify-content-center">
                            @section('breadcrumb')
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                            @show
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
