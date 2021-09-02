<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GetController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Login and Verification

Route::get('logout', [LoginController::class, 'logout']);
Auth::routes(['verify' => true]);
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

//For view controller
Route::get('users', [ViewController::class, 'users'])->name('users.index')->middleware('CheckRole');
Route::get('closed', [ViewController::class, 'closed'])->name('closed');
Route::get('/', [ViewController::class, 'open'])->name('home.index');
Route::get('monthlytickets', [ViewController::class, 'monthlytickets'])->name('gbi.monthly.blade');
Route::get('weeklytickets', [ViewController::class, 'weeklytickets'])->name('gbi.weekly.blade');
Route::get('dailytickets', [ViewController::class, 'dailytickets'])->name('gbi.daily.blade');
Route::get('change-password', [UserController::class, 'changepass']);

//For users controller
Route::get('getusers', [UserController::class, 'getusers']);
Route::put('updateuser/{id}', [UserController::class, 'update']);
Route::post('adduser', [UserController::class, 'store']);
Route::get('userlogs', [UserController::class, 'userlogs']);
Route::get('/user/verify/{token}',[UserController::class, 'verifyUser']);
Route::get('/send/verification', [UserController::class, 'resend']);
Route::post('change-password', [UserController::class, 'storepass'])->name('change.password');

//For ticket Controller
Route::get('closedtickets', [TicketController::class, 'closedtickets']);
Route::get('getticket', [TicketController::class, 'getticket']);
Route::get('monthlyticketsdata', [TicketController::class, 'monthlyticketsdata']);
Route::get('dailyticketsdata', [TicketController::class, 'dailyticketsdata']);
Route::get('storetopissue', [TicketController::class, 'storetopissue']);
Route::get('taskdata', [TicketController::class, 'taskdata']);
Route::get('ExportData/{year}/{month}/{monthname}', [HomeController::class, 'ExportData']);



//dummies


Route::get('createlyka', [HomeController::class, 'createuserlyka']);
Route::any('check', [HomeController::class, 'check']);
Route::get('sendotp', [HomeController::class, 'sendotp']);

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('table', [HomeController::class, 'table']);//->middleware('ajax');
Route::get('/test', [GetController::class, 'index']);//->middleware('ajax');
Route::get('/update', [GetController::class, 'updatedata']);//->middleware('ajax');
Route::get('createuserlyka', [HomeController::class, 'createuserlyka']);
Route::any('createuser', [HomeController::class, 'createuser']);
Route::get('planttopissue', [HomeController::class, 'planttopissue']);
Route::get('officetopissue', [HomeController::class, 'officetopissue']);
Route::get('aging', [HomeController::class, 'aging']);




// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
