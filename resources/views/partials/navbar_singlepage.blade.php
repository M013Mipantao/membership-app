
@section('menu')
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow mb-4">
    <a class="brand text-primary ml-3" href="#">Balesin Key Wallet</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Dashboard</a>
        </li>
        {{-- <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
              Services
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Create Guest Information</a>
              <a class="dropdown-item" href="#">Service 2</a>
            </div>
          </li>       
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li> --}}
      </ul>
      <div class="navbar-buttons">
        <button class="btn btn-outline-primary btn-sm mr-2">Profile</button>
        <button class="btn btn-primary btn-sm">Logout</button>
      </div>
    </div>
  </nav>
@endsection


@section('scripts')
   
@endsection
