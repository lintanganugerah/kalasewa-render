<?php


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

use App\Http\Controllers\KeuanganPenyewaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutentikasiBuyerController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProdukSellerController;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\AutentikasiSellerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\StatusPenyewaanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PenilaianSisiSellerController;
use App\Http\Controllers\BanksController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\Pemilik\PengajuanDendaController;
use App\Http\Controllers\Pemilik\PenarikanSaldoController;
use App\Http\Controllers\Pemilik\RekeningController;
use App\Http\Controllers\Pemilik\TiketController;
use App\Http\Controllers\Pemilik\KeuanganController;


use App\Http\Controllers\TicketingController;

// HOMEPAGE
Route::get('/', [PublicController::class, 'viewHomepage'])->name('viewHomepage');
Route::get('/series', [PublicController::class, 'viewSeries'])->name('viewSeries');
Route::get('/jadiseller', [SellerController::class, 'jadiSellerView'])->name('jadiSellerView');
Route::get('/test', [SellerController::class, 'testView'])->name('seller.testView');

// REGISTER
Route::get('/daftar/penyewa', [AutentikasiController::class, 'registerViewPenyewa'])->name('registerViewPenyewa');
Route::get('/daftar/pemiliksewa', [AutentikasiController::class, 'registerViewPemilikSewa'])->name('registerViewPemilikSewa');
Route::post('/daftar/act/penyewa', [AutentikasiController::class, 'checkEmailPenyewa'])->name('checkEmailPenyewa');
Route::post('/daftar/act/pemiliksewa', [AutentikasiController::class, 'checkEmailPemilikSewa'])->name('checkEmailPemilikSewa');
Route::get('/daftar/penyewa/informasi', [AutentikasiController::class, 'registerInformationViewPenyewa'])->name('registerInformationViewPenyewa');
Route::get('/daftar/pemiliksewa/informasi', [AutentikasiController::class, 'registerInformationViewPemilikSewa'])->name('registerInformationViewPemilikSewa');
Route::post('/daftar/penyewa/informasi/act', [AutentikasiController::class, 'registerInformationActionPenyewa'])->name('registerInformationActionPenyewa');
Route::post('/daftar/pemiliksewa/informasi/act', [AutentikasiController::class, 'registerInformationActionPemilikSewa'])->name('registerInformationActionPemilikSewa');

// VERIFIKASI
Route::get('/daftar/verifikasi/otp', [AutentikasiController::class, 'verifikasiViewOTP'])->name('verifikasiViewOTP');
Route::post('/verify-otp', [AutentikasiController::class, 'verifyOTP'])->name('verifyOTP');

// LOGIN
Route::get('/login', [AutentikasiController::class, 'loginView'])->name('loginView');
Route::post('/login/act', [AutentikasiController::class, 'loginAction'])->name('loginAction');
Route::get('/login/view', [AutentikasiController::class, 'loginView'])->name('login'); //buat chat

// LUPA PASSWORD
Route::get('/forgot-password', [AutentikasiController::class, 'viewForgotPass'])->name('viewForgotPass');
Route::post('/forgot-password', [AutentikasiController::class, 'ForgotPassAction'])->name('ForgotPassAction');
Route::get('/reset-password/{token}', [AutentikasiController::class, 'viewResetPass'])->name('password.reset');
Route::post('/reset-password/{token}/act', [AutentikasiController::class, 'resetPassAction'])->name('resetPassAction');

// PUBLIC ROUTE
Route::get('/catalog/detail/{id}', [PublicController::class, 'viewDetail'])->name('viewDetail');
Route::get('/rules', [PublicController::class, 'viewRules'])->name('viewRules');
Route::get('/about', [PublicController::class, 'viewAbout'])->name('viewAbout');
Route::get('/pencarian', [PublicController::class, 'viewPencarian'])->name('viewPencarian');
Route::get('/pencarian/search', [PublicController::class, 'searchProduk'])->name('searchProduk');
Route::get('/toko', [PublicController::class, 'viewListToko'])->name('viewListToko');
Route::get('/toko/search', [PublicController::class, 'searchToko'])->name('searchToko');
Route::get('/toko/{id}', [PublicController::class, 'viewToko'])->name('viewToko');
Route::get('/toko/{id}/search', [PublicController::class, 'searchProdukToko'])->name('searchProdukToko');

//Logout
Route::get('/logout', [AutentikasiController::class, 'logout'])->name('logout');

// MIDDLEWARE PENYEWA
Route::group(['middleware' => 'penyewa'], function () {

    //Manajemen Profil
    Route::get('/user/profile/{id}', [PenyewaController::class, 'viewProfile'])->name('viewProfile');
    Route::post('/user/profile/update/act', [PenyewaController::class, 'updateProfile'])->name('updateProfilAction');


    //Update Password
    Route::get('/user/profile/password/{id}', [PenyewaController::class, 'viewGantiPassword'])->name('viewGantiPassword');
    Route::post('/user/profile/password/update', [PenyewaController::class, 'updatePassword'])->name('updatePasswordAction');

    //Wishlist
    Route::post('/wishlist/add/{id}', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('/wishlist/remove/{id}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
    Route::get('/wishlist', [WishlistController::class, 'viewWishlist'])->name('wishlist.view');

    //Order Produk
    Route::get('/produk/{id}/create/order', [OrderController::class, 'viewOrder'])->name('viewOrder');
    Route::post('/produk/{id}/create/order/act', [OrderController::class, 'createOrder'])->name('createOrder');
    Route::get('/order/checkout', [OrderController::class, 'viewCheckout'])->name('viewCheckout');

    Route::post('/order/checkout/cek-status', [OrderController::class, 'cekStatusProduk'])->name('cekStatusProduk');
    Route::post('/order/checkout/getTransaction', [OrderController::class, 'getTransaction'])->name('getTransaction');

    Route::post('/order/checkout/update-status', [OrderController::class, 'updateCheckout'])->name('updateCheckout');
    Route::get('/order/{orderId}/detail/pemesanan', [OrderController::class, 'viewDetailPemesanan'])->name('viewDetailPemesanan');
    Route::post('/order/{orderId}/detail/pemesanan/diterima/act', [OrderController::class, 'terimaBarang'])->name('terimaBarang');
    Route::get('/order/{orderId}/detail/pemgembalian', [OrderController::class, 'viewPengembalianBarang'])->name('viewPengembalianBarang');
    Route::post('/order/{orderId}/detail/pemgembalian/act', [OrderController::class, 'returBarang'])->name('returBarang');
    Route::get('/order/{orderId}/detail/penyewaan/selesai', [OrderController::class, 'viewPenyewaanSelesai'])->name('viewPenyewaanSelesai');

    Route::get('/order/{orderId}/detail/penyewaan/dibatalkan-toko', [OrderController::class, 'viewDibatalkanPemilikSewa'])->name('viewDibatalkanPemilikSewa');

    //History
    Route::get('/user/history/semua', [HistoryController::class, 'viewHistory'])->name('viewHistory');
    Route::get('/user/history/menunggu-diproses', [HistoryController::class, 'viewHistoryMenungguDiproses'])->name('viewHistoryMenungguDiproses');
    Route::get('/user/history/dalam-pengiriman', [HistoryController::class, 'viewHistoryDalamPengiriman'])->name('viewHistoryDalamPengiriman');
    Route::get('/user/history/sedang-berlangsung', [HistoryController::class, 'viewHistorySedangBerlangsung'])->name('viewHistorySedangBerlangsung');
    Route::get('/user/history/telah-kembali', [HistoryController::class, 'viewHistoryTelahKembali'])->name('viewHistoryTelahKembali');
    Route::get('/user/history/penyewaan-selesai', [HistoryController::class, 'viewHistoryPenyewaanSelesai'])->name('viewHistoryPenyewaanSelesai');
    Route::get('/user/history/dibatalkan', [HistoryController::class, 'viewHistoryDibatalkan'])->name('viewHistoryDibatalkan');
    Route::get('/user/history/diretur', [HistoryController::class, 'viewHistoryDiretur'])->name('viewHistoryDiretur');

    //Refund
    Route::get('/user/penarikan', [KeuanganPenyewaController::class, 'viewPenarikan'])->name('viewPenarikan');
    Route::post('/refund/set/rekening/act', [KeuanganPenyewaController::class, 'setRekening'])->name('setRekening');
    Route::post('/refund/tarik/saldo/act', [KeuanganPenyewaController::class, 'tarikSaldo'])->name('tarikSaldo');
    Route::get('/user/penarikan/ubah', [KeuanganPenyewaController::class, 'viewUbahRekening'])->name('viewUbahRekening');
    Route::get('/user/penarikan/tarik', [KeuanganPenyewaController::class, 'viewTarikRekening'])->name('viewTarikRekening');

    //Retur
    Route::post('/order/{orderId}/detail/retur/act', [OrderController::class, 'ajukanRefund'])->name('ajukanRefund');
    Route::post('/order/{orderId}/detail/refund/act', [OrderController::class, 'returBarangRefund'])->name('returBarangRefund');


    //Ticketing
    Route::get('/kalasewa/ticketing', [TicketingController::class, 'viewTicketing'])->name('viewTicketing');
    Route::get('/kalasewa/ticketing/create', [TicketingController::class, 'viewNewTicketing'])->name('viewNewTicketing');
    Route::post('/kalasewa/ticketing/create/act', [TicketingController::class, 'createTicket'])->name('createTicket');


    // Tes route getTransaction midtrans
    // Route::post('/order/checkout/cekTransaksi', [OrderController::class, 'getTransaction'])->name('tesCekCheckout');

    Route::post('/order/denda/createSnap/{orderId}', [OrderController::class, 'createSnapTokenDenda'])->name('createSnapTokenDenda');
    Route::post('/order/denda/createSnap/updatePenghasilan/{orderId}', [OrderController::class, 'updatePenghasilan'])->name('updatePenghasilan');
    Route::post('/order/denda-lain/createSnap/{orderId}', [OrderController::class, 'createSnapTokenDendaLain'])->name('createSnapTokenDendaLain');
    Route::post('/order/denda-lain/createSnap/updatePenghasilan/{orderId}', [OrderController::class, 'updatePenghasilanDendaLain'])->name('updatePenghasilanDendaLain');
    Route::post('/order/denda-retur/createSnap/{orderId}', [OrderController::class, 'createSnapTokenDendaRetur'])->name('createSnapTokenDendaRetur');
    Route::post('/order/denda-retur/createSnap/updatePenghasilan/{orderId}', [OrderController::class, 'updatePenghasilanDendaRetur'])->name('updatePenghasilanDendaRetur');
    //Ticketing
    Route::get('/kalasewa/ticketing', [TicketingController::class, 'viewTicketing'])->name('viewTicketing');
    Route::get('/kalasewa/ticketing/create', [TicketingController::class, 'viewNewTicketing'])->name('viewNewTicketing');
    Route::post('/kalasewa/ticketing/create/act', [TicketingController::class, 'createTicket'])->name('createTicket');


    // Tes route getTransaction midtrans
    // Route::post('/order/checkout/cekTransaksi', [OrderController::class, 'getTransaction'])->name('tesCekCheckout');
});

//Pemilik Sewa SEMUA ROUTE
Route::group(['middleware' => 'pemilik_sewa'], function () {

    Route::get('/dashboard/toko', [SellerController::class, 'sellerDashboardToko'])->name('seller.dashboardtoko');

    //TOKO ROUTE
    Route::get('/profil/toko', [SellerController::class, 'profilTokoView'])->name('seller.profilTokoView');

    Route::post('/profil/toko/act', [SellerController::class, 'profilTokoAction'])->name('seller.profilTokoAction');

    //PERATURAN SEWA
    Route::get('/profil/toko/peraturansewa', [SellerController::class, 'viewPeraturanSewaToko'])->name('seller.profil.viewPeraturanSewaToko');
    Route::get('/profil/toko/peraturansewa/edit/{id}', [SellerController::class, 'viewEditPeraturanSewa'])->name('seller.profil.viewEditPeraturanSewa');
    Route::get('/profil/toko/peraturansewa/tambah', [SellerController::class, 'viewTambahPeraturanSewa'])->name('seller.profil.viewTambahPeraturanSewa');

    Route::post('/profil/toko/peraturansewa/edit/{id}/act', [SellerController::class, 'EditPeraturanSewaAction'])->name('seller.profil.EditPeraturanSewaAction');
    Route::post('/profil/toko/peraturansewa/tambah/act', [SellerController::class, 'TambahPeraturanSewaAction'])->name('seller.profil.TambahPeraturanSewaAction');
    Route::post('/profil/toko/peraturansewa/delete/{id}', [SellerController::class, 'DeletePeraturanSewaAction'])->name('seller.profil.DeletePeraturanSewaAction');

    //ALAMAT TAMBAHAN / LAINNYA
    Route::get('/profil/toko/AlamatTambahan', [SellerController::class, 'viewAlamatTambahanToko'])->name('seller.profil.viewAlamatTambahanToko');
    Route::get('/profil/toko/AlamatTambahan/edit/{id}', [SellerController::class, 'viewEditAlamatTambahan'])->name('seller.profil.viewEditAlamatTambahan');
    Route::get('/profil/toko/AlamatTambahan/tambah', [SellerController::class, 'viewTambahAlamatTambahan'])->name('seller.profil.viewTambahAlamatTambahan');

    Route::post('/profil/toko/AlamatTambahan/edit/{id}/act', [SellerController::class, 'EditAlamatTambahanAction'])->name('seller.profil.EditAlamatTambahanAction');
    Route::post('/profil/toko/AlamatTambahan/tambah/act', [SellerController::class, 'TambahAlamatTambahanAction'])->name('seller.profil.TambahAlamatTambahanAction');
    Route::post('/profil/toko/AlamatTambahan/delete/{id}', [SellerController::class, 'DeleteAlamatTambahanAction'])->name('seller.profil.DeleteAlamatTambahanAction');

    //PRODUK ROUTE
    Route::get('/produk/tambahproduk', [ProdukSellerController::class, 'viewTambahProduk'])->name('seller.viewTambahProduk');

    Route::get('/produk/produkanda', [ProdukSellerController::class, 'viewProdukAnda'])->name('seller.viewProdukAnda');

    Route::post('/produk/tambahproduk/act', [ProdukSellerController::class, 'tambahProdukAction'])->name('seller.tambahProdukAction');

    Route::post('/produk/{id}/arsipkan', [ProdukSellerController::class, 'arsipProduk'])->name('seller.arsipProduk');
    Route::post('/produk/{id}/aktifkan', [ProdukSellerController::class, 'aktifkanProduk'])->name('seller.aktifkanProduk');
    Route::post('/produk/{id}/delete', [ProdukSellerController::class, 'hapusProduk'])->name('seller.hapusProduk');

    Route::get('/produk/edit/{id}', [ProdukSellerController::class, 'viewEditProduk'])->name('seller.viewEditProduk');
    Route::post('/produk/edit/{id}/act', [ProdukSellerController::class, 'editProdukAction'])->name('seller.editProdukAction');
    Route::post('/produk/foto/{id}/delete', [ProdukSellerController::class, 'hapusFoto'])->name('seller.hapusFoto');

    //LOGOUT ROUTE

    Route::get('/logout/seller', [AutentikasiSellerController::class, 'logout'])->name('seller.logout');

    //PESANAN
    Route::get('/status-sewa/belumdiproses', [StatusPenyewaanController::class, 'viewBelumDiProses'])->name('seller.statuspenyewaan.belumdiproses');
    Route::get('/status-sewa/dalampengiriman', [StatusPenyewaanController::class, 'viewDalamPengiriman'])->name('seller.statuspenyewaan.dalampengiriman');
    Route::get('/status-sewa/sedangberlangsung', [StatusPenyewaanController::class, 'viewSedangBerlangsung'])->name('seller.statuspenyewaan.sedangberlangsung');
    Route::get('/status-sewa/telahkembali', [StatusPenyewaanController::class, 'viewTelahKembali'])->name('seller.statuspenyewaan.telahkembali');
    Route::get('/status-sewa/penyewaandiretur', [StatusPenyewaanController::class, 'viewPenyewaanDiretur'])->name('seller.statuspenyewaan.penyewaandiretur');
    Route::get('/status-sewa/riwayat', [StatusPenyewaanController::class, 'viewRiwayatPenyewaan'])->name('seller.statuspenyewaan.riwayatPenyewaan');

    //KONFIRMASI RETUR SELESAI
    Route::post('/konfirmasi/returSelesai/{nomor_order}', [StatusPenyewaanController::class, 'returSelesai'])->name('seller.statuspenyewaan.returSelesai');

    //INGATKAN PENYEWA
    Route::post('/reminder/order/{nomor_order}/{id_penyewa}', [StatusPenyewaanController::class, 'ingatkanPenyewa'])->name('seller.statuspenyewaan.ingatkanPenyewa');

    //ORDER DIBATALKAN PEMILIK SEWA
    Route::post('/batal/order/{nomor_order}', [StatusPenyewaanController::class, 'dibatalkanPemilikSewa'])->name('seller.statuspenyewaan.dibatalkanPemilikSewa');

    //INPUT RESI
    Route::post('/inputresi/order/{nomor_order}', [StatusPenyewaanController::class, 'inputResi'])->name('seller.statuspenyewaan.inputResi');

    //REVIEW
    Route::get('/penilaian/produk', [PenilaianSisiSellerController::class, 'viewpenilaianProduk'])->name('seller.view.penilaian.penilaianProduk');
    Route::get('/penilaian/produk/{id}/detail', [PenilaianSisiSellerController::class, 'viewdetailPenilaianProduk'])->name('seller.view.penilaian.detailPenilaianProduk');
    Route::get('/penilaian/penyewa/{id}', [PenilaianSisiSellerController::class, 'viewReviewPenyewa'])->name('seller.view.penilaian.reviewPenyewa');

    //TAMBAH REVIEW PENYEWA DAN KONFIRMASI PENYEWAAN SELESAI
    Route::post('/tambah/penilaian/penyewa/{id}/{nomor_order}', [PenilaianSisiSellerController::class, 'viewTambahReviewPenyewa'])->name('seller.view.penilaian.TambahReviewPenyewa');
    Route::post('/tambah/penilaian/penyewa/{id}/{nomor_order}/act', [PenilaianSisiSellerController::class, 'tambahReviewPenyewaAction'])->name('seller.view.penilaian.tambahReviewPenyewaAction');

    // tiket
    Route::get('/tiket', [TiketController::class, 'index'])->name('seller.tiket.index');
    Route::get('/tiket/create', [TiketController::class, 'createTicketing'])->name('seller.tiket.createTicketing');
    Route::post('/tiket/create', [TiketController::class, 'storeTicketingAction'])->name('seller.tiket.storeTicketingAction');

    //Keuangan
    Route::get('/keuangan', [KeuanganController::class, 'dashboardKeuangan'])->name('seller.keuangan.dashboardKeuangan');
    Route::get('/keuangan/riwayat-penarikan', [KeuanganController::class, 'viewRiwayatPenarikan'])->name('seller.penarikan.viewRiwayatPenarikan');
    Route::post('/keuangan/getTotalPenghasilan', [KeuanganController::class, 'getTotalPenghasilan'])->name('seller.keuangan.getTotalPenghasilan');
    Route::get('/export_pdf', [KeuanganController::class, 'export_pdf'])->name('export_pdf');

    //Rekening
    Route::get('/rekening/set', [RekeningController::class, 'viewSetRekening'])->name('seller.rekening.viewSetRekening');
    Route::post('/rekening/store', [RekeningController::class, 'store'])->name('seller.rekening.store');

    // penarikan
    Route::get('/penarikan', [PenarikanSaldoController::class, 'viewTarikSaldo'])->name('seller.penarikan.viewTarikSaldo');
    Route::post('/penarikan', [PenarikanSaldoController::class, 'store'])->name('seller.penarikan-saldo.store');
    Route::post('/penarikan/sendotp', [PenarikanSaldoController::class, 'sendOTPpenarikan'])->name('seller.kodeOTPpenarikan.send');

    //Pengajuan Denda
    Route::get('/denda/{nomor_order}', [PengajuanDendaController::class, 'viewPengajuanDenda'])->name('seller.viewPengajuanDenda');
    Route::get('/detail/denda/{nomor_order}', [PengajuanDendaController::class, 'viewDetailPengajuanDenda'])->name('seller.viewDetailPengajuanDenda');
    Route::post('/denda/{nomor_order}', [PengajuanDendaController::class, 'pengajuanDendaAction'])->name('seller.pengajuanDendaAction');
    Route::post('/denda/batalkan/{nomor_order}/{id}', [PengajuanDendaController::class, 'batalkanPengajuanDenda'])->name('seller.batalkanPengajuanDendaAction');

    //Top series
    Route::get('/top-series/{period}', [SellerController::class, 'getTopSeries']);
    //Top produk
    Route::get('/top-produk/{period}', [SellerController::class, 'getTopProduk']);
    Route::get('/keuangan-bulan/{period}', [KeuanganController::class, 'getPenghasilanBulan']);
});

// ALL Admin Route
Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin/dashboard', [UserController::class, 'userCount'])->name('admin.dashboard');

    // Series Routes
    Route::get('/admin/series', [SeriesController::class, 'index'])->name('admin.series.index');
    Route::get('/admin/series/search', [SeriesController::class, 'search'])->name('admin.series.search');
    Route::get('/admin/series/{series}/edit', [SeriesController::class, 'edit'])->name('admin.series.edit');
    Route::put('/admin/series/{series}', [SeriesController::class, 'update'])->name('admin.series.update');
    Route::delete('/admin/series/{series}', [SeriesController::class, 'destroy'])->name('admin.series.destroy');
    Route::get('/admin/series/create', [SeriesController::class, 'create'])->name('admin.series.create');
    Route::post('/admin/series', [SeriesController::class, 'store'])->name('admin.series.store');

    // Users Routes
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users/{user}/nonaktifkan', [UserController::class, 'nonaktifkanUser'])->name('admin.users.nonaktifkan');
    Route::get('/admin/users/search', [UserController::class, 'search'])->name('admin.users.search');

    // Verify Routes
    Route::get('/admin/verify', [UserController::class, 'index_verify'])->name('admin.verify.index');
    Route::put('/admin/users/{user}/verify', [UserController::class, 'updateVerification'])->name('admin.users.updateVerification');
    Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::post('/admin/users/{user}/reject', [UserController::class, 'reject'])->name('admin.users.reject');

    // Peraturan Platform
    // About Us
    Route::get('/admin/aboutus', [AdminController::class, 'indexAboutUs'])->name('admin.aboutus.index');
    Route::get('/admin/aboutus/edit', [AdminController::class, 'editAboutUs'])->name('admin.aboutus.edit');
    Route::post('/admin/aboutus/update', [AdminController::class, 'updateAboutUs'])->name('admin.aboutus.update');

    // Regulations Platform
    Route::get('/admin/regulations', [AdminController::class, 'indexRegulations'])->name('admin.regulations.index');
    Route::post('/admin/regulations/update', [AdminController::class, 'updateRegulations'])->name('admin.regulations.update');
    Route::get('/admin/regulations/{id}/edit', [AdminController::class, 'editRegulations'])->name('admin.regulations.edit');

    // Banks Routes
    Route::get('/admin/banks/{id}/edit', [BanksController::class, 'edit'])->name('admin.banks.edit');
    Route::put('/admin/banks/{id}', [BanksController::class, 'update'])->name('admin.banks.update');
    Route::delete('/admin/banks/{id}', [BanksController::class, 'destroy'])->name('admin.banks.destroy');
    Route::get('/admin/banks/create', [BanksController::class, 'create'])->name('admin.banks.create');
    Route::post('/admin/banks', [BanksController::class, 'store'])->name('admin.banks.store');
    Route::get('/admin/banks', [BanksController::class, 'index'])->name('admin.banks.index');
    Route::get('/admin/banks/search', [BanksController::class, 'search'])->name('admin.banks.search');

    // Refunds Routes
    Route::get('/admin/refund', [FundsController::class, 'index'])->name('admin.refunds.index');
    Route::post('/admin/refunds/transfer/{id}', [FundsController::class, 'transfer'])->name('admin.refunds.transfer');
    Route::get('/admin/refunds/{id}', [FundsController::class, 'show'])->name('admin.refunds.show');
    Route::post('/admin/refunds/process', [FundsController::class, 'process'])->name('admin.refunds.process');
    Route::post('/admin/refunds/reject', [FundsController::class, 'reject'])->name('admin.refunds.reject');

    // Ticket Routes
    Route::get('/admin/ticket', [TicketingController::class, 'index'])->name('admin.ticket.index');

    Route::get('/admin/ticket/category', [TicketingController::class, 'showTicketCategory'])->name('admin.ticket.category');
    Route::post('/admin/ticket/category', [TicketingController::class, 'storeTicketCategory'])->name('admin.ticket.category.store');
    Route::get('/admin/ticket/category/edit/{id}', [TicketingController::class, 'editTicketCategory'])->name('admin.ticket.editCategory');
    Route::post('/admin/ticket/category/update/{id}', [TicketingController::class, 'updateTicketCategory'])->name('admin.ticket.category.update');
    Route::delete('/admin/ticket/category/{id}/delete', [TicketingController::class, 'destroyTicketCategory'])->name('admin.ticket.category.delete');


    Route::get('/admin/ticket/{id}', [TicketingController::class, 'show'])->name('admin.ticket.show');
    Route::post('/admin/ticket/{id}/reject', [TicketingController::class, 'reject'])->name('admin.ticket.reject');
    Route::post('/admin/ticket/{id}/process', [TicketingController::class, 'process'])->name('admin.ticket.process');
    Route::post('/ticket/{id}/complete', [TicketingController::class, 'complete'])->name('admin.ticket.complete');

    // Retur Routes
    Route::get('/admin/retur', [TicketingController::class, 'indexRetur'])->name('admin.retur.index');
    Route::get('/admin/retur/{nomor_order}', [TicketingController::class, 'showRetur'])->name('admin.retur.show');
    Route::post('/admin/retur/{nomor_order}/complete', [TicketingController::class, 'completeRetur'])->name('admin.retur.complete');
    Route::get('/admin/retur/{nomor_order}/reject', [TicketingController::class, 'rejectRetur'])->name('admin.retur.reject');

    // Penalty Routes
    Route::get('/admin/penalty', [FundsController::class, 'indexPenalty'])->name('admin.penalty.index');
    Route::get('/admin/penalty/{id}', [FundsController::class, 'showPenalty'])->name('admin.penalty.show');
    Route::post('/admin/penalty/{id}/confirm', [FundsController::class, 'confirmPenalty'])->name('admin.penalty.confirm');
    Route::post('/admin/penalty/{id}/hitnrun', [FundsController::class, 'hitnrunPenalty'])->name('admin.penalty.hitnrun');
    Route::post('/admin/penalty/reject/{id}', [FundsController::class, 'rejectPenalty'])->name('admin.penalty.reject');

    // Logout
    Route::get('/logout/admin', [AutentikasiController::class, 'logout'])->name('admin.logout');
    // Ubah Sandi
    Route::get('/admin/ubahsandi', [AutentikasiController::class, 'ubahSandi'])->name('admin.ubahSandi');
    Route::post('/admin/ubahsandi', [AutentikasiController::class, 'updateSandi'])->name('admin.updateSandi');
});