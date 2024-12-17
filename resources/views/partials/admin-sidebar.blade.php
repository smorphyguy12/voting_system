<!-- ========== Menu ========== -->
<div class="app-menu">

    <!-- Brand Logo -->
    <div class="logo-box">
        <!-- Brand Logo Light -->
        <a href="{{ route('home') }}" class="logo-light">
            <img src="{{ asset('assets/images/logo-sidebar-light.png') }}" alt="logo" style="padding: 5px; height: 60px;" class="logo-lg">
            <img src="{{ asset('assets/images/logo.png') }}" alt="small logo" style="padding: 5px; height: 39px;" class="logo-sm">
        </a>

        <!-- Brand Logo Dark -->
        <a href="{{ route('home') }}" class="logo-dark text-center">
            <img src="{{ asset('assets/images/logo-sidebar-dark.png') }}" alt="dark logo" style="padding: 5px; height: 60px;" class="logo-lg">
            <img src="{{ asset('assets/images/logo.png') }}" alt="small logo" style="padding: 5px; height: 39px;" class="logo-sm">
        </a>
    </div>

    <!-- menu-left -->
    <div class="scrollbar">

        <!-- User box -->
        <div class="user-box text-center">
            <img src="{{ asset('assets/images/users/profile.png') }}" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="dropdown-toggle h5 mb-1 d-block" data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                </div>
            </div>
            <p class="text-muted mb-0">Admin Head</p>
        </div>

        <!--- Menu -->
        <ul class="menu">

            <li class="menu-title">Home</li>

            <li class="menu-item">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <span class="menu-icon"><i data-feather="airplay"></i></span>
                    <span class="menu-text"> Dashboard </span>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('admin.elections.index') }}" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-vote-yea"></i></span>
                    <span class="menu-text"> Elections </span>
                </a>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="{{ route('admin.candidates.index') }}">
                    <span class="menu-icon"><i class="fas fa-users"></i></span>
                    <span class="menu-text"> Candidates </span>
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link" href="{{ route('admin.students.index') }}">
                    <span class="menu-icon"><i class="fas fa-graduation-cap"></i></span>
                    <span class="menu-text"> Students </span>
                </a>
            </li>
        </ul>
        <!--- End Menu -->
        <div class="clearfix"></div>
    </div>
</div>
<!-- ========== Left menu End ========== -->