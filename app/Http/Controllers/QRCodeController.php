<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QR_Code;
use App\Models\Guest;
use App\Models\Member;
use App\Models\Transaction;
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
                return response()->json(['error' => 'QR code not found.']);
            }
    
            // Initialize result and member info
            $result = null;
            $memberInfo = null;
    
            if ($qrCode->type === 'member') {
                // Get member details
                $result = Member::findOrFail($qrCode->fk_member_guest_qr_id);
    
            } elseif ($qrCode->type === 'guest') {
                // Get guest details
                $result = Guest::findOrFail($qrCode->fk_member_guest_qr_id);
    
                // Fetch associated member information
                if ($result) {
                    $memberInfo = Member::findOrFail($result->fk_member_guest_id);
                }
            }
    
            // Check if the QR code is within the valid date range
            $currentDate = now();
            $isActive = false;
    
            // Case 1: Both startdate and enddate are not null
            if (!is_null($qrCode->startdate) && !is_null($qrCode->enddate)) {
                if ($currentDate->gte($qrCode->startdate) && $currentDate->lte($qrCode->enddate)) {
                    $isActive = true;
                }
            }
            // Case 2: startdate is not null and enddate is null
            elseif (!is_null($qrCode->startdate) && is_null($qrCode->enddate)) {
                if ($currentDate->lt($qrCode->startdate)) {
                    // QR code is not yet active
                    DB::table('qr_codes')
                        ->where('qr_code', $code)
                        ->update(['status' => 'Inactive']);
    
                    Transaction::create([
                        'fk_qr_id' => $qrCode->id,
                        'created_at' => $currentDate,
                        'type' => $qrCode->type,
                        'status' => 'not_yet_active',
                        'fk_user_id' => "1"
                    ]);
    
                    return response()->json(['error' => 'QR code is not yet active.']);
                } else {
                    // QR code is valid if the current date is on or after the startdate
                    $isActive = true;
                    // QR code is not yet active
                    DB::table('qr_codes')
                    ->where('qr_code', $code)
                    ->update(['status' => 'Active']);
                }
            }
    
            // Update QR code status and log transaction based on active status
            if (!$isActive) {
                // If inactive, update status to 'Inactive'
                DB::table('qr_codes')
                    ->where('qr_code', $code)
                    ->update(['status' => 'Inactive']);
    
                Transaction::create([
                    'fk_qr_id' => $qrCode->id,
                    'created_at' => $currentDate,
                    'type' => $qrCode->type,
                    'status' => 'invalid',
                    'fk_user_id' => "1"
                ]);
    
                return response()->json(['error' => 'QR code has expired or is not active.']);
            }
    
            // Log the valid transaction
            Transaction::create([
                'fk_qr_id' => $qrCode->id,
                'created_at' => $currentDate,
                'type' => $qrCode->type,
                'status' => 'valid',
                'fk_user_id' => "1"
            ]);
    
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
                return response()->json(['error' => 'Account not found.']);
            }
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error('Error fetching account info: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }
    
    
}
