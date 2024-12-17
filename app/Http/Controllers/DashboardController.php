<?php

namespace App\Http\Controllers;
use App\Helpers\ApiHelper;
use App\Models\Guest;
use App\Models\QrCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode as customQRcode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        try {
            // Initialize the data array
            $data = [];
    
            // Fetch the logged-in member's ID from the session
            $memberId = session('member')->id ?? '00001-BIC-000-00'; // Use a default ID for testing if session is null
    
            // Get the current month's start and end dates
            $from = Carbon::now()->startOfMonth()->format('Y-m-d');
            $to = Carbon::now()->endOfMonth()->format('Y-m-d');
            $type = 'W'; // Type
    
            // Fetch transactions for the current month via API
            $transactions = ApiHelper::fetchMemberTransactions($memberId, $from, $to, $type);
    
            // Add transactions to data if successful
            if (isset($transactions['error'])) {
                $data['transactions_error'] = $transactions['error'];
            } else {
                $data['transactions'] = $transactions;
            }
    
            // Fetch QR codes linked to the session member and guests
            $qrCodes = QrCode::with(['guest.member'])
                ->whereHas('guest', function ($query) use ($memberId) {
                    $query->where('type', 'guest')
                            ->where('status','Active')
                          ->where('fk_member_guest_id', $memberId);
                })
                ->get();
            // Add QR codes to data
            $data['qr_codes'] = $qrCodes;
    
        } catch (\Exception $e) {
            // Handle errors gracefully
            $data['error'] = $e->getMessage();
        }
    
        // Pass data to the view
        return view('single_page.dashboard', compact('data'));
    }
    
    public function transaction(Request $request, $memberId)
    {
        try {
            // Initialize the response array
            $response = [];
    
            // Fetch the logged-in member's ID from the session
            $memberId = session('member')->id;
            // $memberId = '00001-BIC-000-00';
    
            // Get date range from request
            $from = $request->input('from', '2024-06-01'); // Default from date
            $to = $request->input('to', '2024-11-30');     // Default to date
            $type = 'W';                                   // Type
    
            // Fetch transactions via API
            $transactions = ApiHelper::fetchMemberTransactions($memberId, $from, $to, $type);
    
            // Add transactions to response
            if (isset($transactions['error'])) {
                $response['error'] = $transactions['error'];
            } else {
                $response['transactions'] = $transactions;
            }
    
            return response()->json($response); // Return JSON response
        } catch (\Exception $e) {
            // Handle errors gracefully
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function updateStatus(Request $request, $id)
    {
        try {
            // Find the QR code by ID
            $qrCode = QrCode::find($id);
    
            // Check if the record exists
            if (!$qrCode) {
                return response()->json(['success' => false, 'message' => 'QR code not found.'], 404);
            }
    
            // Update the status
            $qrCode->status = $request->input('status'); // Get the new status from the request
            $qrCode->save();
    
            return response()->json(['success' => true, 'message' => 'QR code status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating QR code status: ' . $e->getMessage()]);
        }
    }
    

    public function apifetch_member($memberId){
        try {
            // $memberId = '00341-BIC-100-00';
            $memberDetails = ApiHelper::fetchMemberTransactions($memberId);
            dd($memberDetails); // Debug or handle the data as needed
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function getQrCodes()
    {
        $memberId = session('member')->id;
        // Fetch QR codes linked to the session member and guests
        $qrCodes = QrCode::with(['guest.member'])
           ->whereHas('guest', function ($query) use ($memberId) {
               $query->where('type', 'guest')
                     ->where('status','Active')
                     ->where('fk_member_guest_id', $memberId);
           })
           ->get();

        return response()->json(['qr_codes' => $qrCodes]);
    }

    public function dashboard2()
    {
        $data = [];
        $memberId = session('member')->id;
        // $memberId = '1';
        // Fetch QR codes linked to the session member and guests
        $qrCodes = QrCode::with(['guest.member'])
           ->whereHas('guest', function ($query) use ($memberId) {
               $query->where('type', 'guest')
                     ->where('status','Active')
                     ->where('fk_member_guest_id', $memberId);
           })
           ->get();

           $data['guest_info'] = $qrCodes; 

          // Pass data to the view
          return view('single_page.dashboard2', compact('data'));
    }


}
