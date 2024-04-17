<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
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
Route::get('/', function () {
    return view('pages.dashboard.index', ['type_menu' => 'dashboard']);
});
Route::resource('category', CategoryController::class)->names('category');
Route::resource('member', MemberController::class)->names('member');
