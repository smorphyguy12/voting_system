<div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.elections.*') ? 'active' : '' }}"
                    href="{{ route('admin.elections.index') }}">
                    <i class="fas fa-vote-yea"></i> Elections
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}"
                    href="{{ route('admin.candidates.index') }}">
                    <i class="fas fa-users"></i> Candidates
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}"
                    href="{{ route('admin.students.index') }}">
                    <i class="fas fa-graduation-cap"></i> Students
                </a>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

@yield('content')