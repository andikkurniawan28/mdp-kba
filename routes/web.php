<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZonaController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\CetakBarcodeController;
use App\Http\Controllers\LaporanShiftController;
use App\Http\Controllers\MonitoringAllController;
use App\Http\Controllers\EksternalInputController;
use App\Http\Controllers\TitikPengamatanController;
use App\Http\Controllers\VerifikasiMandorController;
use App\Http\Controllers\KategoriParameterController;
use App\Http\Controllers\MonitoringPerZonaController;
use App\Http\Controllers\InputMonitoringLogController;
use App\Http\Controllers\MonitoringPerTitikController;
use App\Http\Controllers\DokumentasiPengembangController;
use App\Http\Controllers\MonitoringPerKategoriController;
use App\Http\Controllers\JenisPilihanKualitatifController;
use App\Http\Controllers\UbahUrutanTitikPengamatanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', DashboardController::class)->name('dashboard')->middleware(['auth']);
Route::resource('/kategori_parameter', KategoriParameterController::class)->middleware(['auth']);
Route::resource('/satuan', SatuanController::class)->middleware(['auth']);
Route::resource('/zona', ZonaController::class)->middleware(['auth']);
Route::resource('/zona', ZonaController::class)->middleware(['auth']);
Route::resource('/parameter', ParameterController::class)->middleware(['auth']);
Route::resource('/jenis_pilihan_kualitatif', JenisPilihanKualitatifController::class)->middleware(['auth']);
Route::resource('/titik_pengamatan', TitikPengamatanController::class)->middleware(['auth']);
Route::resource('/role', RoleController::class)->middleware(['auth']);
Route::resource('/user', UserController::class)->middleware(['auth']);
Route::resource('/monitoring', MonitoringController::class)->middleware(['auth']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login_process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/change_session_periode', [AuthController::class, 'changeSessionPeriode'])->name('change_session_periode');
Route::get('/ubah_urutan_titik_pengamatan', [UbahUrutanTitikPengamatanController::class, 'index'])->name('ubah_urutan_titik_pengamatan.index')->middleware(['auth']);
Route::post('/ubah_urutan_titik_pengamatan', [UbahUrutanTitikPengamatanController::class, 'process'])->name('ubah_urutan_titik_pengamatan.process')->middleware(['auth']);
Route::get('/monitoring_per_kategori/{kategori_parameter_id}', [MonitoringPerKategoriController::class, 'index'])->name('monitoring_per_kategori.index')->middleware(['auth']);
Route::get('/monitoring_per_kategori/data/{kategori_parameter_id}', [MonitoringPerKategoriController::class, 'data'])->name('monitoring_per_kategori.data');
Route::get('/monitoring_per_zona/{zona_parameter_id}', [MonitoringPerZonaController::class, 'index'])->name('monitoring_per_zona.index')->middleware(['auth']);
Route::get('/monitoring_per_zona/data/{zona_parameter_id}', [MonitoringPerZonaController::class, 'data'])->name('monitoring_per_zona.data');
Route::get('/monitoring_all', [MonitoringAllController::class, 'index'])->name('monitoring_all')->middleware(['auth']);
Route::get('/monitoring_per_titik/{titik_pengamatan_id}', [MonitoringPerTitikController::class, 'index'])->name('monitoring_per_titik.index')->middleware(['auth']);
Route::get('/monitoring_per_titik/data/{titik_pengamatan_id}', [MonitoringPerTitikController::class, 'data'])->name('monitoring_per_titik.data');
Route::get('/input_monitoring_log', [InputMonitoringLogController::class, 'index'])->name('input_monitoring_log')->middleware(['auth']);
Route::get('/eksternal_input/{periode}/{jam}/{titik_pengamatan_id}/{parameter_id}/{user_id}/{value}', EksternalInputController::class)->name('eksternal_input');
Route::get('/dokumentasi_pengembang', DokumentasiPengembangController::class)->name('dokumentasi_pengembang')->middleware(['auth']);
Route::get('/cetak_barcode/{zona_id}', [CetakBarcodeController::class, 'index'])->name('cetak_barcode.index')->middleware(['auth']);
Route::post('/cetak_barcode', [CetakBarcodeController::class, 'proses'])->name('cetak_barcode.proses')->middleware(['auth']);
Route::get('/laporan_shift/', [LaporanShiftController::class, 'index'])->name('laporan_shift.index')->middleware(['auth']);
Route::get('/laporan_shift/{tanggal}/{shift}', [LaporanShiftController::class, 'proses'])->name('laporan_shift.proses')->middleware(['auth']);
Route::get('/verifikasi_mandor', [VerifikasiMandorController::class, 'index'])->name('verifikasi_mandor.index')->middleware(['auth']);
Route::post('/verifikasi_mandor', [VerifikasiMandorController::class, 'proses'])->name('verifikasi_mandor.proses')->middleware(['auth']);
