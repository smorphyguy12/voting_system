<!-- ========== Topbar Start ========== -->
<div class="navbar-custom">
    <div class="topbar">
        <div class="topbar-menu d-flex align-items-center gap-1">

            <!-- Topbar Brand Logo -->
            <div class="logo-box">
                @auth
                <div class="navbar-nav">
                    @if(Auth::user()->role === 'admin')
                    <!-- Brand Logo Light -->
                    <a href="{{ route('admin.dashboard') }}" class="logo-light">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="small logo" class="logo-sm">
                    </a>

                    <!-- Brand Logo Dark -->
                    <a href="{{ route('admin.dashboard') }}" class="logo-dark">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="dark logo" class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="small logo" class="logo-sm">
                    </a>
                    @else
                    <!-- Brand Logo Light -->
                    <a href="{{ route('student.dashboard') }}" class="logo-light">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="small logo" class="logo-sm">
                    </a>

                    <!-- Brand Logo Dark -->
                    <a href="{{ route('student.dashboard') }}" class="logo-dark">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="dark logo" class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="small logo" class="logo-sm">
                    </a>
                    @endif
                </div>
                @endauth
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="button-toggle-menu">
                <i class="mdi mdi-menu"></i>
            </button>
        </div>

        <ul class="topbar-menu d-flex align-items-center">

            <!-- Fullscreen Button -->
            <li class="d-none d-md-inline-block">
                <a class="nav-link waves-effect waves-light" href="" data-toggle="fullscreen">
                    <i class="fe-maximize font-22"></i>
                </a>
            </li>

            <!-- Light/Dark Mode Toggle Button -->
            <li class="d-none d-sm-inline-block">
                <div class="nav-link waves-effect waves-light" id="light-dark-mode">
                    <i class="ri-moon-line font-22"></i>
                </div>
            </li>

            <!-- User Dropdown -->
            <li class="dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ asset('assets/images/users/profile.png') }}" alt="user-image" class="rounded-circle">
                    <span class="ms-1 d-none d-md-inline-block">
                        {{ Auth::user()->name }} <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                    <!-- item-->
                    <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </li>

            <!-- Right Bar offcanvas button (Theme Customization Panel) -->
            <li>
                <a class="nav-link waves-effect waves-light" data-bs-toggle="offcanvas" href="#theme-settings-offcanvas">
                    <i class="fe-settings font-22"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- ========== Topbar End ========== -->