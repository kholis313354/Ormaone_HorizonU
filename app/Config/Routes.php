<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('contact', 'Contact::index');
$routes->post('contact/send', 'Contact::send');

// Login Route
$routes->get('login', 'Auth::index', ['as' => 'login']);
$routes->post('login', 'Auth::login', ['as' => 'login.post']);
$routes->get('logout', 'Auth::logout', ['as' => 'logout']);

// Home Pemilihan
// Language Route
$routes->get('lang/{locale}', 'Language::switch/$1', ['as' => 'lang.switch']);

$routes->get('/', 'Home::index', ['as' => 'home']);
$routes->get('voting', 'Home::voting', ['as' => 'voting']);
$routes->get('voting/(:any)', 'Home::votingDetail/$1', ['as' => 'voting.detail']);

// vote
$routes->post('vote', 'Home::vote', ['as' => 'vote']);
// OTP Voting (dipindahkan ke Calon controller)
$routes->post('vote/request-otp', 'Pemilihan\Calon::requestOtp', ['as' => 'vote.requestOtp']);
$routes->post('vote/verify-otp', 'Pemilihan\Calon::verifyOtp', ['as' => 'vote.verifyOtp']);

// Sertifikat
$routes->get('sertifikat', 'Sertifikat::all', ['as' => 'sertifikat']);
// Sertifikat Routes
$routes->group('sertifikat', function (RouteCollection $routes) {
    $routes->get('sertifikat', 'Sertifikat::index', ['as' => 'sertifikat.index']);
    $routes->get('/', 'Sertifikat::index', ['as' => 'sertifikat.index']);
    $routes->get('create', 'Sertifikat::create', ['as' => 'sertifikat.create']);
    $routes->post('save', 'Sertifikat::save', ['as' => 'sertifikat.save']);
    $routes->get('download/(:num)', 'Sertifikat::download/$1', ['as' => 'sertifikat.download']);
    $routes->delete('delete/(:num)', 'Sertifikat::delete/$1', ['as' => 'sertifikat.delete']);
    $routes->post('bulkDelete', 'Sertifikat::bulkDelete', ['as' => 'sertifikat.bulkDelete']);
    $routes->get('verify/(:num)', 'Sertifikat::verify/$1', ['as' => 'sertifikat.verify']);
    $routes->get('sertifikat/getMonthlyData', 'Sertifikat::getMonthlyData');
    $routes->get('sertifikat/getAvailableYears', 'Sertifikat::getAvailableYears');
    $routes->get('sertifikat/getFacultyDistribution', 'Sertifikat::getFacultyDistribution');
    $routes->get('sertifikat/monthly-data', 'Sertifikat::getMonthlyData');
    // Untuk data distribusi fakultas
    $routes->get('sertifikat/faculty-distribution', 'Sertifikat::getFacultyDistribution');
    // Untuk daftar tahun yang tersedia
    $routes->get('sertifikat/available-years', 'Sertifikat::getAvailableYears');
    $routes->get('edit/(:num)', 'Sertifikat::edit/$1', ['as' => 'sertifikat.edit']);
    $routes->post('update/(:num)', 'Sertifikat::update/$1', ['as' => 'sertifikat.update']);
    $routes->get('verifikasi/(:num)', 'Sertifikat::verify/$1');
    $routes->get('verifikasi/(:num)', 'Sertifikat::verify/$1');
    $routes->get('verifikasi', 'Sertifikat::verify'); // Untuk handle case tanpa parameter
    // Import Batch Routes
    $routes->get('downloadTemplate', 'Sertifikat::downloadTemplate', ['as' => 'sertifikat.downloadTemplate']);
    $routes->post('importBatch', 'Sertifikat::importBatch', ['as' => 'sertifikat.importBatch']);
    $routes->post('uploadBaseFile', 'Sertifikat::uploadBaseFile', ['as' => 'sertifikat.uploadBaseFile']);
});
$routes->get('verifikasi/(:num)', 'Sertifikat::verify/$1');
$routes->get('verifikasi', 'Sertifikat::verify');
// Tambahkan ini untuk API endpoint statistik
$routes->group('api', ['namespace' => 'App\Controllers'], function (RouteCollection $routes) {
    // Untuk data bulanan
    $routes->get('sertifikat/monthly-data', 'Sertifikat::getMonthlyData');
    $routes->get('api/sertifikat/monthly-data', 'Sertifikat::getMonthlyData');
    // Untuk data distribusi fakultas
    $routes->get('sertifikat/faculty-distribution', 'Sertifikat::getFacultyDistribution');

    // Untuk daftar tahun yang tersedia
    $routes->get('sertifikat/available-years', 'Sertifikat::getAvailableYears');
});
$routes->get('/', 'Home::index');
// Public Routes - Berita/Blog
$routes->group('', function ($routes) {
    // Rute utama untuk berita (diarahkan ke Home::berita)
    $routes->get('berita', 'Home::berita', ['as' => 'berita']);

    // Rute untuk menampilkan detail berita dengan ID terenkripsi
    $routes->get('berita/detail/(:any)', 'PublicBlogController::detail/$1', ['as' => 'public.berita.detail']);

    // Rute untuk pencarian berita
    $routes->get('berita/search', 'PublicBlogController::search', ['as' => 'public.berita.search']);

    // Rute untuk filter berita berdasarkan kategori
    $routes->get('berita/kategori/(:any)', 'PublicBlogController::byCategory/$1', ['as' => 'public.berita.category']);
    // Rute untuk share link yang dienkripsi
    $routes->get('berita/share/(:any)', 'PublicBlogController::share/$1', ['as' => 'public.berita.share']);
    $routes->get('berita/latest', 'Api\BeritaApi::latest');
    $routes->get('berita/older', 'Api\BeritaApi::older');
    $routes->get('berita/kategori', 'Api\BeritaApi::kategori');
});
// Admin

// Hapus semua route yang ada dan ganti dengan ini:

// admin berita
$routes->group('page', ['filter' => 'csrf'], function ($routes) {
    $routes->get('berita', 'BeritaController::index', ['as' => 'berita.index']);
    $routes->get('berita/create', 'BeritaController::create', ['as' => 'berita.create']);
    $routes->post('berita/store', 'BeritaController::store', ['as' => 'berita.store']);
    $routes->get('berita/edit/(:num)', 'BeritaController::edit/$1', ['as' => 'berita.edit']);
    $routes->put('berita/update/(:num)', 'BeritaController::update/$1', ['as' => 'berita.update']);
    $routes->delete('berita/delete/(:num)', 'BeritaController::delete/$1', ['as' => 'berita.delete']);
});


// Public Routes


// Admin Security Logs
$routes->group('admin/keamanan', ['filter' => 'csrf'], function ($routes) {
    $routes->get('/', 'Admin\SecurityController::index', ['as' => 'admin.security.index']);
    $routes->post('block', 'Admin\SecurityController::blockIp', ['as' => 'admin.security.block']);
    $routes->get('unblock/(:num)', 'Admin\SecurityController::unblockIp/$1', ['as' => 'admin.security.unblock']);
    $routes->delete('delete/(:num)', 'Admin\SecurityController::delete/$1', ['as' => 'admin.security.delete']);
});


// Struktur (Public View)
$routes->get('struktur', 'Home::struktur', ['as' => 'struktur']);

// Struktur (Admin CRUD)
$routes->group('struktur', function (RouteCollection $routes) {
    $routes->get('admin', 'StrukturController::index', ['as' => 'struktur.index']);
    $routes->get('admin/create', 'StrukturController::create', ['as' => 'struktur.create']);
    $routes->post('admin/store', 'StrukturController::store', ['as' => 'struktur.store']);
    $routes->get('admin/edit/(:num)', 'StrukturController::edit/$1', ['as' => 'struktur.edit']);
    $routes->put('admin/update/(:num)', 'StrukturController::update/$1', ['as' => 'struktur.update']);
    $routes->delete('admin/delete/(:num)', 'StrukturController::delete/$1', ['as' => 'struktur.delete']);

    // Divisi Routes
    $routes->get('admin/divisi/create', 'StrukturController::createDivisi', ['as' => 'struktur.divisi.create']);
    $routes->post('admin/divisi/store', 'StrukturController::storeDivisi', ['as' => 'struktur.divisi.store']);
    $routes->get('admin/divisi/edit/(:num)', 'StrukturController::editDivisi/$1', ['as' => 'struktur.divisi.edit']);
    $routes->put('admin/divisi/update/(:num)', 'StrukturController::updateDivisi/$1', ['as' => 'struktur.divisi.update']);
    $routes->delete('admin/divisi/delete/(:num)', 'StrukturController::deleteDivisi/$1', ['as' => 'struktur.divisi.delete']);

    // Visi Misi Routes
    $routes->get('admin/visimisi/create', 'StrukturController::createVisiMisi', ['as' => 'struktur.visimisi.create']);
    $routes->post('admin/visimisi/store', 'StrukturController::storeVisiMisi', ['as' => 'struktur.visimisi.store']);
    $routes->get('admin/visimisi/edit/(:num)', 'StrukturController::editVisiMisi/$1', ['as' => 'struktur.visimisi.edit']);
    $routes->put('admin/visimisi/update/(:num)', 'StrukturController::updateVisiMisi/$1', ['as' => 'struktur.visimisi.update']);
    $routes->delete('admin/visimisi/delete/(:num)', 'StrukturController::deleteVisiMisi/$1', ['as' => 'struktur.visimisi.delete']);

    // Proker Routes
    $routes->get('admin/proker/create', 'StrukturController::createProker', ['as' => 'struktur.proker.create']);
    $routes->post('admin/proker/store', 'StrukturController::storeProker', ['as' => 'struktur.proker.store']);
    $routes->get('admin/proker/edit/(:num)', 'StrukturController::editProker/$1', ['as' => 'struktur.proker.edit']);
    $routes->put('admin/proker/update/(:num)', 'StrukturController::updateProker/$1', ['as' => 'struktur.proker.update']);
    $routes->delete('admin/proker/delete/(:num)', 'StrukturController::deleteProker/$1', ['as' => 'struktur.proker.delete']);

    // Anggaran Routes
    $routes->get('admin/anggaran/create', 'StrukturController::createAnggaran', ['as' => 'struktur.anggaran.create']);
    $routes->post('admin/anggaran/store', 'StrukturController::storeAnggaran', ['as' => 'struktur.anggaran.store']);
    $routes->get('admin/anggaran/edit/(:num)', 'StrukturController::editAnggaran/$1', ['as' => 'struktur.anggaran.edit']);
    $routes->put('admin/anggaran/update/(:num)', 'StrukturController::updateAnggaran/$1', ['as' => 'struktur.anggaran.update']);
    $routes->delete('admin/anggaran/delete/(:num)', 'StrukturController::deleteAnggaran/$1', ['as' => 'struktur.anggaran.delete']);
});

// Dashboard
$routes->get('/dashboard', 'Dashboard::index', ['as' => 'dashboard']);

// Organisasi
$routes->group('organisasi', function (RouteCollection $routes) {
    // List Organisasi
    $routes->group('item', function (RouteCollection $routes) {
        $routes->get('/', 'Organisasi\Item::index', ['as' => 'organisasi.item.index']);
        $routes->get('create', 'Organisasi\Item::create', ['as' => 'organisasi.item.create']);
        $routes->post('create', 'Organisasi\Item::store', ['as' => 'organisasi.item.store']);
        $routes->get('edit/(:num)', 'Organisasi\Item::edit/$1', ['as' => 'organisasi.item.edit']);
        $routes->put('edit/(:num)', 'Organisasi\Item::update/$1', ['as' => 'organisasi.item.update']);
        $routes->delete('delete/(:num)', 'Organisasi\Item::delete/$1', ['as' => 'organisasi.item.delete']);
    });

    // List Anggota
    $routes->group('anggota', function (RouteCollection $routes) {
        $routes->get('/', 'Organisasi\Anggota::index', ['as' => 'organisasi.anggota.index']);
        $routes->get('create', 'Organisasi\Anggota::create', ['as' => 'organisasi.anggota.create']);
        $routes->post('create', 'Organisasi\Anggota::store', ['as' => 'organisasi.anggota.store']);
        $routes->get('edit/(:num)', 'Organisasi\Anggota::edit/$1', ['as' => 'organisasi.anggota.edit']);
        $routes->put('edit/(:num)', 'Organisasi\Anggota::update/$1', ['as' => 'organisasi.anggota.update']);
        $routes->delete('delete/(:num)', 'Organisasi\Anggota::delete/$1', ['as' => 'organisasi.anggota.delete']);
    });

    // Kepengurusan
    $routes->group('kepengurusan', function (RouteCollection $routes) {
        $routes->get('/', 'Organisasi\Kepengurusan::index', ['as' => 'organisasi.kepengurusan.index']);
        $routes->get('create', 'Organisasi\Kepengurusan::create', ['as' => 'organisasi.kepengurusan.create']);
        $routes->post('create', 'Organisasi\Kepengurusan::store', ['as' => 'organisasi.kepengurusan.store']);
        $routes->get('edit/(:num)', 'Organisasi\Kepengurusan::edit/$1', ['as' => 'organisasi.kepengurusan.edit']);
        $routes->put('edit/(:num)', 'Organisasi\Kepengurusan::update/$1', ['as' => 'organisasi.kepengurusan.update']);
        $routes->delete('delete/(:num)', 'Organisasi\Kepengurusan::delete/$1', ['as' => 'organisasi.kepengurusan.delete']);
        $routes->get('anggota/(:num)', 'Organisasi\Kepengurusan::getAnggotaByOrganisasi/$1', ['as' => 'organisasi.kepengurusan.anggota']);
        $routes->get('detail-suara/(:num)', 'Organisasi\Kepengurusan::detailSuara/$1', ['as' => 'organisasi.kepengurusan.detailSuara']);
        $routes->get('export-excel/(:num)', 'Organisasi\Kepengurusan::exportExcel/$1', ['as' => 'organisasi.kepengurusan.exportExcel']);
    });

    // Mahasiswa Routes
    $routes->group('mahasiswa', function ($routes) {
        $routes->get('/', 'Organisasi\Mahasiswa::index', ['as' => 'organisasi.mahasiswa.index']);
        $routes->get('create', 'Organisasi\Mahasiswa::create', ['as' => 'organisasi.mahasiswa.create']);
        $routes->post('store', 'Organisasi\Mahasiswa::store', ['as' => 'organisasi.mahasiswa.store']);
        $routes->get('edit/(:num)', 'Organisasi\Mahasiswa::edit/$1', ['as' => 'organisasi.mahasiswa.edit']);
        $routes->put('update/(:num)', 'Organisasi\Mahasiswa::update/$1', ['as' => 'organisasi.mahasiswa.update']);
        $routes->delete('delete/(:num)', 'Organisasi\Mahasiswa::delete/$1', ['as' => 'organisasi.mahasiswa.delete']);
        // Bulk Actions Routes
        $routes->post('bulkDelete', 'Organisasi\Mahasiswa::bulkDelete', ['as' => 'organisasi.mahasiswa.bulkDelete']);
        // Import Batch Routes
        $routes->get('downloadTemplate', 'Organisasi\Mahasiswa::downloadTemplate', ['as' => 'organisasi.mahasiswa.downloadTemplate']);
        $routes->post('importBatch', 'Organisasi\Mahasiswa::importBatch', ['as' => 'organisasi.mahasiswa.importBatch']);
    });
});

// Fakultas
$routes->group('fakultas', function (RouteCollection $routes) {
    $routes->get('/', 'Fakultas::index', ['as' => 'fakultas.index']);
    $routes->get('create', 'Fakultas::create', ['as' => 'fakultas.create']);
    $routes->post('create', 'Fakultas::store', ['as' => 'fakultas.store']);
    $routes->get('edit/(:num)', 'Fakultas::edit/$1', ['as' => 'fakultas.edit']);
    $routes->put('edit/(:num)', 'Fakultas::update/$1', ['as' => 'fakultas.update']);
    $routes->delete('delete/(:num)', 'Fakultas::delete/$1', ['as' => 'fakultas.delete']);
});

// Pemilihan
$routes->group('pemilihan', function (RouteCollection $routes) {
    $routes->get('/', 'Pemilihan::index', ['as' => 'pemilihan.index']);
    $routes->get('create', 'Pemilihan::create', ['as' => 'pemilihan.create']);
    $routes->post('create', 'Pemilihan::store', ['as' => 'pemilihan.store']);
    $routes->get('edit/(:num)', 'Pemilihan::edit/$1', ['as' => 'pemilihan.edit']);
    $routes->put('edit/(:num)', 'Pemilihan::update/$1', ['as' => 'pemilihan.update']);
    $routes->delete('delete/(:num)', 'Pemilihan::delete/$1', ['as' => 'pemilihan.delete']);
});

// Pemilihan Calon
$routes->group('pemilihan/calon', function (RouteCollection $routes) {
    $routes->get('/', 'Pemilihan\Calon::index', ['as' => 'pemilihan.calon.index']);
    $routes->get('create', 'Pemilihan\Calon::create', ['as' => 'pemilihan.calon.create']);
    $routes->post('create', 'Pemilihan\Calon::store', ['as' => 'pemilihan.calon.store']);
    $routes->get('edit/(:num)', 'Pemilihan\Calon::edit/$1', ['as' => 'pemilihan.calon.edit']);
    $routes->put('edit/(:num)', 'Pemilihan\Calon::update/$1', ['as' => 'pemilihan.calon.update']);
    $routes->delete('delete/(:num)', 'Pemilihan\Calon::delete/$1', ['as' => 'pemilihan.calon.delete']);
    $routes->get('anggota/(:num)', 'Pemilihan\Calon::getAnggotaByPemilihan/$1', ['as' => 'pemilihan.calon.anggota']);
    $routes->get('suara/(:num)', 'Pemilihan\Calon::getSuaraByPemilihan/$1', ['as' => 'pemilihan.calon.suara']);
    // confirmVote dan revokeVote dihapus karena voting otomatis terkonfirmasi setelah OTP verified
    $routes->delete('suara/(:num)/delete/(:num)', 'Pemilihan\Calon::deleteVote/$1/$2', ['as' => 'pemilihan.calon.suara.delete']);
});

// User
$routes->group('user', function (RouteCollection $routes) {
    $routes->get('/', 'User::index', ['as' => 'user.index']);
    $routes->get('create', 'User::create', ['as' => 'user.create']);
    $routes->post('create', 'User::store', ['as' => 'user.store']);
    $routes->get('edit/(:num)', 'User::edit/$1', ['as' => 'user.edit']);
    $routes->put('edit/(:num)', 'User::update/$1', ['as' => 'user.update']);
    $routes->delete('delete/(:num)', 'User::delete/$1', ['as' => 'user.delete']);
});

// Document
$routes->group('document', function (RouteCollection $routes) {
    $routes->get('/', 'Document::index', ['as' => 'document.index']);
    $routes->get('create', 'Document::create', ['as' => 'document.create']);
    $routes->post('store', 'Document::store', ['as' => 'document.store']);
    $routes->get('edit/(:num)', 'Document::edit/$1', ['as' => 'document.edit']);
    $routes->post('update/(:num)', 'Document::update/$1', ['as' => 'document.update']);
    $routes->post('delete/(:num)', 'Document::delete/$1', ['as' => 'document.delete']);
    $routes->get('download/(:num)', 'Document::download/$1', ['as' => 'document.download']);
});

// API Document
$routes->group('api', ['namespace' => 'App\Controllers'], function (RouteCollection $routes) {
    $routes->get('document/monthly-data', 'Document::getMonthlyData');
    $routes->get('document/available-years', 'Document::getAvailableYears');
    $routes->get('document/category-distribution', 'Document::getCategoryDistribution');
});

// Profile
$routes->group('profile', function (RouteCollection $routes) {
    $routes->get('edit', 'Profile::edit', ['as' => 'profile.edit']);
    $routes->post('update', 'Profile::update', ['as' => 'profile.update']);
});

// Settings
$routes->group('settings', function (RouteCollection $routes) {
    $routes->get('/', 'Settings::index', ['as' => 'settings.index']);
    $routes->post('update-name', 'Settings::updateName', ['as' => 'settings.updateName']);
    $routes->post('update-email', 'Settings::updateEmail', ['as' => 'settings.updateEmail']);
    $routes->get('verify-email/(:segment)', 'Settings::verifyEmail/$1', ['as' => 'settings.verifyEmail']);
    $routes->post('update-password', 'Settings::updatePassword', ['as' => 'settings.updatePassword']);
    $routes->get('verify-password/(:segment)', 'Settings::verifyPasswordChange/$1', ['as' => 'settings.verifyPassword']);
});

// Pesan (Aspirasi)
$routes->group('pesan', function (RouteCollection $routes) {
    $routes->get('/', 'Pesan::index', ['as' => 'pesan.index']);
    $routes->get('send-email/(:num)', 'Pesan::sendEmail/$1', ['as' => 'pesan.sendEmail']);
    $routes->post('process-reply', 'Pesan::processReply', ['as' => 'pesan.processReply']);
    $routes->delete('delete/(:num)', 'Pesan::delete/$1', ['as' => 'pesan.delete']);
});

// Kalender
$routes->group('kalender', function (RouteCollection $routes) {
    $routes->get('/', 'Kalender::index', ['as' => 'kalender.index']);
    $routes->post('store', 'Kalender::store', ['as' => 'kalender.store']);
    $routes->post('update/(:num)', 'Kalender::update/$1', ['as' => 'kalender.update']);
    $routes->get('delete/(:num)', 'Kalender::delete/$1', ['as' => 'kalender.delete']);
    $routes->get('events', 'Kalender::getEvents', ['as' => 'kalender.getEvents']);
});

// Form (Gform) - Admin Routes
$routes->group('form', function (RouteCollection $routes) {
    $routes->get('/', 'Form::index', ['as' => 'form.index']);
    $routes->get('create', 'Form::create', ['as' => 'form.create']);
    $routes->post('store', 'Form::store', ['as' => 'form.store']);
    $routes->get('edit/(:num)', 'Form::edit/$1', ['as' => 'form.edit']);
    $routes->put('update/(:num)', 'Form::update/$1', ['as' => 'form.update']);
    $routes->delete('delete/(:num)', 'Form::delete/$1', ['as' => 'form.delete']);
    $routes->get('response/(:num)', 'Form::response/$1', ['as' => 'form.response']);
    $routes->get('export/excel/(:num)', 'Form::exportExcel/$1', ['as' => 'form.export.excel']);
    $routes->get('export/print/(:num)', 'Form::printResponse/$1', ['as' => 'form.export.print']);

    // Field Management Routes
    $routes->group('field', function (RouteCollection $routes) {
        $routes->post('store/(:num)', 'Form::storeField/$1', ['as' => 'form.field.store']);
        $routes->put('update/(:num)/(:num)', 'Form::updateField/$1/$2', ['as' => 'form.field.update']);
        $routes->delete('delete/(:num)/(:num)', 'Form::deleteField/$1/$2', ['as' => 'form.field.delete']);
        $routes->post('reorder/(:num)', 'Form::reorderField/$1', ['as' => 'form.field.reorder']);
    });

    // Publish & QR Code Routes
    $routes->post('publish/(:num)', 'Form::publish/$1', ['as' => 'form.publish']);
    $routes->post('unpublish/(:num)', 'Form::unpublish/$1', ['as' => 'form.unpublish']);
    $routes->get('qrcode/(:num)', 'Form::generateQRCode/$1', ['as' => 'form.qrcode']);

    // Image Upload Route
    $routes->post('upload-image', 'Form::uploadImage', ['as' => 'form.uploadImage']);
});

// Form (Gform) - Public Routes (tanpa autentikasi)
$routes->group('form/public', function (RouteCollection $routes) {
    $routes->get('(:any)', 'Form::public/$1', ['as' => 'form.public']);
    $routes->post('submit/(:any)', 'Form::publicSubmit/$1', ['as' => 'form.public.submit']);
});

// Route untuk akses QR Code (fallback jika .htaccess tidak bekerja)
$routes->get('uploads/qrcode/(:any)', 'Form::viewQRCode/$1', ['as' => 'form.qrcode.view']);

// --- HONEYPOT TRAPS (Jebakan) ---
// Redirect URL umum yang sering discan hacker ke HoneypotController
$routes->get('admin_login', 'HoneypotController::trap');
$routes->get('wp-admin', 'HoneypotController::trap');
$routes->get('wp-login.php', 'HoneypotController::trap');
$routes->get('administrator', 'HoneypotController::trap');
$routes->get('cpanel', 'HoneypotController::trap');
$routes->get('db.sql', 'HoneypotController::trap');
$routes->get('backup.zip', 'HoneypotController::trap');
$routes->get('.env', 'HoneypotController::trap');

