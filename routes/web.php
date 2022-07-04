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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    return 'linkstorage';
})->name('tool.linkstorage');

Route::get('/clear', function () {
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('debugbar:clear');
    return 'clear';
})->name('tool.clear');

Route::get('/cache', function () {
    Artisan::call('config:cache');
    Artisan::call('view:cache');
    return 'cache';
})->name('tool.cache');

Route::get('/flushall', function () {
    \Redis::command('flushall');
    return 'flushall';
})->name('tool.flushall');


include('client-routes.php');

include('admin-routes.php');
