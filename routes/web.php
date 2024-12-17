<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\WizardController;
use App\Http\Controllers\Login_Controller;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode as customQrCode;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Guest;
use App\Models\QrCode;
use App\Http\Controllers\AdminController;

// Index
Route::get('/', function () {
    return view('flows.login');
});

Route::post('/validate-membership', [Login_Controller::class, 'validateMembership'])->name('validateMembership');

// Index
Route::get('/create-password', function () {
    return view('flows.create-password');
});

Route::get('password/create/{membership_id}', [Login_Controller::class, 'createPasswordForm'])->name('password.create');
Route::post('password/store', [Login_Controller::class, 'storePassword'])->name('password.store');
Route::get('password/insert', [Login_Controller::class, 'insert'])->name('password.insert');


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


// QR Scanner Page
Route::get('/scanner', [QRCodeController::class, 'showScanPage']);
Route::post('/get-account-info', [QRCodeController::class, 'getAccountInfo']);


// Members Page
Route::middleware(['auth'])->group(function () {
    Route::get('/member_registration/form', [WizardController::class, 'guest_info'])->name('flows.guest_info');
    Route::post('guest-info-form', [GuestController::class, 'new_member_store_guest'])->name('guest-info-form');

    Route::get('/member_registration/step1', [WizardController::class, 'step1'])->name('flows.step1');
    Route::post('/step1', [WizardController::class, 'postStep1']);
    Route::get('/member_registration/step2', [WizardController::class, 'step2'])->name('flows.step2');
    Route::get('/member_registration/step3', [WizardController::class, 'step3'])->name('flows.step3');
    Route::post('/step3', [WizardController::class, 'postStep3']);
    Route::get('/member_registration/complete', [GuestController::class, 'complete'])->name('flows.complete');

    Route::get('/select-guests', [GuestController::class, 'select_guest']);
    Route::put('/select-guests/{id}', [GuestController::class, 'update'])->name('guests.update');
    Route::put('/member_registration/complete/{id}', [WizardController::class, 'updateStatus'])->name('complete.update');
    
});



Route::put('/update-qr-code/{guest_id}', [WizardController::class, 'updateQrCode'])->name('update.qr.code');

// Login an Logout
Route::get('/login', [Login_Controller::class, 'showLoginForm'])->name('login');
Route::post('/login', [Login_Controller::class, 'login'])->name('login');
Route::get('/logout', [Login_Controller::class, 'logout'])->name('logout');

// Change password routes
Route::get('/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('update.password');
Route::post('/change-password', [PasswordController::class, 'changePassword'])->middleware('auth');

// Forgot password and reset routes
Route::get('/forgot-password', [PasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');

// Download
Route::get('/download-qr-code/{qrId}', function ($qrId) {
    $qr = QrCode::findOrFail($qrId);
    $qrCodeImage = utf8_encode($qr->qr_code);
    $qrCode = customQrCode::format('png')->size(200)->generate($qrCodeImage);

    return response($qrCode)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="qr_code.png"');
})->name('download.qr.code');


//Admin
Route::get('transactions/fetch', [TransactionController::class, 'fetch'])->name('transactions.fetch');
Route::get('admin/transaction', [TransactionController::class, 'index'])->name('admin.transaction');
Route::get('admin', [Controller::class,'index'])->name('users.create');
Route::get('admin', [Controller::class,'dashboard'])->name('admin.dashboard');
Route::post('admin/user', [Controller::class,'create'])->name('users.create');
Route::get('admin/user', [Controller::class,'edit'])->name('users.edit');
Route::post('admin/user', [Controller::class,'destroy'])->name('users.destroy');
Route::get('admin/create', [AdminController::class, 'create'])->name('admin.create');
Route::post('admin/create', [AdminController::class, 'store'])->name('admin.store');

// New Scanner
Route::get('/qr-code-scan', [QRCodeController::class, 'scanQrCode'])->name('qr.scan');

// OTP
Route::get('otp/verify', [Login_Controller::class, 'showOtpForm'])->name('otp.verify');
Route::post('otp/verify', [Login_Controller::class, 'verifyOtp']);

Route::get('/test-imagick', [GuestController::class, 'testImagick']);

// Profile
Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth')->name('profile');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/api/{member_id}', [MemberController::class, 'apifetch_member'])->name('api');
Route::get('/transactionapi/{member_id}', [DashboardController::class, 'transaction'])->name('transactionapi');

//delet
// Route::patch('/qr-codes/{id}/delete', [DashboardController::class, 'destroy']);
Route::patch('/qr-codes/{id}/status', [DashboardController::class, 'updateStatus']);
Route::get('/qr-codes', [DashboardController::class, 'getQrCodes'])->name('qr-codes.list');
Route::get('/dashboard2', [DashboardController::class, 'dashboard2'])->name('dashboard2');

Route::get('/transactions-tab', [DashboardController::class, 'index'])->name('transactions-tab');
Route::get('/qr-codes-tab', [DashboardController::class, 'index'])->name('qr-codes-tab');

