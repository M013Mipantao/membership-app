<?php
// namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Member;

class Login_Controller extends Controller
{
    public function showLoginForm()
    {
        return view('flows.login'); // Your login view
    }

    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'membership_id' => 'required',
            'password' => 'required',
        ]);
    
        // Attempt to find the user by membership_id
        $member = Member::where('membership_id', $request->membership_id)
            ->where('status', 'Active')
            ->first();
    
        // Check if member exists
        if ($member) {
            // Find the user associated with the member
            $user = User::find($member->user_id);

            // Check if user exists and password matches
            if ($user && Hash::check($request->password, $user->password)) {

                Auth::login($user); 
                $request->session()->put('member', $member);
                // Regenerate session
                $request->session()->regenerate();

                // Redirect to intended page
                return redirect()->intended('member_registration/form');
            }
        }
    
            // Authentication failed
        return back()->withErrors([
            'membership_id' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password')); // Preserve the membership ID input
    }

    public function updatePassword(Request $request)
    {
        // Validate the new password
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Update the user's password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('dashboard')->with('status', 'Password updated successfully.');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
