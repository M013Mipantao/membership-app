<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\Member;
use App\Models\QrCode;
use App\Mail\SendQrMail;
use SimpleSoftwareIO\QrCode\Facades\QrCode as customQRcode;
use Illuminate\Support\Facades\Mail;
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
                $qrCode = customQrCode::format('png')->size(300)->generate($qrCodeImage);
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
            'guests_email' => 'required|email|unique:guests',
            'contact' => 'required|numeric',
            'status' => 'required|in:Active,Inactive',
            'fk_member_guest_id' => 'required',
            'startdate' => 'required|date',
            'enddate' => 'required|date',  // Ensure enddate is not before startdate
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
        $code = "data=code:".$gencode.";name:". $validatedData['guests_name'].";visitdate:".$validatedData['startdate']."-".$validatedData['enddate'].";status:".$validatedData['status'];
    
        // Create the QR code entry with the guest's ID
        $qr = QrCode::create([
            'qr_code' => url('/')."/qr-code-scan?".$code,
            'fk_member_guest_qr_id' => $guest->id,
            'type' => 'guest',
            'startdate' => $validatedData['startdate'],
            'enddate' => $validatedData['enddate'],
            'status' => 'Active',
        ]);
    
        // Store the guest and QR code info in session
        session([
            'guest_id' => $guest->id,
            'guest_name' => $guest->guests_name,
            'qr_code_id' => $qr->id,
            'guest_email' => $guest->guests_email,
        ]);
    
        // Redirect back with a success message or route to the next step
        return   redirect()->route('flows.complete');
    }

    public function complete(Request $request)
    {
        $qr_code_id = session('qr_code_id');
        $qrCode = QrCode::findOrFail($qr_code_id);
        $qrCode->status = $request->input('status', 'Active'); // Default to 'Active' if no status provided
        $qrCode->save();
    
        // Update the status of the related guest
        if ($qrCode->guest) {
            $guest = $qrCode->guest;
            $guest->status = $request->input('status', 'Active'); // Or another logic for guest status
            $guest->save();
    
            $emailGuest = $qrCode->guest->guests_email;
        }
    
        $guest_name = session()->get('guest_name');
        $member = session()->get('member')['members_name'];
        $visit_type = isset($qrCode->enddate) ? 'Multiple' : 'One-time';
        $duration = isset($qrCode->enddate) 
            ? convertDateTimeToString($qrCode->startdate) . '-' . convertDateTimeToString($qrCode->enddate) 
            : convertDateTimeToString($qrCode->startdate);
    
        // Generate the QR code from the URL and encode it as base64
        // $qrCodeImage = $qrCode->qr_code;  // This is the URL or string you want to encode
        $qrCodeImage = 'http://127.0.0.1:8000/qr-code-scan?data=code:pa3eBxf6;name:guest 1;visitdate:2024-10-10T10:12-2024-10-10T22:12;status:Inactive';
        $qrCodeGenerated = customQrCode::format('png')->size(300)->generate($qrCodeImage);


        // Save the generated QR code to a file
        $filePath = public_path('qr_codes/'.$guest_name.'.png'); // Make sure the 'qr_codes' directory exists
        file_put_contents($filePath, $qrCodeGenerated);

        // Encode the QR code image as base64
        $encodedQrCode = base64_encode($qrCodeGenerated); 
        $qrCodeUrl = 'data:image/png;base64,' . $encodedQrCode; // Create the data URL
         
        // dd($qrCodeGenerated);

    
        // Send email with the QR code
        Mail::to($emailGuest)->send(new SendQrMail($guest_name, $member, $visit_type, $duration, $qrCodeImage, $qrCodeUrl));
    
        return view('flows.complete', compact('qrCodeUrl', 'guest_name', 'member', 'visit_type', 'duration'));
    }
    
}