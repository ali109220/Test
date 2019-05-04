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


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/control_panel','ControlPanelController@index');
Route::get('/allCities','CityController@all');
Route::get('/allAreas','AreaController@all');
Route::post('/city/index','CityController@index');
Route::post('/city','CityController@store');
Route::post('/area/index','AreaController@index');
Route::post('/area','AreaController@store');
//Specialty
Route::post('/specialty/index','SpecialtyController@getAllWithFilters');
Route::resource('specialty','SpecialtyController');
Route::get('/allSpecialties','SpecialtyController@all');
//SubSpecialty
Route::post('/sub_specialty/index','SubspecialtyController@getAllWithFilters');
Route::resource('sub_specialty','SubspecialtyController');
Route::get('/allSubSpecialties','SubspecialtyController@all');
//Doctor
Route::post('/doctor/index','DoctorController@getAllWithFilters');
Route::resource('doctor','DoctorController');
Route::get('/allDoctors','DoctorController@all');
Route::get('/allTitles','DoctorController@allTitles');
Route::get('/allGenders','DoctorController@allGenders');
Route::post('/addInsurance','DoctorController@addInsurance');

//Insurance
Route::post('/insurance/index','InsuranceController@getAllWithFilters');
Route::resource('insurance','InsuranceController');
Route::get('/allInsurances','InsuranceController@all');
Route::get('/allInsurancesWithoutAdded/{id}','InsuranceController@allInsurancesWithoutAdded');
//

