<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;    #PostControlleri käyttöön

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

Route::get('/', function () {   #Etusivu vain jossa valitaan kirjaudu sivu tai rekisteröidy
    return view('welcome');
});

Auth::routes();    #Määrittää reitit käyttäjille ja kirjautuneille käyttäjille
Route::resource('todo', PostController::class);   #Resource controlleri käyttöön jossa on toiminnat ja reitit





