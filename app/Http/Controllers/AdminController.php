<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Show the form for creating a new admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all(); // Assuming users exist to associate with the admin
        return view('admin.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'email' => 'required|email|unique:admin',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            // 'user_id' => 'required|exists:users,id',
        ]);

        // $code = generateRandomCode();
        $password = 'admin';
        // dd($password);
        $validatedDataUser = [
            'name' => $validatedData['name'],
            'password' => Hash::make($password),
            'type' => 'member',
            'status' => 'Active',
        ];

         // create a user account
        $user = User::create($validatedDataUser);

        // Validate user_id separately
        $request->validate([
            'user_id' => 'exists:users,id'
        ]);

        // Combine validated data
        $validatedData['user_id'] = $user->id;

        Admin::create($validatedData);

        return redirect()->route('admin.create')->with('success', 'Admin created successfully.');
    }
}
