<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Transaction::select('datetime', 'name', 'type', 'membership');
            return DataTables::of($transactions)
                ->make(true);
        }
    }

    public function index()
    {
        $transactions = [];
        return view('admin.transaction',compact('transactions'));
    }
}
