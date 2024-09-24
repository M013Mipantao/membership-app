<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\Member;
use App\Models\QrCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode as customQRcode;

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
}