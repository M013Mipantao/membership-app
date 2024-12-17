
@section('menu')
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow mb-4">
  <a class="brand text-primary ml-3" href="{{ route('dashboard2') }}">Balesin Wallet</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars"></i>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
          {{-- <li class="nav-item {{ request()->is('member_registration/form') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('flows.guest_info') }}">Home</a>
          </li>
          <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
          </li> --}}
          <li class="nav-item">
            @if(Auth::check()) {{  session('member')->member_name  }} @endif 
        </li>
          
          {{-- <li class="nav-item dropdown">
              <a class="nav-link" href="#" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
                  Services
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="#">Create Guest Information</a>
                  <a class="dropdown-item" href="#">Service 2</a>
              </div>
          </li> --}}
      </ul>
      <div class="navbar-buttons">
          <a class="btn btn-outline-primary btn-sm me-2" href="{{ route('profile') }}">Profile</a>
          <a class="btn btn-primary btn-sm" href="{{ route('logout') }}">Logout</a>
      </div>
  </div>
</nav>
@endsection


@section('scripts')

@endsection
