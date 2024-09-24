<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QR_Code;
use App\Models\Guest;
use App\Models\Member;
use App\Models\QrCode;
use Illuminate\Support\Facades\DB;

class QRCodeController extends Controller
{
    public function generateQrCode()
    {
        // Generate a QR code
        $qrCode = Qr_Code::size(300)->generate('LthyZ7n6'); // You can change the URL or data

        // Pass the QR code to the view
        return view('qr_code.qr_code', compact('qrCode'));
    }

    public function showScanPage()
    {
        return view('qr_code.qr_ui_scanner');
    }

  
    public function getAccountInfo(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'code' => 'required|string'
            ]);
    
            $code = $request->input('code');
    
            // Fetch QR code details
            $qrCode = DB::table('qr_codes')
                ->where('qr_code', $code)
                ->first();
    
            if (!$qrCode) {
                return response()->json(['error' => 'QR code not found.'], 404);
            }
    
            // Initialize result and member info
            $result = null;
            $memberInfo = null;
    
            if ($qrCode->type === 'member') {
                // Get member details
                $result = DB::table('members')
                    ->where('id', $qrCode->fk_member_guest_qr_id)
                    ->first();
            } elseif ($qrCode->type === 'guest') {
                // Get guest details
                $result = DB::table('guests')
                    ->where('id', $qrCode->fk_member_guest_qr_id)
                    ->first();
    
                // Fetch associated member information
                if ($result) {
                    $memberInfo = DB::table('members')
                        ->where('id', $result->fk_member_guest_id) // Assuming this field links to the member
                        ->first();
                }
            }
    
            // Return response based on the type
            if ($result) {
                $response = [
                    'type' => $qrCode->type,  // Add the type here
                    'id' => $result->membership_id,
                    'name' => $qrCode->type === 'member' ? $result->members_name : $result->guests_name,
                    'email' => $qrCode->type === 'member' ? $result->members_email : $result->guests_email
                ];
    
                // Add member information if the type is guest
                if ($qrCode->type === 'guest' && $memberInfo) {
                    $response['member_id'] = $memberInfo->membership_id;
                    $response['member_name'] = $memberInfo->members_name;
                }
    
                return response()->json($response);
            } else {
                return response()->json(['error' => 'Account not found.'], 404);
            }
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error('Error fetching account info: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }
    
    
    
}
