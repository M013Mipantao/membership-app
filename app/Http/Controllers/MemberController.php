<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Log;
use App\Models\QrCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MemberController extends Controller
{
    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validatedDataMember = $request->validate([
            'membership_id' => 'required|unique:members',
            'members_name' => 'required|string|max:255',
            'members_email' => 'required|email|unique:members',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date',
            'status' => 'required|in:Active,Inactive'
        ]);

        $code = generateRandomCode();
        $password = generateTemporaryPassword($validatedDataMember['membership_id'],$validatedDataMember['members_name']);
        // dd($password);
        $validatedDataUser = [
            'name' => $validatedDataMember['members_name'],
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
        $validatedDataMember['user_id'] = $user->id;

        // Create a new member using the validated data
        $member = Member::create($validatedDataMember);
        

        QrCode::create([
            'qr_code' => $code,
            'fk_member_guest_qr_id' => $member->id,
            'type' => "member",
            'startdate' => 0,
            'enddate' => 0,
            'status' => 'Active',
        ]);

        // Redirect back with a success message
        return redirect()->route('members.create')->with('success', 'Member registered successfully!');
    }

   
    
}

