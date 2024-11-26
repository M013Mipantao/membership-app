<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch data based on the date range (dummy for now)
        $data = []; // Replace with actual data fetching logic

        // Pass data to the view
        return view('single_page.dashboard', compact('data'));
    }
}
