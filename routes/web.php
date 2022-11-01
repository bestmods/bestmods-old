<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use Psr\Http\Message\ServerRequestInterface;

use \Illuminate\Support\HtmlString;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Cache;
use Illuminate\Mail\Markdown;


use App\Models\Mod;

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

Route::get('/', function (ServerRequestInterface $request) {;
    $img = '/images/bestmods-filled.png';
    $icon = '/images/bestmods-icon.png';

    $base_url = Url::to('/');

    $headinfo = array
    (
        'image' => Url::to($img),
        'icon' => Url::to($icon),
        'url' => $base_url
    );

    return view('global', ['page' => 'index', 'headinfo' => $headinfo, 'base_url' => $base_url]);
})->middleware(['auth0.authenticate.optional']);

Route::get('/retrieve', function(ServerRequestInterface $request) {
    $mods = Mod::with('seedReal')->with('gameReal')->get();
    $json = array('data' => array());
    
    // We have to format it for DataTables.
    foreach ($mods as $mod)
    {
        // Firstly, decide the image.
        $img = 'mods/default.png';

        if (!empty($mod->seedReal->image))
        {
            // Get filename and extension.
            $parts = explode(".", $mod->seedReal->image, 2);

            if (is_array($parts) && count($parts) > 1)
            {
                $img = 'seeds/' . $parts[0] . '_full.' . $parts[1];
            }
        }

        if (!empty($mod->image))
        {
            $img = 'mods/' . $mod->image;
        }

        $json['data'][] = array($mod->id, $img, $mod->name, $mod->description_short, isset($mod->gameReal->name) ? $mod->gameReal->name : '', isset($mod->seedReal->name) ? $mod->seedReal->name : '', $mod->rating, $mod->total_downloads, $mod->total_views, $mod->url, isset($mod->seedReal->url) ? $mod->seedReal->url : '', $mod->custom_url, '', isset($mod->gameReal->image) ? $mod->gameReal->image : '', isset($mod->seedReal->image) ? $mod->seedReal->image : '');
    }

    return json_encode($json);
})->middleware(['auth0.authenticate.optional']);

Route::get('/view/{mod}/{view?}', function (ServerRequestInterface $request, $mod, $view='') {
    $params = $request->getQueryParams();

    if (empty($view))
    {
        $view = (isset($params['view']) && !empty($params['view'])) ? $params['view'] : 
        'overview';
    }

    // Assume we're firstly loading based off of custom URL.
    $mod = Mod::where('custom_url', $mod)->get(Mod::$columns)->first();

    // If we're invalid, try searching by ID.
    $mod = ($mod->exists) ? $mod : Mod::where('id', intval($mod));

    $mod->update(array('total_views' => $mod->total_views + 1));

    // Firstly, decide the image.
    $img = 'mods/default.png';

    if (!empty($mod->seed->image))
    {
        // Get filename and extension.
        $parts = explode(".", $mod->seed->image, 2);

        if (is_array($parts) && count($parts) > 1)
        {
            $img = 'seeds/' . $parts[0] . '_full.' . $parts[1];
        }
    }

    if (!empty($mod->image))
    {
        $img = 'mods/' . $mod->image;
    }

    $icon = 'bestmods-icon.png';

    $headinfo = array
    (
        'title' => $mod->name . ' - Best Mods',
        'robots' => 'noindex, nofollow',
        'type' => 'article',
        'image' => Url::to('/images/' . $img),
        'icon' => Url::to('/images' . $icon),
        'description' => $mod->description_short,
        'item1' => $mod->total_views,
        'item2' => $mod->total_downloads,
        'url' => Url::to('/view', array('mod' => $mod->custom_url, 'view' => $view))
    );

    $key = 'mod_desc.'.$mod->id;

    $desc = Cache::remember($key, 8640, function () use ($mod)  {
        return new HtmlString(Markdown::parse($mod->description));
    });

    $key = 'mod_install.'.$mod->id;

    $install_help =  Cache::remember($key, 8640, function () use ($mod) {
        return new HtmlString(Markdown::parse($mod->install_help));
    });

    // Parse downloads.
    $downloads = json_decode($mod->downloads, true);

    // Loop through each and replace with index.
    if (is_array($downloads))
    {
        $i = 1;

        foreach ($downloads as $download)
        {
            $html = '<a class="modDownload" href="' . $download->url . '" target="_blank">' . $download->name . '</a>';

            // Replace instances in description and install.
            $desc = str_replace('{' + $i + '}',  $html, $desc);
            $install_help = str_replace('{' + $i + '}',  $html, $install_help);
        }
    }

    $base_url = Url::to('/view', array('mod' => $mod->custom_url));

    return view('global', ['page' => 'view', 'mod' => $mod, 'view' => $view, 'headinfo' => $headinfo, 'base_url' => $base_url, 'desc' => $desc, 'install_help' => $install_help, 'downloads' => $downloads]);
})->middleware(['auth0.authenticate.optional']);

Route::get('/create/{type?}', function (ServerRequestInterface $request, $type='mod') {
    $base_url = Url::to('/create', array('type' => $type));

    $headinfo = array
    (
        'title' => 'Submit - Best Mods',
        'robots' => 'noindex, nofollow',
        'type' => 'article',
        'url' => $base_url
    );

    return view('global', ['page' => 'create', 'headinfo' => $headinfo, 'base_url' => $base_url]);
})->middleware(['auth0.authenticate:mods:create']);


/* Auth0 (Authentication) */
Route::get('/login', \Auth0\Laravel\Http\Controller\Stateful\Login::class)->name('login');
Route::get('/logout', \Auth0\Laravel\Http\Controller\Stateful\Logout::class)->name('logout');
Route::get('/auth0/callback', \Auth0\Laravel\Http\Controller\Stateful\Callback::class)->name('auth0.callback');