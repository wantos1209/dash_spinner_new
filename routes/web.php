<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ApkBoController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\WebPromosiController;
use App\Http\Controllers\SpinnerVoucherController;
use App\Http\Controllers\SpinnerJenisvoucherController;
use App\Http\Controllers\SpinnerGeneratevoucherController;
use App\Http\Controllers\LoginSpinnerController;





Route::get('/', function () {

    if (Auth::check()) {
        $user = Auth::user();

        return redirect()->intended('/spinner');
    }

    return redirect()->intended('http://127.0.0.1:8023/login');
});

Route::get('/superadmin', function () {
    return view('dashboard.superadmin.superadmin', [
        'title' => 'superadmin',
    ]);
})->Middleware(['auth', 'superadmin']);

Route::get('/spinner', function () {
    return view('dashboard.dashboard', [
        'title' => 'SPINNER',
    ]);
});

Route::get('/login', [LoginController::class, 'index'])->name('login')->Middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->Middleware('auth');


Route::get('/trex1diath/register', [RegisterController::class, 'index']);
Route::post('/trex1diath/register', [RegisterController::class, 'store']);

/*------------------------------------- APK -------------------------------------*/

/*-- Bo --*/
Route::get('/apk/bo', [ApkBoController::class, 'index'])->middleware('auth');
Route::get('apk/bo/data/{id}', [ApkBoController::class, 'data'])->middleware('auth');
Route::post('/apk/bo/create', [ApkBoController::class, 'store'])->middleware('auth');
Route::put('/apk/bo/update/{id}', [ApkBoController::class, 'update'])->middleware('auth');
Route::delete('/apk/bo/delete/{id}', [ApkBoController::class, 'destroy'])->middleware('auth');


/*------------------------------------- SUPERADMIN -------------------------------------*/
/*-- Dshboard --*/
Route::resource('/superadmins/usertrexdiat', SuperAdminController::class)->Middleware(['auth', 'superadmin'])->middleware('auth');
Route::post('/superadmins/usertrexdiat/{post:id}', [SuperAdminController::class, 'show'])->Middleware(['auth', 'superadmin'])->middleware('auth');
Route::post('/web/promosi/deleteimage', [WebPromosiController::class, 'deleteimage'])->name('deleteimage')->middleware('auth');




/*-- Voucher --*/
Route::get('spinner/voucher/{id}/{api?}', [SpinnerVoucherController::class, 'index'])->name('spinner.voucher')->middleware('auth');
Route::get('spinner/voucherindex/{api?}', [SpinnerVoucherController::class, 'index2'])->name('spinner.voucherindex')->middleware('auth');
Route::get('spinner/voucher/data/{id}', [SpinnerVoucherController::class, 'data'])->middleware('auth');
Route::get('spinner/voucher/datapromosi/{id}', [SpinnerVoucherController::class, 'datapromosi'])->middleware('auth');
Route::post('spinner/voucher/create', [SpinnerVoucherController::class, 'store'])->middleware('auth');
Route::put('spinner/voucher/update/{id}', [SpinnerVoucherController::class, 'update'])->middleware('auth');
Route::delete('spinner/voucher/delete/{id}', [SpinnerVoucherController::class, 'destroy'])->middleware('auth');
Route::get('spinner/voucher/export/{id}', [SpinnerVoucherController::class, 'export'])->name('spinner.voucher.export')->middleware('auth');
Route::post('spinner/voucher/update-status/{id}', [SpinnerVoucherController::class, 'updateStatus'])->name('spinner.update-status')->middleware('auth');

/*-- Jenis Voucher --*/
Route::get('spinner/jenisvoucher', [SpinnerJenisvoucherController::class, 'index'])->middleware('auth');
Route::post('spinner/jenisvoucher/create', [SpinnerJenisvoucherController::class, 'store'])->middleware('auth');
Route::get('spinner/jenisvoucher/data/{id}', [SpinnerJenisvoucherController::class, 'data'])->middleware('auth');
Route::post('spinner/jenisvoucher/create', [SpinnerJenisvoucherController::class, 'store'])->middleware('auth');
Route::put('spinner/jenisvoucher/update/{id}', [SpinnerJenisvoucherController::class, 'update'])->middleware('auth');
Route::delete('spinner/jenisvoucher/delete/{id}', [SpinnerJenisvoucherController::class, 'destroy'])->middleware('auth');

Route::get('spinner/jenisvoucher/datapromosi/{id}', [SpinnerJenisvoucherController::class, 'datapromosi'])->middleware('auth');
Route::get('spinner/jenisvoucher/datavoucher/', [SpinnerJenisvoucherController::class, 'datavoucher'])->middleware('auth');


/*-- Link --*/
// Route::get('spinner/jenisvoucher', [ApkLinkController::class, 'index'])->Middleware(['auth', 'apk']);
// Route::get('spinner/jenisvoucher/data/{id}', [ApkLinkController::class, 'data'])->Middleware(['auth', 'apk']);
// Route::post('spinner/jenisvoucher/create', [ApkLinkController::class, 'create'])->Middleware(['auth', 'apk']);
// Route::post('spinner/jenisvoucher/update/{id}', [ApkLinkController::class, 'update'])->Middleware(['auth', 'apk']);
// Route::delete('spinner/jenisvoucher/delete/{id}', [ApkLinkController::class, 'delete'])->Middleware(['auth', 'apk']);




/*-- Generate Voucher --*/
Route::get('spinner/generatevoucher', [SpinnerGeneratevoucherController::class, 'index'])->name('spinner.generatevoucher')->middleware('auth');
Route::get('spinner/generatevoucher/data/{id}', [SpinnerGeneratevoucherController::class, 'data'])->middleware('auth');
Route::get('spinner/generatevoucher/datapromosi/{id}', [SpinnerGeneratevoucherController::class, 'datapromosi'])->middleware('auth');
Route::post('spinner/generatevoucher/create', [SpinnerGeneratevoucherController::class, 'store'])->middleware(['auth', 'is_admin']);
Route::put('spinner/generatevoucher/update/{id}', [SpinnerGeneratevoucherController::class, 'update'])->middleware('auth');
Route::delete('spinner/generatevoucher/delete/{id}', [SpinnerGeneratevoucherController::class, 'destroy'])->middleware(['auth', 'is_admin']);


/*------------------------------------- SPINNER -------------------------------------*/

Route::get('spinner/voucher/exportexcel/{id}', [SpinnerVoucherController::class, 'exportexcel'])->name('spinner.voucher.exportexcel')->middleware('auth');


Route::get('k6rilog19', function () {
    return view('spinlg.index');
});

Route::get('spinnerl21', function () {
    return view('spinlg.spinner');
});


Route::post('spinner/auth', [LoginSpinnerController::class, 'authenticate']);
