<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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
Route::get('createuserlyka', [HomeController::class, 'createuserlyka']);
Route::any('check', [HomeController::class, 'check']);
Route::get('sendotp', [HomeController::class, 'sendotp']);
// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/', [HomeController::class, 'index'])->name('home.index');//->middleware('ajax');
Route::get('getticket', [HomeController::class, 'getticket']);
Route::get('closedtickets', [HomeController::class, 'closedtickets']);
Route::get('closed', [HomeController::class, 'closed']);
Route::get('createuserlyka', [HomeController::class, 'createuserlyka']);
Route::any('createuser', [HomeController::class, 'createuser']);
Route::get('taskdata', [HomeController::class, 'taskdata']);
Route::get('storetopissue', [HomeController::class, 'storetopissue']);
Route::get('planttopissue', [HomeController::class, 'planttopissue']);
Route::get('officetopissue', [HomeController::class, 'officetopissue']);
Route::get('dailytickets', [HomeController::class, 'dailytickets'])->name('gbi.daily.blade');
Route::get('monthlytickets', [HomeController::class, 'monthlytickets'])->name('gbi.monthly.blade');
Route::get('weeklytickets', [HomeController::class, 'weeklytickets'])->name('gbi.weekly.blade');
Route::get('dailyticketsdata', [HomeController::class, 'dailyticketsdata']);
Route::get('monthlyticketsdata', [HomeController::class, 'monthlyticketsdata']);
Route::get('aging', [HomeController::class, 'aging']);
Route::get('ExportData/{year}/{month}/{monthname}', [HomeController::class, 'ExportData']);



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
