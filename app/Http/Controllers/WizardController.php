<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Guest;
use App\Models\QrCode;
use App\Http\Controllers\Controller;
use App\Helpers\helpers;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode as customQrCode;
use App\Mail\SendQrMail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Mail;


class WizardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function step1()
    {
        $guests = Guest::all();
        $members = Member::all();
        return view('flows.step1', compact('guests', 'members'));
    }

    public function postStep1(Request $request)
    {
        // Handle form submission for Step 1
        $request->session()->put('step1', $request->all());
        return redirect()->route('flows.step2');
    }

    public function step2()
    {
        // Retrieve the guest_id and qr_code_id from session
        $guest_id = session('guest_id');
        $guest_name = session('guest_name');
        $guest_email = session('guest_email');
        $qr_code_id = session('qr_code_id');

        return view('flows.step2', compact('guest_id','guest_name', 'qr_code_id','guest_email'));
    }

    public function postStep2(Request $request)
    {
        // Handle form submission for Step 2
        $request->session()->put('step2', $request->all());
        return redirect()->route('flows.step3');
    }

    public function updateQrCode(Request $request, $guest_id)
    {
        // Validate the request
        $request->validate([
            'startdate' => 'required|date',
            'enddate' => 'nullable|date|after_or_equal:startdate',
            'visit_type' => 'required|string|in:one-time,multiple',
        ]);

        // Update the qr_codes table where fk_member_guest_qr_id is the $guest_id and type is 'guest'
        $data = DB::table('qr_codes')
            ->where('fk_member_guest_qr_id', $guest_id)
            ->where('type', 'guest')
            ->update([
                'startdate' => $request->startdate,
                'enddate' => $request->visit_type === 'multiple' ? $request->enddate : null, // Set enddate to null for one-time visits
            ]);

        // Redirect with success message
        return redirect()->route('flows.step3', [
            'startdate' => $request->startdate,
            'enddate' => $request->visit_type === 'multiple' ? $request->enddate : null,
            'visit_type' => $request->visit_type,
        ]);
    }

    public function step3(Request $request)
    {
        $data = [
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
            'visit_type' => $request->visit_type,
            'member' => session('member'),
            'guest_id' => session('guest_id'),
            'guest_name' => session('guest_name'),
            'guest_email' => session('guest_email'),
            'qr_code_id' => session('qr_code_id'),
        ];

        return view('flows.step3',compact('data'));
    }

    public function postStep3(Request $request)
    {
        // Handle form submission for Step 3
        $request->session()->put('step3', $request->all());
        return redirect()->route('flows.complete');
    }


    public function complete(Request $request)
    {

        $qr_code_id = session('qr_code_id');
        $qrCode = QrCode::findOrFail($qr_code_id);
        $qrCode->status = $request->input('status', 'Active'); // Default to 'active' if no status provided
        $qrCode->save();

        // Update the status of the related guest
        if ($qrCode->guest) {
            $guest = $qrCode->guest;
            $guest->status = $request->input('status', 'Active'); // Or     another logic for guest status
            $guest->save();

            $emailGuest = $qrCode->guest->guests_email;
        }

        $guest_name = session()->get('guest_name');
        $member = session()->get('member')['members_name'];
        $visit_type = isset($qrCode->enddate) ? 'Multiple' : 'One-time';
        $duration = isset($qrCode->enddate) ? convertDateTimeToString($qrCode->startdate).'-'.convertDateTimeToString($qrCode->enddate) : convertDateTimeToString($qrCode->startdate);
        // Generate QR code from the QR code string (assuming it's a base64 string)
        // $qrCodeImage = utf8_encode($qrCode->qr_code); // Decode base64 string

        $qrCode = base64_encode(customQrCode::format('png')->size(200)->generate($qrCode->qr_code));

        $qrPath = '/qr_codes/'.$guest_name.'-'.$duration.'.png';
        Storage::disk('public')->put($qrPath, $qrCode);

        $qrCodeUrl = Storage::disk('public')->url($qrPath);

        Mail::to($emailGuest)->send(new SendQrMail($guest_name, $member, $visit_type, $duration, $qr_code_id, $qrCodeUrl));

        return view('flows.complete');
    }

    public function guest_info(Request $request)
    {
        $data = [
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
            'visit_type' => $request->visit_type,
            'member' => session('member'),
            'guest_id' => session('guest_id'),
            'guest_name' => session('guest_name'),
            'guest_email' => session('guest_email'),
            'qr_code_id' => session('qr_code_id'),
        ];

        return view('flows.guest_info',compact('data'));
        // return view('flows.guest_info', compact('guests', 'members'));
    }

}
