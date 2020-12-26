<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['middleware'=>'auth'], function(){

	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/billings', 'BillingController@list')->name('billings');
	Route::get('/billpaidoff', 'BillingController@lunas')->name('billpaidoff');
	Route::get('/billnoactive', 'BillingController@noactive')->name('billnoactive');
	Route::get('/import', 'BillingController@import')->name('import');
	Route::get('/create', 'BillingController@create')->name('create');
	Route::get('/billings/edit/{id}', 'BillingController@edit')->name('edit');
	Route::get('/billings/delete/{id}', 'BillingController@delete')->name('billdel');
	Route::get('/billings/restore/{id}', 'BillingController@restore')->name('billrestore');
	Route::post('/billings/update', 'BillingController@update')->name('billupdate');
	Route::post('/billing/save', 'BillingController@save')->name('save');
	Route::get('/delete', 'BillingController@delete')->name('delete');
	Route::get('/transaction', 'TransactionController@list')->name('transaction');
	Route::get('/transactions/delete/{id}', 'TransactionController@delete')->name('transdel');
	Route::post('/alltrans', 'TransactionController@alltrans')->name('alltrans');
	Route::post('/allcancel', 'TransactionController@allCancel')->name('allCancel');
	Route::post('/lunasall', 'BillingController@lunasall')->name('lunasall');
	Route::post('/notactiveall', 'BillingController@notactiveall')->name('notactiveall');
	Route::post('/billall', 'BillingController@billAll')->name('billAll');
	Route::get('/canceled', 'TransactionController@cancel')->name('canceled');
});
