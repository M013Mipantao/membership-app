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
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {

    $member = session('member'); // Assuming member is already in session
    $api = session('api'); // Assuming member is already in session

    return view('single_page.profile', compact('member','api'));

    }

}
