<?php

use CodeIgniter\Router\RouteCollection;

// Login Route
$routes->get('login', 'Auth::index', ['as' => 'login']);
$routes->post('login', 'Auth::login', ['as' => 'login.post']);
$routes->get('logout', 'Auth::logout', ['as' => 'logout']);

// Home Pemilihan
$routes->get('/', 'Home::index', ['as' => 'home']);
$routes->get('voting', 'Home::voting', ['as' => 'voting']);
$routes->get('voting/(:num)', 'Home::votingDetail/$1', ['as' => 'voting.detail']);

// vote
$routes->post('vote', 'Home::vote', ['as' => 'vote']);

// Sertifikat
$routes->get('sertifikat', 'Home::sertifikat', ['as' => 'sertifikat']);

// Berita
$routes->get('berita', 'Home::berita', ['as' => 'berita']);

// Struktur
$routes->get('struktur', 'Home::struktur', ['as' => 'struktur']);

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
    });
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
    $routes->get('confirm-suara/(:num)/(:num)', 'Pemilihan\Calon::confirmVote/$1/$2', ['as' => 'pemilihan.calon.suara.confirm']);
    $routes->get('revoke-suara/(:num)/(:num)', 'Pemilihan\Calon::revokeVote/$1/$2', ['as' => 'pemilihan.calon.suara.revoke']);
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