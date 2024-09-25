<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    /**
     * Show the form to request a password reset link.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        // Validate the email field
        $request->validate(['email' => 'required|email']);

        // Find the member by email
        $member = Member::where('members_email', $request->email)->first();
        
        // Log member details for debugging
        Log::info('Member details:', ['member' => $member]);

        if (!$member) {
            return back()->withErrors(['email' => 'No account found with this email address.']);
        }

        // Find the associated user
        $user = User::find($member->user_id);

        // Log user details for debugging
        Log::info('User details:', ['user' => $user]);

        if (!$user) {
            return back()->withErrors(['email' => 'No user associated with this account.']);
        }

        // Create a password reset token
        $token = app('auth.password.broker')->createToken($user);

        // Log token for debugging
        Log::info('Password reset token:', ['token' => $token]);

        // Manually insert the token into the password_reset_tokens table
        $status = DB::table('password_reset_tokens')->insert([
            'email' => $member->members_email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Log the status of the insert operation
        Log::info('Insert status:', ['status' => $status]);

        if (!$status) {
            return back()->withErrors(['email' => 'Failed to store password reset token.']);
        }

        try {
            Mail::to($member->members_email)->send(new ResetPasswordMail($token, $member->members_email));
        } catch (\Exception $e) {
            Log::error('Mail sending failed:', ['error' => $e->getMessage(), 'token' => $token, 'email' => $member->members_email]);
            return back()->withErrors(['email' => 'Failed to send password reset email.']);
        }
        

        return back()->with(['status' => 'Password reset link sent to your email address.']);
    }

    /**
     * Show the form for resetting the password.
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle resetting the password.
     */
    public function resetPassword(Request $request)
    {
        // Validate the request data
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        // Attempt to find the member by email
        $member = Member::where('members_email', $request->email)->first();
    
        // Log member details for debugging
        Log::info('Reset member details:', ['member' => $member]);
    
        if (!$member) {
            return back()->withErrors(['email' => 'No account found with this email address.']);
        }
    
        // Instead of trying to find the user with an email,
        // you already have the user_id from the member
        $user = User::find($member->user_id);
    
        // Log user details for debugging
        Log::info('Reset user details:', ['user' => $user]);
    
        if (!$user) {
            return back()->withErrors(['email' => 'No user associated with this member.']);
        }
        // Update the user's password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();
    
        // Trigger the password reset event
        event(new PasswordReset($user));
    
        // Redirect the user after a successful password reset
        return redirect()->route('login')->with('status', __('Password has been reset successfully.'));
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    // Handle password change
    public function changePassword(Request $request)
    {
        // Validate the input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password matches the user's password
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update the password
        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}
