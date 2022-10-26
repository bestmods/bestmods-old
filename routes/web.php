<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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
    $mods = Db::table('mods')->get();

    return view('global', ['page' => 'index', 'mods' => $mods]);
});

Route::get('/view/{mod}', function ($mod) {
    // Assume we're firstly loading based off of custom URL.
    $mod_db = Db::table('mods')->where('custom_url', $mod);

    // If we're invalid, try searching by ID.
    if (!$mod_db)
    {
        $mod_db = Db::table('mods')->find($mod);
    }

    return view('global', ['page' => 'view', 'mod' => $mod_db]);
});