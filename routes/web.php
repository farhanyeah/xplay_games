<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers — Public
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\SewaController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TambahDurasiController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\XPlayChatController;
use App\Http\Controllers\CekSlotController;

// Controllers — Operational
use App\Http\Controllers\Operational\OperationalController;
use App\Http\Controllers\Operational\BillingController;
use App\Http\Controllers\Operational\SewaOperasionalController;
use App\Http\Controllers\Operational\BookingOperasionalController;
use App\Http\Controllers\Operational\PenjualanController;
use App\Http\Controllers\Operational\LaporanController;
use App\Http\Controllers\Operational\MaintenanceController;
use App\Http\Controllers\Operational\ChecksheetController;
use App\Http\Controllers\Operational\KelolaStafController;
use App\Http\Controllers\Operational\KelolaHargaController;
use App\Http\Controllers\Operational\KelolaStokController;


// =========================================
// HALAMAN PUBLIK
// =========================================

Route::get('/', [UlasanController::class, 'index'])
    ->name('home');

Route::view('/promo', 'pages.promo')->name('promo');
Route::view('/tipe-ps', 'pages.tipe-ps')->name('tipe-ps');
Route::view('/list-game', 'pages.list-game')->name('list-game');
Route::view('/info', 'pages.info')->name('info');
Route::get('/cek-slot', [CekSlotController::class, 'index'])->name('cek.slot');

// =========================================
// XPLAY CHAT AI
// =========================================

Route::get('/xplay/chat', fn() => redirect('/'));
Route::post('/xplay/chat', [XPlayChatController::class, 'chat']);
Route::get('/xplay/chat/history', fn() => redirect('/'));
Route::post('/xplay/chat/history', [XPlayChatController::class, 'chatWithHistory']);

// =========================================
// PAYMENT REDIRECT (DANA / SHOPEEPAY)
// =========================================

// Route::get('/payment/finish', fn() => view('payment.finish'))->name('payment.finish');
// Route::get('/payment/cancel', fn() => view('payment.cancel'))->name('payment.cancel');
// Route::get('/payment/error',  fn() => view('payment.error'))->name('payment.error');

// =========================================
// MIDTRANS WEBHOOK
// =========================================

Route::post('/midtrans/notification', [MidtransController::class, 'notification']);

// =========================================
// SEWA (CUSTOMER)
// =========================================

Route::get('/sewa', [SewaController::class, 'index'])->name('sewa.index');

Route::middleware('auth')->group(function () {
    Route::post('/sewa', [SewaController::class, 'store'])->name('sewa.store');
    Route::patch('/sewa/{id}/konfirmasi-midtrans', [SewaController::class, 'konfirmasiMidtrans'])->name('sewa.konfirmasiMidtrans');
    Route::delete('/sewa/{id}', [SewaController::class, 'destroy'])->name('sewa.destroy');

    // Tambah Durasi
    Route::post('/sewa/{id}/tambah-durasi', [TambahDurasiController::class, 'store'])->name('sewa.tambahDurasi');
    Route::patch('/sewa/tambah-durasi/{id}/konfirmasi', [TambahDurasiController::class, 'konfirmasi'])->name('sewa.tambahDurasi.konfirmasi');
    Route::delete('/sewa/tambah-durasi/{id}', [TambahDurasiController::class, 'destroy'])->name('sewa.tambahDurasi.destroy');
});

// =========================================
// BOOKING (CUSTOMER)
// =========================================

// GET boleh diakses guest (lihat antrian)
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');

Route::middleware('auth')->group(function () {
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::patch('/booking/{id}/konfirmasi-midtrans', [BookingController::class, 'konfirmasiMidtrans'])->name('booking.konfirmasiMidtrans');
    Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
});

// =========================================
// ULASAN
// =========================================

Route::middleware('auth')->group(function () {
    Route::post('/ulasan', [UlasanController::class, 'store'])
        ->name('ulasan.store');
    Route::delete('/ulasan/{ulasan}', [UlasanController::class, 'destroy'])
        ->name('ulasan.destroy');
});

// =========================================
// OPERATIONAL — STAF & OWNER
// =========================================

Route::middleware(['auth', 'role:staf,owner'])->prefix('operational')->name('operational.')->group(function () {

    // Dashboard & halaman statis
    Route::get('/dashboard', fn() => view('operational.dashboard'))->name('dashboard');

    // Billing
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/history', [BillingController::class, 'history'])->name('billing.history');
    Route::get('/history/{id}', [BillingController::class, 'show'])->name('billing.show');

    Route::post('/billing/get-paket-harga', [BillingController::class, 'getPaketHarga'])->name('billing.getPaketHarga');
    Route::post('/billing/store', [BillingController::class, 'store'])->name('billing.store');

    Route::post('/billing/{id}/paid', [BillingController::class, 'markAsPaid'])->name('billing.paid');
    Route::post('/billing/{id}/start', [BillingController::class, 'startSesi'])->name('billing.start');
    Route::post('/billing/{id}/pause', [BillingController::class, 'pauseSesi'])->name('billing.pause');
    Route::post('/billing/{id}/resume', [BillingController::class, 'resumeSesi'])->name('billing.resume');
    Route::post('/billing/{id}/complete', [BillingController::class, 'completeSesi'])->name('billing.complete');
    Route::post('/billing/{id}/cancel', [BillingController::class, 'cancel'])->name('billing.cancel');

    Route::post('/billing/{id}/extend', [BillingController::class, 'extend'])->name('billing.extend');
    Route::post('/billing/{id}/pindah-unit', [BillingController::class, 'pindahUnit'])->name('billing.pindahUnit');
    Route::post('/billing/{id}/refund', [BillingController::class, 'refund'])->name('billing.refund');
    Route::post('/billing/{id}/update-harga-final', [BillingController::class, 'updateHargaFinal'])->name('billing.updateHargaFinal');
    Route::get('/billing/get-available-units/{billing}', [BillingController::class, 'getAvailableUnits'])->name('billing.getAvailableUnits');

    // Billing Midtrans
    Route::patch('/billing/{id}/konfirmasi-midtrans', [BillingController::class, 'confirmMidtrans'])
    ->name('billing.konfirmasiMidtrans');

    // Billing Midtrans Extend
    Route::patch('/billing/extend/{id}/konfirmasi-midtrans', [BillingController::class, 'confirmExtendMidtrans'])
    ->name('billing.extend.confirmMidtrans');

    // Data Sewa
    Route::get('/sewa', [SewaOperasionalController::class, 'index'])->name('sewa.index');
    Route::get('/sewa/{id}', [SewaOperasionalController::class, 'show'])->name('sewa.show');
    Route::patch('/sewa/{id}/status', [SewaOperasionalController::class, 'updateStatus'])->name('sewa.updateStatus');
    Route::patch('/sewa/{id}/konfirmasi-pembayaran', [SewaOperasionalController::class, 'konfirmasiPembayaran'])->name('sewa.konfirmasiPembayaran');
    Route::patch('/sewa/{id}/cancel', [SewaOperasionalController::class, 'cancel'])->name('sewa.cancel');
    Route::patch('/sewa/{id}/complete', [SewaOperasionalController::class, 'complete'])->name('sewa.complete');

    // Data Booking
    Route::get('/booking', [BookingOperasionalController::class, 'index'])->name('booking.index');
    Route::get('/booking/{id}', [BookingOperasionalController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{id}/konfirmasi-pembayaran', [BookingOperasionalController::class, 'konfirmasiPembayaran'])->name('booking.konfirmasiPembayaran');
    Route::patch('/booking/{id}/selesaikan', [BookingOperasionalController::class, 'selesaikan'])->name('booking.selesaikan');
    Route::patch('/booking/{id}/cancel', [BookingOperasionalController::class, 'cancel'])->name('booking.cancel');

    // Penjualan
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::patch('/penjualan/{id}/konfirmasi-pembayaran', [PenjualanController::class, 'konfirmasiPembayaran'])->name('penjualan.konfirmasiPembayaran');
    Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan/generate', [LaporanController::class, 'generate'])->name('laporan.generate');
    Route::get('/laporan/{tanggal}/export-pdf', [LaporanController::class, 'exportPdf'])
    ->name('laporan.exportPdf');
    Route::get('/laporan/riwayat', [LaporanController::class, 'riwayat'])->name('laporan.riwayat');


    // Maintenance
    Route::get('/maintenance', [MaintenanceController::class, 'index'])
    ->name('maintenance.index');
    Route::get('/maintenance/{id}', [MaintenanceController::class, 'show'])
    ->name('maintenance.show');
    Route::post('/maintenance', [MaintenanceController::class, 'store'])
    ->name('maintenance.store');
    Route::patch('/maintenance/{id}/description', [MaintenanceController::class, 'updateDescription'])
    ->name('maintenance.updateDescription');
    Route::post('/maintenance/{id}/feedback', [MaintenanceController::class, 'addFeedback'])
    ->name('maintenance.feedback');
    Route::post('/maintenance/{id}/resolve', [MaintenanceController::class, 'resolve'])
    ->name('maintenance.resolve');

    // Checksheet — Staf
    Route::middleware('role:staf')->group(function () {
        Route::get('/checksheet', [ChecksheetController::class, 'index'])->name('checksheet');
        Route::post('/checksheet/store', [ChecksheetController::class, 'store'])->name('checksheet.store');
    });
    
});

// =========================================
// OPERATIONAL — OWNER ONLY
// =========================================

Route::middleware(['auth', 'role:owner'])->prefix('operational')->name('operational.')->group(function () {

    // Delete Sewa
    Route::delete('/sewa/{id}', [SewaOperasionalController::class, 'destroy'])->name('sewa.destroy');

    // Delete Booking
    Route::delete('/booking/{id}', [BookingOperasionalController::class, 'destroy'])->name('booking.destroy');

    // Hapus Laporan (delete)
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');

    // Kelola Staf
    Route::get('/kelola-staf', [KelolaStafController::class, 'index'])->name('kelola-staf');
    Route::post('/kelola-staf', [KelolaStafController::class, 'store'])->name('kelola-staf.store');
    Route::put('/kelola-staf/{user}', [KelolaStafController::class, 'update'])->name('kelola-staf.update');
    Route::delete('/kelola-staf/{user}', [KelolaStafController::class, 'destroy'])->name('kelola-staf.destroy');

    // Kelola Harga
    Route::get('/kelola-harga', [KelolaHargaController::class, 'index'])->name('kelola-harga');

    // Paket Harga Sewa
    Route::post('/kelola-harga/sewa', [KelolaHargaController::class, 'storeSewa'])->name('kelola-harga.sewa.store');
    Route::put('/kelola-harga/sewa/{paket}', [KelolaHargaController::class, 'updateSewa'])->name('kelola-harga.sewa.update');
    Route::delete('/kelola-harga/sewa/{paket}', [KelolaHargaController::class, 'destroySewa'])->name('kelola-harga.sewa.destroy');

    // Paket Harga Booking
    Route::post('/kelola-harga/booking', [KelolaHargaController::class, 'storeBooking'])->name('kelola-harga.booking.store');
    Route::put('/kelola-harga/booking/{paket}', [KelolaHargaController::class, 'updateBooking'])->name('kelola-harga.booking.update');
    Route::delete('/kelola-harga/booking/{paket}', [KelolaHargaController::class, 'destroyBooking'])->name('kelola-harga.booking.destroy');

    // Paket Khusus Booking
    Route::post('/kelola-harga/khusus', [KelolaHargaController::class, 'storeKhusus'])->name('kelola-harga.khusus.store');
    Route::put('/kelola-harga/khusus/{paket}', [KelolaHargaController::class, 'updateKhusus'])->name('kelola-harga.khusus.update');
    Route::delete('/kelola-harga/khusus/{paket}', [KelolaHargaController::class, 'destroyKhusus'])->name('kelola-harga.khusus.destroy');

    // Kelola Stok
    Route::get('/stok', [KelolaStokController::class, 'index'])->name('stok');
    Route::post('/stok', [KelolaStokController::class, 'store'])->name('stok.store');
    Route::put('/stok/{stok}', [KelolaStokController::class, 'update'])->name('stok.update');
    Route::post('/stok/{stok}/restock', [KelolaStokController::class, 'restock'])->name('stok.restock');
    Route::delete('/stok/{stok}', [KelolaStokController::class, 'destroy'])->name('stok.destroy');

    // Kelola Checksheet
    Route::get('/checksheet/riwayat', [ChecksheetController::class, 'ownerIndex'])->name('checksheet.riwayat');
    Route::get('/checksheet/manage', [ChecksheetController::class, 'manage'])->name('checksheet.manage');
    Route::post('/checksheet/item/store', [ChecksheetController::class, 'itemStore'])->name('checksheet.item.store');
    Route::put('/checksheet/item/{checksheet_item}/update', [ChecksheetController::class, 'itemUpdate'])->name('checksheet.item.update');
    Route::delete('/checksheet/item/{checksheet_item}/delete', [ChecksheetController::class, 'itemDelete'])->name('checksheet.item.delete');
    Route::get('/checksheet/show/{checksheetHeader}', [ChecksheetController::class, 'show'])->name('checksheet.show');
    
    });

// =========================================
// AUTH
// =========================================

require __DIR__.'/auth.php';