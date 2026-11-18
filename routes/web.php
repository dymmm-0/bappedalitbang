<?php

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {

Route::get('/','Beranda_Controller@index');

// Preview desain glassmorphism (demonstrasi, bukan bagian produksi)
Route::get('/preview-glass', function () {
    return view('glass_preview', ['variant' => 'neon']);
});

// Preview glassmorphism versi governance (palet warna situs saat ini)
Route::get('/preview-glass-gov', function () {
    return view('glass_preview', ['variant' => 'gov']);
});

Route::get('search', 'Beranda_Controller@search');

Route::get('detail/{artikel_id}','Beranda_Controller@detail');

Route::post('komentar/{artikel_id}','Beranda_Controller@komentar');

Route::post('kontak','Beranda_Controller@kirimKontak');

Route::get('artikel','Beranda_Controller@artikel');

Route::get('artikel/kategori/{kategori_id}','Beranda_Controller@artikel_kategori');

//Public content pages (CMS)
Route::get('profil/struktur', 'Halaman_Controller@struktur');
Route::get('profil/tugas-fungsi', 'Halaman_Controller@tugasFungsi');
Route::get('profil/maklumat-pelayanan', 'Halaman_Controller@maklumatPelayanan');
Route::get('profil/pegawai', 'Halaman_Controller@pegawai');
Route::get('profil/alamat', 'Halaman_Controller@alamat');
Route::get('profil/bidang', 'Halaman_Controller@bidang');
Route::get('profil/bidang/{id}', 'Halaman_Controller@bidangDetail');
Route::get('pengumuman', 'Halaman_Controller@pengumuman');
Route::get('pengumuman/{id}', 'Halaman_Controller@pengumumanDetail');
Route::get('penelitian', 'Halaman_Controller@penelitian');
Route::get('penelitian/{id}', 'Halaman_Controller@penelitianDetail');
Route::get('dokumen', 'Halaman_Controller@dokumen');
Route::get('galeri', 'Halaman_Controller@galeri');
Route::get('faq', 'Halaman_Controller@faq');

Route::group(['middleware' => ['auth', 'admin']], function(){
	Route::get('admin','Admin\Beranda_Controller@index');

Route::get('admin/kunjungan','Admin\Kunjungan_Controller@index');
Route::get('admin/kunjungan/data','Admin\Kunjungan_Controller@chartData');


//manage kategori
Route::get('admin/kategori','Admin\Kategori_controller@index');
Route::get('admin/kategori/add', 'Admin\Kategori_controller@add');
Route::post('admin/kategori/add', 'Admin\Kategori_controller@store');
Route::get('admin/kategori/{id}', 'Admin\Kategori_controller@edit');
Route::put('admin/kategori/{id}', 'Admin\Kategori_controller@update');
Route::delete('admin/kategori/{id}', 'Admin\Kategori_controller@delete');



//manage artikel
Route::get('admin/artikel','Admin\Artikel_controller@index');
Route::get('admin/artikel/add','Admin\Artikel_controller@add');
Route::post('admin/artikel/add','Admin\Artikel_controller@store');
Route::get('admin/artikel/{id_artikel}','Admin\Artikel_controller@edit');
Route::put('admin/artikel/{id_artikel}','Admin\Artikel_controller@update');
Route::delete('admin/artikel/{id_artikel}','Admin\Artikel_controller@delete');

//manage komentar
Route::get('admin/komentar','Admin\Komentar_Controller@index');
Route::get('hapus/komentar/{id}','Admin\Komentar_Controller@delete');


//CMS modules (config-driven CRUD)
Route::post('admin/media/upload', 'MediaController@upload')->name('admin.media.upload');
Route::get('admin/crud/{module}', 'Crud_Controller@index')->name('admin.crud.index');
Route::get('admin/crud/{module}/create', 'Crud_Controller@create')->name('admin.crud.create');
Route::post('admin/crud/{module}', 'Crud_Controller@store')->name('admin.crud.store');
Route::post('admin/crud/{module}/reorder', 'Crud_Controller@reorder')->name('admin.crud.reorder');
Route::get('admin/crud/{module}/{id}/edit', 'Crud_Controller@edit')->name('admin.crud.edit');
Route::put('admin/crud/{module}/{id}', 'Crud_Controller@update')->name('admin.crud.update');
Route::delete('admin/crud/{module}/{id}', 'Crud_Controller@destroy')->name('admin.crud.destroy');
Route::post('admin/crud/{module}/{id}/toggle', 'Crud_Controller@toggle')->name('admin.crud.toggle');

// Persetujuan Konten (Kepala Bappedalitbang)
Route::get('admin/persetujuan', 'Admin\PersetujuanController@index')->name('admin.persetujuan.index');
Route::get('admin/persetujuan/{type}/{id}', 'Admin\PersetujuanController@show')->name('admin.persetujuan.show');
Route::post('admin/persetujuan/{type}/{id}/approve', 'Admin\PersetujuanController@approve')->name('admin.persetujuan.approve');
Route::post('admin/persetujuan/{type}/{id}/reject', 'Admin\PersetujuanController@reject')->name('admin.persetujuan.reject');
Route::post('admin/persetujuan/{type}/{id}/revision', 'Admin\PersetujuanController@revision')->name('admin.persetujuan.revision');

//manage settings
Route::get('admin/settings', 'Admin\SettingsController@index')->name('admin.settings.index');
Route::post('admin/settings', 'Admin\SettingsController@store')->name('admin.settings.store');
Route::get('admin/alamat', 'Admin\SettingsController@alamat')->name('admin.settings.alamat');

//manage menus
Route::get('admin/menu', 'Admin\MenuController@index')->name('admin.menus.index');
Route::post('admin/menu', 'Admin\MenuController@store')->name('admin.menus.store');
Route::get('admin/menu/{id}', 'Admin\MenuController@manage')->name('admin.menus.manage');
Route::post('admin/menu/{id}/item', 'Admin\MenuController@storeItem')->name('admin.menus.storeItem');
Route::post('admin/menu/{id}/item/reorder', 'Admin\MenuController@reorder')->name('admin.menus.reorder');
Route::put('admin/menu/{id}/item/{itemId}', 'Admin\MenuController@updateItem')->name('admin.menus.updateItem');
Route::delete('admin/menu/{id}/item/{itemId}', 'Admin\MenuController@deleteItem')->name('admin.menus.deleteItem');

//manage home sections
Route::get('admin/home-sections', 'Admin\HomeSectionController@index')->name('admin.home_sections.index');
Route::put('admin/home-sections/{id}', 'Admin\HomeSectionController@update')->name('admin.home_sections.update');
Route::delete('admin/home-sections/{id}', 'Admin\HomeSectionController@destroy')->name('admin.home_sections.destroy');
Route::post('admin/home-sections/reorder', 'Admin\HomeSectionController@reorder')->name('admin.home_sections.reorder');

//manage user
    Route::get('admin/user','Admin\User_Controller@index');
    Route::get('admin/user/add','Admin\User_Controller@add');
    Route::post('admin/user','Admin\User_Controller@store');
    Route::get('admin/user/{id}','Admin\User_Controller@edit');
    Route::put('admin/user/{id}','Admin\User_Controller@update');
    Route::delete('admin/user/{id}','Admin\User_Controller@delete');
});


Auth::routes();

Route::get('/home', function(){
	return redirect ('admin');
});

Route::get('keluar', function(){
	\Auth::logout();

	return redirect('login');
});

});
