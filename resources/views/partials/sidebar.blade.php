<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Membership</div>
    </a>

    <hr class="sidebar-divider my-0">
    
    {{-- <li class="nav-item active">
        <a class="nav-link" href="{{route('admin.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a class="nav-link" href="{{route('admin.transaction')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Trasactions</span>
        </a>
        <a class="nav-link" href="{{route('admin.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Controls</span>
        </a>
        <a class="nav-link" href="{{route('admin.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>List</span>
        </a>
    
    </li> --}}

        <!-- Nav Items -->
        <li class="nav-item active">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home"></i> <!-- Dashboard Icon -->
                <span>Dashboard</span>
            </a>
            <a class="nav-link" href="">
                <i class="fas fa-exchange-alt"></i> <!-- Transactions Icon -->
                <span>Transactions</span>
            </a>
            <a class="nav-link" href="{{route('controls')}}">
                <i class="fas fa-cogs"></i> <!-- Controls Icon -->
                <span>Controls</span>
            </a>
            <a class="nav-link" href="">
                <i class="fas fa-list"></i> <!-- List Icon -->
                <span>List</span>
            </a>
        </li>
</ul>


