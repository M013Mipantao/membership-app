<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\Member;
use App\Models\QrCode;
use App\Mail\SendQrMail;
use SimpleSoftwareIO\QrCode\Facades\QrCode as customQRcode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GuestController extends Controller
{
    public function create_guests()
    {
        $guests = Guest::all();
        $members = Member::all();

        return view('members.guest', compact('guests', 'members'));
    }

    public function member_store_guest(Request $request)
    {
        $validatedData = $request->validate([
            'guests_name' => 'required|string|max:255',
            'guests_email' => 'required|email|unique:guests',
            'contact' => 'required|numeric',
            // 'gender' => 'required|in:Male,Female,Other',
            // 'date_of_birth' => 'required|date',
            'status' => 'required|in:Active,Inactive',
            'fk_member_guest_id' => 'required'
        ]);

        $guest = Guest::create($validatedData);
        $code = generateRandomCode();
        $qr = QrCode::create([
            'qr_code' => $code,
            'fk_member_guest_qr_id' => $guest->id,
            'type' => "guest",
            'startdate' => 0,
            'enddate' => 0,
            'status' => 'Inactive',
        ]);

        session(['guest_id' => $guest->id,'guest_name' => $guest->guests_name, 'qr_code_id' => $qr->id, 'guest_email' => $guest->guests_email]);
           // Redirect back with a success message
           return redirect()->route('flows.step2');
    }

    public function store_guest(Request $request)
    {
        $validatedData = $request->validate([
            'guests_name' => 'required|string|max:255',
            'guests_email' => 'required|email|unique:guests',
            'contact' => 'required|numeric',
            // 'gender' => 'required|in:Male,Female,Other',
            // 'date_of_birth' => 'required|date',
            'status' => 'required|in:Active,Inactive',
            'fk_member_guest_id' => 'required'
        ]);

        $guest = Guest::create($validatedData);
        $code = generateRandomCode();
        QrCode::create([
            'qr_code' => $code,
            'fk_member_guest_qr_id' => $guest->id,
            'type' => "guest",
            'startdate' => 0,
            'enddate' => 0,
            'status' => 'Active',
        ]);

        return redirect()->route('members.guest');
    }

    public function destroy($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();

        return redirect()->route('members.guest');
    }

    public function getGuestsByMember($id)
    {

        // Fetch guests and their QR codes using a join
        $guests = \DB::table('guests')
        ->join('qr_codes', 'guests.id', '=', 'qr_codes.fk_member_guest_qr_id')
        ->select('guests.*', 'qr_codes.qr_code as qr_code') // Adjust field names as needed
        ->where('guests.fk_member_guest_id', $id)
        ->get();


        // Initialize an empty array to hold processed guest data
        $processedGuests = [];

        // Iterate over the guests using a foreach loop
        foreach ($guests as $guest) {
            try {
                // Generate QR code from the QR code string (assuming it's a base64 string)
                $qrCodeImage = utf8_encode($guest->qr_code); // Decode base64 string
                $qrCode = customQrCode::format('png')->size(150)->generate($qrCodeImage);
                $encodedQrCode = base64_encode($qrCode); // Encode to base64 for the response

                // Process each guest record
                $processedGuests[] = [
                    'id' => $guest->id,
                    'guests_name' => $guest->guests_name,
                    'guests_email' => $guest->guests_email,
                    'qr_code' => 'data:image/png;base64,' . $encodedQrCode
                ];
            } catch (\Exception $e) {
                // Handle any errors that occur during QR code generation
                \Log::error('QR Code Generation Error: ' . $e->getMessage());
            }
        }

        // Return the processed guest data in JSON format
        return response()->json($processedGuests);
    }


    // SELECT GUEST DIFFERENT PAGE
    public function select_guest()
    {
        $guests = Guest::all();
        return view('flows.step2', compact('guests'));
    }

    public function update(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);
        $guest->update($request->all());
        return response()->json(['success' => true]);
    }


    public function new_member_store_guest(Request $request)
    {
        // Validate the required fields
        $validatedData = $request->validate([
            'guests_name' => 'required|string|max:255',
            'guests_email' => 'required|email',
            'contact' => 'required|numeric',
            'status' => 'required|in:Active,Inactive',
            'fk_member_guest_id' => 'required',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after_or_equal:startdate', // Ensure enddate is not before startdate
            'agreementCheckbox' => 'nullable',  // Make sure the checkbox is checked
        ]);

        // Create the guest record
        $guest = Guest::create([
            'guests_name' => $validatedData['guests_name'],
            'guests_email' => $validatedData['guests_email'],
            'contact' => $validatedData['contact'],
            'status' => $validatedData['status'],
            'fk_member_guest_id' => $validatedData['fk_member_guest_id'],
        ]);

        // Generate a random QR code
        $gencode = generateRandomCode();
    
        // Check current date and status of the QR code based on start and end dates
        $startdate = Carbon::parse($validatedData['startdate']);
        $enddate = Carbon::parse($validatedData['enddate']);
        $currentDate = Carbon::now();
        
        // Determine the status based on date comparison
        if ($currentDate->lt($startdate)) {
            $qrStatus = 'Not yet available';
        } elseif ($currentDate->between($startdate, $enddate)) {
            $qrStatus = 'Active';
        } else {
            $qrStatus = 'Expired';
        }
    
        // Generate the QR code URL with the visit date range and the determined status
        $code = "data=code:".$gencode.";name:". $validatedData['guests_name'].
                ";visitdate:".$validatedData['startdate'].",".$validatedData['enddate'].
                ";status:".$qrStatus;
    
        // Create the QR code entry with the guest's ID
        $qr = QrCode::create([
            'qr_code' => url('/')."/qr-code-scan?".$code,
            'fk_member_guest_qr_id' => $guest->id,
            'type' => 'guest',
            'startdate' => $validatedData['startdate'],
            'enddate' => $validatedData['enddate'],
            'status' => $qrStatus,
        ]);

        // Store the guest and QR code info in session
        session([
            'guest_id' => $guest->id,
            'guest_name' => $guest->guests_name,
            'qr_code_id' => $qr->id,
            'guest_email' => $guest->guests_email,
        ]);

        // Redirect back with a success message or route to the next step
        return redirect()->route('flows.complete');
    }
    

    public function complete(Request $request)
    {
        // Retrieve QR code from session
        $qr_code_id = session('qr_code_id');
        $qrCode = QrCode::findOrFail($qr_code_id);
    
        // Update QR code status, default to 'Active' if not provided
        $qrCode->status = $request->input('status', 'Active');
        $qrCode->save();
    
        // Update the status of the related guest
        if ($qrCode->guest) {
            $guest = $qrCode->guest;
            $guest->status = $request->input('status', 'Active');
            $guest->save();
    
            $emailGuest = $qrCode->guest->guests_email;
        }
    
        $guest_name = session()->get('guest_name');
        $member = session()->get('member')['members_name'] ?? 'Member';
    
        // Determine visit type and duration
        $visit_type = isset($qrCode->enddate) ? 'Multiple' : 'One-time';
        // $duration = isset($qrCode->enddate)
        //     ? convertDateTimeToString($qrCode->startdate) . ',' . convertDateTimeToString($qrCode->enddate)
        //     : convertDateTimeToString($qrCode->startdate);
            $duration = isset($qrCode->enddate) 
    ? Carbon::parse($qrCode->startdate)->toFormattedDateString().' to '.Carbon::parse($qrCode->enddate)->toFormattedDateString() 
    : Carbon::parse($qrCode->startdate)->toFormattedDateString();
    
        // Sanitize guest name and duration to remove special characters
        $sanitizedGuestName = preg_replace('/[^A-Za-z0-9\-]/', '_', $guest_name);
        $sanitizedDuration = preg_replace('/[^A-Za-z0-9\-]/', '_', $duration);
    
        // Define the path for saving the QR code image in public/qr_codes directory
        $qrPath = public_path('qr_codes/'.$sanitizedGuestName.'-'.$sanitizedDuration.'.png');
    
        // Ensure the directory exists, if not create it
        if (!file_exists(public_path('qr_codes'))) {
            mkdir(public_path('qr_codes'), 0755, true);
        }
    
        // Generate QR code image
        $qrCodeImage = customQrCode::format('png')->size(200)->generate($qrCode->qr_code);
    
        // Save the QR code image
        file_put_contents($qrPath, $qrCodeImage);
    
        // Create the public URL for the QR code image
        $qrCodeUrl = config('app.url') . '/qr_codes/' . $sanitizedGuestName . '-' . $sanitizedDuration . '.png';
    
        // Send the QR code in an email
        Mail::to($emailGuest)->send(new SendQrMail($guest_name, $member, $visit_type, $duration, $qr_code_id, $qrCodeUrl));
    
        // Return the view with the QR code details
        return view('flows.complete', compact('qrCodeUrl', 'guest_name', 'member', 'visit_type', 'duration'));
    }

   public function testImagick()
    {
        // Directly instantiate the Imagick class without a use statement
        $imagick = new \Imagick();
        
        // Create a new image (100x100) with a red background
        $imagick->newImage(100, 100, new \ImagickPixel('red'));
        $imagick->setImageFormat('png');

        // Set the content type header to image/png
        header('Content-Type: image/png');
        
        // Output image directly
        echo $imagick;

        // Clear Imagick object resources
        $imagick->clear();
        $imagick->destroy();
    }
    
    

}
