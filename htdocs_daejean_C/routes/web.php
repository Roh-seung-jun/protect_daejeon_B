<?php

use App\Garden;
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
//    $file = json_decode(file_get_contents('./resources/js/garden_data.json'));
//    foreach ($file->garden_data as $item){
//        $input = [];
//        $input['image'] = $item->image;
//        $input['name'] = $item->name;
//        $input['phone'] = $item->phone;
//        $input['address'] = $item->address;
//        $input['introduce'] = $item->introduce;
//        $input['open'] = explode(' ~ ',$item->{'open-close'})[0];
//        $input['close'] = explode(' ~ ',$item->{'open-close'})[1];
//        $input['institution'] = $item->institution;
//        $input['limit'] = $item->limit;
//        Garden::create($input);
//    }
    return view('index');
});

Route::get('/user_login','UserController@loginPage')->name('login');
Route::get('/user_sign','UserController@signPage')->name('sign');
Route::get('/visit','UserController@visitPage')->name('visit');
Route::get('/view/{id}','UserController@viewPage')->name('view');
Route::get('/logout','UserController@logout')->name('logout');
Route::get('/list_check','AdminController@list_check')->name('list_check');
Route::get('/apply','AdminController@apply')->name('apply');
Route::get('/accept/{id}','AdminController@accept')->name('accept');
Route::get('/cancel/{id}','UserController@cancel')->name('cancel');
Route::get('/management','AdminController@management')->name('management');

Route::post('/applyAction','AdminController@applyAction')->name('applyAction');
Route::post('/getCalendar','AdminController@getCalendar')->name('getCalendar');
Route::post('/calender','UserController@calender')->name('calender');
Route::post('/makeCalender','UserController@makeCalender')->name('makeCalender');
Route::post('/viewDate','UserController@viewDate')->name('viewDate');
Route::post('/user_login','UserController@loginAction')->name('user_login');
Route::post('/user_sign','UserController@signAction')->name('user_sign');
