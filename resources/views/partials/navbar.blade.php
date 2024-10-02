<nav class="nav nav-borders">
    <a class="nav-link {{ request()->is('members/create') ? 'active' : '' }}" href="{{ route('members.create') }}">Profile</a>
    <a class="nav-link {{ request()->is('guests') ? 'active' : '' }}" href="{{ route('members.guest') }}">Guest</a>
    {{-- Uncomment the lines below if you need additional links --}}
    {{-- <a class="nav-link {{ request()->is('account/security') ? 'active' : '' }}" href="{{ route('account.security') }}">Security</a> --}}
    {{-- <a class="nav-link {{ request()->is('account/notifications') ? 'active' : '' }}" href="{{ route('notification') }}">Notifications</a> --}}
</nav>
