<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Dashboard
Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
Route::get('/attend', function () {
    return view('pages.visit.attend');
});
Route::post('/search', [SearchController::class, 'search'])->name('search');
Route::post('/saveattend', [SearchController::class, 'saveattend'])->name('saveattend');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/offline', function () {
    return view('vendor.laravelpwa.offline');
});
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/', function () {
    //     return view('pages.dashboard.index');
    // });
    Route::resource('category', CategoryController::class)->names('category');
    Route::resource('member', MemberController::class)->names('member');
    Route::resource('book', BookController::class)->names('book');
    Route::resource('user', UserController::class)->names('user');
    Route::match(['get', 'put'], 'profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
});