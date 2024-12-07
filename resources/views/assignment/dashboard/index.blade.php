<!doctype html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">


    <!-- Title of the page -->
    <title>SIMS Web App</title>

    <!-- Include CSS files -->
    @include('assignment.dashboard.css')

</head>

<body>
    <!-- Container for the entire page -->
    <div class="container-fluid">
        <div class="row vh-100">
            
            <!-- Sidebar Section -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-danger sidebar">
                <!-- Sidebar header -->
                <p class="fs-4 fw-semibold text-white mt-4">SIMS Web App</p>

                <!-- Sidebar navigation -->
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <!-- Produk Section -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('product*') ? 'active' : '' }}" href="{{ route('product') }}">
                                <i class="fas fa-th-large"></i> Produk
                            </a>
                        </li>

                        <!-- Profil Section -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('profile*') ? 'active' : '' }}" href="{{ route('profile') }}">
                                <i class="fas fa-user"></i> Profil
                            </a>
                        </li>

                        <!-- Logout Section -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" 
                                onclick="event.preventDefault(); 
                                         document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Log Out') }}
                            </a>

                            <!-- Logout form (hidden) -->
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content Section -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <!-- The content of the page will be dynamically loaded here -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Include JS files -->
    @include('assignment.dashboard.js')
</body>

</html>
