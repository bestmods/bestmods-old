<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use Psr\Http\Message\ServerRequestInterface;

use \Illuminate\Support\HtmlString;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Cache;
use Illuminate\Mail\Markdown;

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

Route::get('/', function (ServerRequestInterface $request) {
    $img = '/images/bestmods-filled.png';
    $icon = '/images/bestmods-icon.png';

    $headinfo = array
    (
        'image' => Url::to($img),
        'icon' => Url::to($icon),
        'url' => Url::to('/')
    );

    return view('global', ['page' => 'index', 'headinfo' => $headinfo]);
});

Route::get('/retrieve', function(ServerRequestInterface $request) {
    $mods = Db::table('mods')->join('games', 'mods.game', '=', 'games.id')->join('seeds', 'mods.seed', '=', 'seeds.id')->select(array('mods.id', 'games.name AS gname', 'games.name_short AS gname_short', 'mods.name AS name', 'seeds.name AS sname', 'description_short', 'mods.url AS murl', 'seeds.url AS surl', 'custom_url', 'mods.image AS mimage', 'seeds.image AS simage', 'downloads', 'created_at', 'updated_at', 'rating', 'total_downloads', 'total_views', 'games.image AS gimage'))->get();

    $json = array('data' => array());
    
    // We have to format it for DataTables.
    foreach ($mods as $mod)
    {
        $json['data'][] = array($mod->id, $mod->mimage, $mod->name, $mod->description_short, $mod->gname, $mod->sname, $mod->rating, $mod->total_downloads, $mod->total_views, $mod->murl, $mod->surl, $mod->custom_url, '', $mod->gimage, $mod->simage);
    }

    return json_encode($json);
});

Route::get('/view/{mod}', function (ServerRequestInterface $request, $mod) {
    $params = $request->getQueryParams();

    $view = (isset($params['view']) && !empty($params['view'])) ? $params['view'] : 'overview';

    // Assume we're firstly loading based off of custom URL.
    $mod_db = Db::table('mods')->where('custom_url', $mod);

    // If we're invalid, try searching by ID.
    $mod_db = ($mod_db->count() < 1) ? Db::table('mods')->where('mods.id', intval($mod)) : $mod_db;
    
    $mod_db = ($mod_db->count() > 0) ? $mod_db->join('games', 'mods.game', '=', 'games.id')->join('seeds', 'mods.seed', '=', 'seeds.id')->paginate(1, array('mods.id', 'games.name AS gname', 'mods.name AS name', 'seed', 'description', 'description_short', 'mods.url AS murl', 'seeds.url AS surl', 'custom_url', 'mods.image AS mimage', 'seeds.image AS simage', 'install_help', 'downloads', 'screenshots', 'created_at', 'updated_at', 'rating', 'total_downloads', 'total_views', 'seeds.name AS sname'))->first() : NULL;

    Db::table('mods')->where('id', $mod_db->id)->update(array('total_views' => $mod_db->total_views + 1));

    $imgUrl = empty($mod_db->mimage) ? "default.png" : $mod_db->mimage;
    $img = '/images/mods/' . $imgUrl;

    $icon = '/images/bestmods-icon.png';

    $headinfo = array
    (
        'title' => $mod_db->name . ' - Best Mods',
        'robots' => 'noindex, nofollow',
        'type' => 'article',
        'image' => Url::to($img),
        'icon' => Url::to($icon),
        'description' => $mod_db->description_short,
        'item1' => $mod_db->total_views,
        'item2' => $mod_db->total_downloads,
        'url' => Url::to('/view', array('mod' => $mod_db->custom_url))
    );

    $key = 'mod_desc.'.$mod_db->id;

    $mod_db->description = Cache::remember($key, 8640, function () use ($mod_db)  {
        return new HtmlString(Markdown::parse($mod_db->description));
    });

    $key = 'mod_install.'.$mod_db->id;

    $mod_db->install_help =  Cache::remember($key, 8640, function () use ($mod_db) {
        return new HtmlString(Markdown::parse($mod_db->install_help));
    });

    return view('global', ['page' => 'view', 'mod' => $mod_db, 'view' => $view, 'headinfo' => $headinfo]);
});