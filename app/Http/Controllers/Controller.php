<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\Models\User;


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
        $users = User::all();  // Fetch all users, you can also paginate here.
        return view('admin.dashboard', compact('users'));
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
