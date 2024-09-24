<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\WizardController;
use App\Http\Controllers\Login_Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode as customQrCode;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Guest;
use App\Models\QrCode;




// // Index
// Route::get('/', function () {
//     return view('flows.login');
// });


// ADMIN PAGE
// membership
Route::get('members/create', [MemberController::class, 'create'])->name('members.create');
Route::post('members', [MemberController::class, 'store'])->name('members.store');
// Guest List
Route::get('/guests', [GuestController::class, 'create_guests'])->name('members.guest');
Route::post('/guests', [GuestController::class, 'store_guest']);
Route::post('/member-guests', [GuestController::class, 'member_store_guest']);

Route::delete('/guests/{id}', [GuestController::class, 'destroy']);
Route::get('/guests/by-member/{memberId}', [GuestController::class, 'getGuestsByMember']);



// QR CODE
Route::get('/generate-qr-code', [QRCodeController::class, 'generateQrCode']);
Route::get('/qr-scanner', function () {
    return view('qr_code.qr_scanner');
});


// Route::get('/test-qr-code', function () {
//     $qrCodeData = '1'; // Replace with dynamic data as needed
//     $qrCode = QrCode::size(300)->generate($qrCodeData);
//     return $qrCode;
// });

// QR Scanner Page
Route::get('/scanner', [QRCodeController::class, 'showScanPage']);
Route::post('/get-account-info', [QRCodeController::class, 'getAccountInfo']);




// Members Page
Route::middleware(['auth'])->group(function () {
    // // Single page
    // Route::get('/member_registration', function () {
    //     return view('layouts.single_page_ui');
    // });

    Route::get('/member_registration/step1', [WizardController::class, 'step1'])->name('flows.step1');
    Route::post('/step1', [WizardController::class, 'postStep1']);
    Route::get('/member_registration/step2', [WizardController::class, 'step2'])->name('flows.step2');
    Route::get('/member_registration/step3', [WizardController::class, 'step3'])->name('flows.step3');
    Route::post('/step3', [WizardController::class, 'postStep3']);
    Route::get('/member_registration/complete', [WizardController::class, 'complete'])->name('flows.complete');
    // Route::post('/step4', [WizardController::class, 'postStep4']);


    Route::get('/select-guests', [GuestController::class, 'select_guest']);
    Route::put('/select-guests/{id}', [GuestController::class, 'update'])->name('guests.update');
    Route::put('/member_registration/complete/{id}', [WizardController::class, 'updateStatus'])->name('complete.update');

    
});



Route::put('/update-qr-code/{guest_id}', [WizardController::class, 'updateQrCode'])->name('update.qr.code');

// LOGIN
Route::get('/login', [Login_Controller::class, 'showLoginForm'])->name('login');
Route::post('/login', [Login_Controller::class, 'login'])->name('login');
Route::post('/update-password', [Login_Controller::class, 'updatePassword'])->name('update.password');

// Route::get('/login', [Login_Controller::class, 'showLoginForm'])->name('login');
// Route::post('/login', [Login_Controller::class, 'login']);
Route::get('/logout', [Login_Controller::class, 'logout'])->name('logout');

// Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Download
// use App\Models\QrCode;
Route::get('/download-qr-code/{qrId}', function ($qrId) {
    $qr = QrCode::findOrFail($qrId);
    $qrCodeImage = utf8_encode($qr->qr_code);
    $qrCode = customQrCode::format('png')->size(200)->generate($qrCodeImage);

    return response($qrCode)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="qr_code_' . $qr->qr_code . '.png"');
})->name('download.qr.code');
