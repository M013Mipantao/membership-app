<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Member;
use App\Models\OTP;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\OtpMail;

class Login_Controller extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('flows.login');
    }

    // AJAX call to validate membership ID
    public function validateMembership(Request $request)
    {
        // Validate membership_id
        $request->validate([
            'membership_id' => 'required|exists:members,membership_id',
        ]);

        // Find the member
        $member = Member::where('membership_id', $request->membership_id)
            ->where('status', 'Active')
            ->first();

        if ($member) {
            // Find associated user
            $user = User::find($member->user_id);

            if (is_null($user->password) || empty($user->password)) {
                // First time, no password exists, redirect to password creation
                return response()->json([
                    'success' => true,
                    'first_time' => true
                ]);
            } else {
                // Password exists, show password input for validation
                return response()->json([
                    'success' => true,
                    'first_time' => false
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Membership ID is invalid.'
            ]);
        }
    }

    // Login user after password validation
    public function login(Request $request)
    {
        // Validate password
        $request->validate([
            'membership_id' => 'required|exists:members,membership_id',
            'password' => 'required',
        ]);

        // Find the member
        $member = Member::where('membership_id', $request->membership_id)
            ->where('status', 'Active')
            ->first();

        // Find the user associated with the member
        $user = User::find($member->user_id);

        if (Hash::check($request->password, $user->password)) {
            // Password is valid, log the user in
            Auth::login($user);
            $request->session()->put('member', $member);
            $request->session()->regenerate();

            return redirect()->intended('member_registration/form');
        } else {
            // Invalid password
            return back()->withErrors(['password' => 'Invalid password.']);
        }
    }

    // Create password for the first time
    public function createPasswordForm($membership_id)
    {
        return view('flows.create-password', compact('membership_id'));
    }

    public function storePassword(Request $request)
    {
        // Validate incoming request for password creation
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'membership_id' => 'required|exists:members,membership_id',
        ], [
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character (@, $, !, %, *, #, ? or &).',
            'membership_id.required' => 'Membership ID is required.',
        ]);
    
        // Find the member and associated user
        $member = Member::where('membership_id', $request->membership_id)->first();
        if (!$member) {
            return back()->withErrors(['membership_id' => 'Invalid membership ID.']);
        }
    
        $user = User::find($member->user_id);
        if (!$user) {
            return back()->withErrors(['user' => 'User not found.']);
        }
    
        // Generate and send OTP
        $otpResponse = $this->sendOtp($request->membership_id, $member->members_email);
        if (!$otpResponse['success']) {
            return back()->withErrors(['otp' => 'Failed to send OTP.']);
        }
    
        // Store the password in the session temporarily for verification
        session(['temp_password' => $request->password]);
    
        // Redirect to OTP verification page
        return redirect()->route('otp.verify')->with('success', 'OTP sent to your email.');
    }
    

    public function sendOtp($membership_id, $email)
    {
        if (empty($email)) {
            return [
                'success' => false,
                'message' => 'Email address is missing.',
            ];
        }
    
        // Generate OTP
        $otp = rand(100000, 999999);
        $otpExpiryMinutes = 10;
        $otpExpiryTime = Carbon::now()->addMinutes($otpExpiryMinutes);
    
        // Send OTP email
        Mail::to($email)->send(new OtpMail($otp, $otpExpiryMinutes));
    
        // Store OTP in the database
        OTP::create([
            'membership_id' => $membership_id,
            'otp' => $otp,
            'expires_at' => $otpExpiryTime,
        ]);
        session(['membership_id' => $membership_id]);
        return [
            'success' => true,
            'message' => 'OTP sent successfully',
            'expires_at' => $otpExpiryTime,
        ];
    }

    public function verifyOtp(Request $request)
    {
        // Validate the OTP input from the request
        $request->validate([
            'otp' => 'required|numeric',
        ]);
    
        // Retrieve the membership ID from the session
        $membership_id = session('membership_id');
        // dd($membership_id);
    
        // Find the OTP record in the database
        $otpRecord = OTP::where('membership_id', $membership_id)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    
        // Check if the OTP is valid and has not expired
        if ($otpRecord) {
            // Find the member using the membership ID
            $member = Member::where('membership_id', $membership_id)->first();
            if (!$member) {
                return back()->withErrors(['membership_id' => 'Membership ID not found.']);
            }
    
            // Find the associated user by the user's ID stored in the member record
            $user = User::find($member->user_id);
            if (!$user) {
                return back()->withErrors(['user' => 'User not found.']);
            }
    
            // Save the new password for the user
            $user->password = Hash::make(session('temp_password')); // Assuming you stored temp_password in the session
            $user->save();
    
            // Log the user in
            Auth::login($user);
            $request->session()->put('member', $member);
            $request->session()->regenerate();
    
            // Clear the OTP record from the database after successful verification
            $otpRecord->delete();
    
            // Redirect to the main page
            return redirect()->intended('member_registration/form');
        }
    
        // If OTP is invalid or has expired, redirect back with an error
        return back()->withErrors(['otp' => 'Invalid OTP or OTP has expired.']);
    }
    
  
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }


    public function showOtpForm(Request $request)
    {
        return view('flows.otp-verify', [
            'membership_id' => $request->membership_id,
        ]);
    }

}
