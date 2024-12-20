<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\Models\User;
use App\Models\Member;
use App\Models\Guest;
use App\Models\QrCode;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $users = User::all();  // Fetch all users, you can also paginate here.
        return view('admin.user_list', compact('users'));
    }
    
    public function dashboard()
    {
        // Fetch counts for dashboard metrics
        $totalMember = Member::count(); // Total members count
        $activeMember = Member::where('status', 'active')->count(); // Active members count
        $totalGuest = Guest::count(); // Total guests count
        $activeQR = QrCode::where('status', 'active')->count(); // Active QR codes count
    
        // Fetch users for any additional needs
        $users = User::all(); // Optional: You can paginate if needed
    
        // Pass metrics to the view
        return view('admin.dashboard', compact('users', 'totalMember', 'activeMember', 'totalGuest', 'activeQR'));
    }
    

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.edit', compact('user'));
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
    
}
