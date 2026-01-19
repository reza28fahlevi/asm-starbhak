<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth Routes (No Auth Filter)
$routes->get('/', 'Auth::index', ['filter' => 'noauth']);
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/register', 'Auth::register', ['filter' => 'noauth']);
$routes->post('/auth/doRegister', 'Auth::doRegister');
$routes->get('/auth/logout', 'Auth::logout');

// Protected Routes (Auth Filter)
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('/dashboard', 'Dashboard::index');
    
    // Pages
    $routes->get('/pages/about', 'Pages::about');
    
    // Examples
    $routes->get('/examples/form', 'Examples::form');
    $routes->get('/examples/table', 'Examples::table');
    
    // User Management
    $routes->get('/user', 'User::index');
    $routes->get('/user/getData', 'User::getData'); // AJAX endpoint
    $routes->post('/user/store', 'User::store');
    $routes->get('/user/show/(:num)', 'User::show/$1');
    $routes->post('/user/update/(:num)', 'User::update/$1');
    $routes->post('/user/delete/(:num)', 'User::delete/$1');
    $routes->post('/user/toggleActive/(:num)', 'User::toggleActive/$1');
    
    // Role Management
    $routes->get('/role', 'Role::index');
    $routes->get('/role/getData', 'Role::getData'); // AJAX endpoint
    $routes->post('/role/store', 'Role::store');
    $routes->get('/role/show/(:num)', 'Role::show/$1');
    $routes->post('/role/update/(:num)', 'Role::update/$1');
    $routes->post('/role/delete/(:num)', 'Role::delete/$1');
    
    // Departemen Management
    $routes->get('/departemen', 'Departemen::index');
    $routes->get('/departemen/getData', 'Departemen::getData');
    $routes->post('/departemen/store', 'Departemen::store');
    $routes->get('/departemen/show/(:num)', 'Departemen::show/$1');
    $routes->post('/departemen/update/(:num)', 'Departemen::update/$1');
    $routes->post('/departemen/delete/(:num)', 'Departemen::delete/$1');
    
    // Supplier Management
    $routes->get('/supplier', 'Supplier::index');
    $routes->get('/supplier/getData', 'Supplier::getData');
    $routes->post('/supplier/store', 'Supplier::store');
    $routes->get('/supplier/show/(:num)', 'Supplier::show/$1');
    $routes->post('/supplier/update/(:num)', 'Supplier::update/$1');
    $routes->post('/supplier/delete/(:num)', 'Supplier::delete/$1');
    
    // Lokasi Management
    $routes->get('/lokasi', 'Lokasi::index');
    $routes->get('/lokasi/getData', 'Lokasi::getData');
    $routes->get('/lokasi/getOptions', 'Lokasi::getOptions');
    $routes->post('/lokasi/store', 'Lokasi::store');
    $routes->get('/lokasi/show/(:num)', 'Lokasi::show/$1');
    $routes->post('/lokasi/update/(:num)', 'Lokasi::update/$1');
    $routes->post('/lokasi/delete/(:num)', 'Lokasi::delete/$1');
    
    // Kategori Asset Management
    $routes->get('/kategori-asset', 'KategoriAsset::index');
    $routes->get('/kategori-asset/getData', 'KategoriAsset::getData');
    $routes->post('/kategori-asset/store', 'KategoriAsset::store');
    $routes->get('/kategori-asset/show/(:num)', 'KategoriAsset::show/$1');
    $routes->post('/kategori-asset/update/(:num)', 'KategoriAsset::update/$1');
    $routes->post('/kategori-asset/delete/(:num)', 'KategoriAsset::delete/$1');
    
    // Kelompok Asset Management
    $routes->get('/kelompok-asset', 'KelompokAsset::index');
    $routes->get('/kelompok-asset/getData', 'KelompokAsset::getData');
    $routes->post('/kelompok-asset/store', 'KelompokAsset::store');
    $routes->get('/kelompok-asset/show/(:num)', 'KelompokAsset::show/$1');
    $routes->post('/kelompok-asset/update/(:num)', 'KelompokAsset::update/$1');
    $routes->post('/kelompok-asset/delete/(:num)', 'KelompokAsset::delete/$1');
    
    // Menu Management
    $routes->get('/menu', 'Menu::index');
    $routes->get('/menu/getData', 'Menu::getData');
    $routes->get('/menu/getOptions', 'Menu::getOptions');
    $routes->post('/menu/store', 'Menu::store');
    $routes->get('/menu/show/(:num)', 'Menu::show/$1');
    $routes->post('/menu/update/(:num)', 'Menu::update/$1');
    $routes->post('/menu/delete/(:num)', 'Menu::delete/$1');
    
    // Permohonan Asset Management
    $routes->get('/permohonan-asset', 'PermohonanAsset::index');
    $routes->get('/permohonan-asset/getData', 'PermohonanAsset::getData');
    $routes->get('/permohonan-asset/getOptions', 'PermohonanAsset::getOptions');
    $routes->post('/permohonan-asset/store', 'PermohonanAsset::store');
    $routes->get('/permohonan-asset/show/(:num)', 'PermohonanAsset::show/$1');
    $routes->post('/permohonan-asset/update/(:num)', 'PermohonanAsset::update/$1');
    $routes->post('/permohonan-asset/delete/(:num)', 'PermohonanAsset::delete/$1');
    $routes->post('/permohonan-asset/storeDetail', 'PermohonanAsset::storeDetail');
    $routes->post('/permohonan-asset/deleteDetail/(:num)', 'PermohonanAsset::deleteDetail/$1');
    
    // Approval Permohonan Management
    $routes->get('/approval-permohonan', 'ApprovalPermohonan::index');
    $routes->get('/approval-permohonan/getData', 'ApprovalPermohonan::getData');
    $routes->get('/approval-permohonan/show/(:num)', 'ApprovalPermohonan::show/$1');
    $routes->post('/approval-permohonan/approve/(:num)', 'ApprovalPermohonan::approve/$1');
    $routes->post('/approval-permohonan/reject/(:num)', 'ApprovalPermohonan::reject/$1');
    
    // Pembelian Asset Management
    $routes->get('/pembelian-asset', 'PembelianAsset::index');
    $routes->get('/pembelian-asset/getData', 'PembelianAsset::getData');
    $routes->get('/pembelian-asset/getOptions', 'PembelianAsset::getOptions');
    $routes->post('/pembelian-asset/store', 'PembelianAsset::store');
    $routes->get('/pembelian-asset/show/(:num)', 'PembelianAsset::show/$1');
    $routes->post('/pembelian-asset/update/(:num)', 'PembelianAsset::update/$1');
    $routes->post('/pembelian-asset/delete/(:num)', 'PembelianAsset::delete/$1');
    $routes->post('/pembelian-asset/storeDetail', 'PembelianAsset::storeDetail');
    $routes->post('/pembelian-asset/updateDetail/(:num)', 'PembelianAsset::updateDetail/$1');
    $routes->post('/pembelian-asset/deleteDetail/(:num)', 'PembelianAsset::deleteDetail/$1');
    
    // Penerimaan Asset Management
    $routes->get('/penerimaan-asset', 'PenerimaanAsset::index');
    $routes->get('/penerimaan-asset/getData', 'PenerimaanAsset::getData');
    $routes->get('/penerimaan-asset/getOptions', 'PenerimaanAsset::getOptions');
    $routes->post('/penerimaan-asset/store', 'PenerimaanAsset::store');
    $routes->get('/penerimaan-asset/show/(:num)', 'PenerimaanAsset::show/$1');
    $routes->post('/penerimaan-asset/update/(:num)', 'PenerimaanAsset::update/$1');
    $routes->post('/penerimaan-asset/delete/(:num)', 'PenerimaanAsset::delete/$1');
    $routes->post('/penerimaan-asset/updateQtyGr/(:num)', 'PenerimaanAsset::updateQtyGr/$1');
    
    // Distribusi Asset Management
    $routes->get('/distribusi-asset', 'DistribusiAsset::index');
    $routes->get('/distribusi-asset/getData', 'DistribusiAsset::getData');
    $routes->get('/distribusi-asset/getOptions', 'DistribusiAsset::getOptions');
    $routes->post('/distribusi-asset/store', 'DistribusiAsset::store');
    $routes->get('/distribusi-asset/show/(:num)', 'DistribusiAsset::show/$1');
    $routes->post('/distribusi-asset/update/(:num)', 'DistribusiAsset::update/$1');
    $routes->post('/distribusi-asset/delete/(:num)', 'DistribusiAsset::delete/$1');
    $routes->post('/distribusi-asset/createAssetFromPenerimaan', 'DistribusiAsset::createAssetFromPenerimaan');
});
