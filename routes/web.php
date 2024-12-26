<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeminjamanTahunanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
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

Route::match(['get', 'post'], '/', [AuthController::class, 'login'])->name('login');
Route::get('/forgot-password', [App\Http\Controllers\AuthController::class, 'forgotPassword'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'forgotPassword'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/attend', function () {
    return view('pages.kunjungan.attend');
});
Route::post('/search', [SearchController::class, 'search'])->name('search');
Route::post('/saveattend', [SearchController::class, 'saveattend'])->name('saveattend');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/offline', function () {
    return view('vendor.laravelpwa.offline');
});


Route::get('/storage-link', function () {
    Artisan::call('storage:link');
});
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class)->names('kategori');
    Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::resource('siswa', SiswaController::class)->names('siswa');
    Route::resource('buku', BukuController::class)->names('buku');
    Route::resource('kelas', KelasController::class)->names('kelas');
    Route::resource('mapel', MapelController::class)->names('mapel');
    Route::resource('user', UserController::class)->names('user');
    Route::resource('kunjungan', KunjunganController::class)->names('kunjungan');
    Route::get('peminjaman/approve/{id}', [PeminjamanController::class, 'approve'])->name('approve');
    Route::resource('peminjaman', PeminjamanController::class)->names('peminjaman');
    Route::resource('peminjamanmapel', PeminjamanTahunanController::class)->names('peminjamanmapel');
    Route::get('peminjamanmapel/approve/{id}', [PeminjamanTahunanController::class, 'approve'])->name('peminjamanmapel.approve');
    Route::post('peminjamanmapel/mass-insert', [PeminjamanTahunanController::class, 'massInsert'])->name('peminjamanmapel.mass-insert');
    Route::match(['get', 'put'], 'profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
});
