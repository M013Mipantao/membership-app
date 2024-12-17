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
use App\Helpers\ApiHelper;

class Login_Controller extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('flows.login');
    }

    public function validateMembership(Request $request){
        // Validate membership_id
        $request->validate([
            'membership_id' => 'required',
        ]);
    
        // Fetch member details via API
        try {
            $apiResponse = ApiHelper::getMemberDetails($request->membership_id);
    
            if (!$apiResponse || !isset($apiResponse['msg'][0])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Membership ID is invalid or inactive in the API.'
                ]);
            }
    
            // Extract API data
            $apiData = $apiResponse['msg'][0];
    
            // Check if the member exists locally
            $member = Member::where('membership_id', $request->membership_id)
                ->where('status', 'Active')
                ->first();
    
            if (!$member) {
                // If the member does not exist locally, insert the data from the API
     
                // Create a new User record for the member (password will be set later)
                $newUser = new User();
                $newUser->name = $apiData['memberName']; // Link user to member
                $newUser->password = ''; // Default password to empty
                $newUser->type = 'Member'; // Default password to empty
                $newUser->status = 'Active'; // Default password to empty
                $newUser->save();


                // Create a new Member record from the API response
                $newMember = new Member();
                $newMember->membership_id = $apiData['memberNo']; // API member ID
                $newMember->status = 'Active'; // Assuming active status, adjust as needed
                $newMember->members_name = $apiData['memberName']; // Full name
                $newMember->user_id = $newUser->id; // Full name
                // $newMember->address = $apiData['address'][0]['address']; // First address entry
                $newMember->date_of_birth = $apiData['birthDate']; // Birth date
                $newMember->members_email = $apiData['email']; // Email
                // $newMember->phone = implode(', ', $apiData['phones']); // Combine phone numbers
                // Add other fields from the API response if needed
                $newMember->save();
   
    
                // After inserting new member and user, proceed to password creation
                return response()->json([
                    'success' => true,
                    'first_time' => true, // Indicating that this is the first time and requires password setup
                ]);
            } else {
                // If member exists locally, proceed to password validation
    
                // Find associated user
                $user = User::find($member->user_id);
    
                // Check if the password is already set
                if (is_null($user->password) || empty($user->password)) {
                    // No password exists, need to create a password
                    return response()->json([
                        'success' => true,
                        'first_time' => true // Indicating that the user needs to create a password
                    ]);
                } else {
                    // Password exists, show password input for validation
                    return response()->json([
                        'success' => true,
                        'first_time' => false // Proceed with password validation
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during the API validation: ' . $e->getMessage()
            ]);
        }
    }
     


    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'membership_id' => 'required|exists:members,membership_id',
            'password' => 'required',
        ]);

        // Fetch member details from API for validation
        try {
            $apiResponse = ApiHelper::getMemberDetails($request->membership_id);

            if (!$apiResponse) {
                return back()->withErrors(['membership_id' => 'Membership ID is invalid or inactive in the API.']);
            }

            // Find the member in the local database
            $member = Member::where('membership_id', $request->membership_id)
                ->where('status', 'Active')
                ->first();

            if (!$member) {
                return back()->withErrors(['membership_id' => 'Member not found or inactive in the local database.']);
            }

            // Find the user associated with the member
            $user = User::find($member->user_id);

            // Check if the password is valid
            if (Hash::check($request->password, $user->password)) {
                // Password is valid, log the user in
                Auth::login($user);
                $request->session()->put('member', $member);
                $request->session()->put('api', $apiResponse['msg'] );
                $request->session()->regenerate();

                // return redirect()->intended('member_registration/form');
                return redirect()->route('dashboard2');
            } else {
                // Invalid password
                return back()->withErrors(['password' => 'Invalid password.']);
            }

        } catch (\Exception $e) {
            return back()->withErrors(['membership_id' => 'An error occurred: ' . $e->getMessage()]);
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

            $apiResponse = ApiHelper::getMemberDetails($membership_id);

            if (!$apiResponse) {
                return back()->withErrors(['membership_id' => 'Membership ID is invalid or inactive in the API.']);
            }

    
            // Log the user in
            Auth::login($user);
            $request->session()->put('member', $member);
            $request->session()->put('api', $apiResponse['msg'] );
            $request->session()->regenerate();
    
            // Clear the OTP record from the database after successful verification
            $otpRecord->delete();
    
            // Redirect to the main page
            // return redirect()->intended('member_registration/form');
            return redirect()->intended('dashboard2');

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
