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

Route::get('/','MainController@main');
Route::get('/dashboard','MainController@dashboard');

Route::post("/login","MainController@login");
Route::get("/logout","MainController@logout");
Route::get('admin_new_sub_menu',"MainController@admin_new_sub_menu");
Route::post("/sendsubagriculture",'MainController@sendsubagriculture');
Route::get('admin_new_sub_menu_view',"MainController@admin_new_sub_menu_view");
Route::get('admin_new_sub_menu_view_edit',"MainController@admin_new_sub_menu_view_edit");
Route::post("/sendsubagricultureedit",'MainController@sendsubagricultureedit');
Route::get("/admin_new_crop_type","MainController@admin_new_crop_type");
Route::post("/sendagriculture","MainController@sendagriculture");
Route::get('/admin_new_crop_type_view',"MainController@admin_new_crop_type_view");
Route::get('/admin_new_crop_type_view_edit',"MainController@admin_new_crop_type_view_edit");
Route::post('/sendagricultureedit',"MainController@sendagricultureedit");
Route::get('/admin_new_method_cultivation_detail',"MainController@admin_new_method_cultivation_detail");
Route::post('/methodofcultivation',"MainController@methodofcultivation");
Route::get("admin_new_method_cultivation_detail_view","MainController@admin_new_method_cultivation_detail_view");
Route::get('admin_new_method_cultivation_detail_view_edit',"MainController@admin_new_method_cultivation_detail_view_edit");
Route::post('methodofcultivationedit',"MainController@methodofcultivationedit");
Route::get("admin_new_land_detail","MainController@admin_new_land_detail");
Route::post("landdetails","MainController@landdetails");
Route::get("admin_new_land_detail_view","MainController@admin_new_land_detail_view");
Route::get("admin_new_land_detail_view_edit","MainController@admin_new_land_detail_view_edit");
Route::post("landdetailedit","MainController@landdetailedit");
Route::get("homedetails","MainController@homedetails");
Route::post('homedetailsedit',"MainController@homedetailsedit");
Route::get('main_menu_add',"MainController@main_menu_add");
Route::post('adddetails',"MainController@adddetails");
Route::get('main_menu_view',"MainController@main_menu_view");
Route::get('main_menu_edit',"MainController@main_menu_edit");
Route::post('editmainmenudata',"MainController@editmainmenudata");
Route::get('admin_new_water_detail',"MainController@admin_new_water_detail");
Route::post('waterdetail',"MainController@waterdetail");
Route::get('admin_new_water_detail_view',"MainController@admin_new_water_detail_view");
Route::get("admin_new_water_detail_view_edit","MainController@admin_new_water_detail_view_edit");
Route::post('waterdetailedit',"MainController@waterdetailedit");
Route::get('season',"MainController@season");
Route::post('sendseason',"MainController@sendseason");
Route::get("season_view","MainController@season_view");
Route::get('season_view_edit',"MainController@season_view_edit");
Route::post('sendseasonedit',"MainController@sendseasonedit");
Route::get("detail","MainController@detail");
Route::post('fetchcrop', 'MainController@fetchcrop')->name('MainController.fetchcrop');
Route::post('fetchmethod', 'MainController@fetchmethod')->name('MainController.fetchmethod');
Route::post('fetchseason', 'MainController@fetchseason')->name('MainController.fetchseason');
Route::post('fetchwater', 'MainController@fetchwater')->name('MainController.fetchwater');
Route::post('senddetails','MainController@senddetails');
Route::get('detail_view',"MainController@detail_view");
Route::get('detail_view_edit',"MainController@detail_view_edit");
Route::post('updatedetails',"MainController@updatedetails");
Route::get('test',"MainController@test");