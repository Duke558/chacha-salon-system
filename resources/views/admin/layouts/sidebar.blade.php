<nav id="sidebar" class="sidebar bg-dark text-white d-lg-block d-none position-fixed h-100 p-3">
    <div class="sidebar-header mb-4">
        <h3 class="text-white">Admin Panel</h3>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ request()->is('admin/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>

        <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}" class="nav-link text-white">
                <i class="fas fa-users me-2"></i> Users
            </a>
        </li>

        <li class="{{ request()->is('admin/services*') ? 'active' : '' }}">
            <a href="{{ route('admin.services') }}" class="nav-link text-white">
                <i class="fas fa-cut me-2"></i> Services
            </a>
        </li>

        <li class="{{ request()->is('admin/bookings*') ? 'active' : '' }}">
            <a href="{{ route('admin.bookings') }}" class="nav-link text-white">
                <i class="fas fa-calendar me-2"></i> Bookings
            </a>
        </li>

        <li class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
            <a href="{{ route('admin.categories') }}" class="nav-link text-white">
                <i class="fas fa-th-list me-2"></i> Categories
            </a>
        </li>

        <li>
            <a href="{{ url('/') }}" class="nav-link text-white" target="_blank">
                <i class="fas fa-globe me-2"></i> Visit Website
            </a>
        </li>

        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="#" class="nav-link text-white" onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </form>
        </li>
    </ul>
</nav>