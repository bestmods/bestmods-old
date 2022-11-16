<?php

use Illuminate\Support\Facades\Route;

use Psr\Http\Message\ServerRequestInterface;

use \Illuminate\Support\HtmlString;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Cache;
use Illuminate\Mail\Markdown;

use App\Models\Seed;
use App\Models\Game;
use App\Models\Mod;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

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
        $img = asset('images/default_mod.png');

        if (!empty($mod->seedReal->image_banner))
        {
            $img = asset('storage/images/seeds/' . $mod->seedReal->image_banner);
        }

        if (!empty($mod->image))
        {
            $img = asset('storage/images/mods/' . $mod->image);
        }

        $json['data'][] = array($mod->id, $img, $mod->name, $mod->description_short, isset($mod->gameReal->name) ? $mod->gameReal->name : '', isset($mod->seedReal->name) ? $mod->seedReal->name : '', $mod->rating, $mod->total_downloads, $mod->total_views, $mod->url, isset($mod->seedReal->url) ? $mod->seedReal->url : '', $mod->custom_url, '', isset($mod->gameReal->image) ? asset('storage/images/games/' . $mod->gameReal->image) : '', isset($mod->seedReal->image) ? asset('storage/images/seeds/' . $mod->seedReal->image) : '', isset($mod->seedReal->protocol) ? $mod->seedReal->protocol : 'https', isset($mod->seedReal->classes) ? $mod->seedReal->classes : '', isset($mod->gameReal->classes) ? $mod->gameReal->classes : '');
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
    $mod = Mod::with('seedReal')->with('gameReal')->where('custom_url', $mod)->get()->first();

    // If we're invalid, try searching by ID.
    $mod = ($mod->exists) ? $mod : Mod::with('seedReal')->with('gameReal')->where('id', intval($mod));

    $type = null;
    $id = null;
    $gameReal = null;
    $seedReal = null;
    $image = null;
    $icon = null;
    $name = null;
    $name_short = null;
    $protocol = null;
    $url = null;
    $custom_url = null;
    $description = null;
    $description_short = null;
    $install_help = null;
    $downloads = null;
    $screenshots = null;
    $games = null;
    $seeds = null;
    $classes = null;

    // If we're in edit mode, fill out needed variables.
    if ($view == 'edit')
    {
        $type = 'mod';
        $id = $mod->id;
        $name = $mod->name;
        $image = $mod->image;
        $protocol = $mod->protocol;
        $url = $mod->url;
        $custom_url = $mod->custom_url;
        $description = $mod->description;
        $description_short = $mod->description_short;
        $install_help = $mod->install_help;
        

        $downloads = array();
        $screenshots = array();

        $gameReal = $mod->gameReal;
        $seedReal = $mod->seedReal;

        $classes = $seedReal->classes;

        $games = Game::all();
        $seeds = Seed::all();
        
        $dls = json_decode($mod->downloads, true);

        if ($dls && is_array($dls))
        {
            foreach ($dls as $dl)
            {
                $downloads[] = array('name' => $dl['name'], 'url' => $dl['url']);
            }
        }

        $SSs = json_decode($mod->screenshots);

        if ($SSs && is_array($SSs))
        {
            foreach ($SSs as $ss)
            {
                $screenshots[] = $ss;
            }
        }
    }
    else
    {
        // Increment view count.
        $mod->update(array('total_views' => $mod->total_views + 1));

        // Firstly, decide the image.
        $image = asset('images/default_mod.png');

        if (!empty($mod->seedReal->image_banner))
        {
            $image = asset('storage/images/seeds/' . $mod->seedReal->image_banner);
        }

        if (!empty($mod->image))
        {
            $image = asset('storage/images/mods/' . $mod->image);
        }

        $icon = 'bestmods-icon.png';

        $key = 'mod_desc.'.$mod->id;

        $description = Cache::remember($key, 8640, function () use ($mod)  {
            return $mod->description;
        });
    
        $key = 'mod_install.'.$mod->id;
    
        $install_help =  Cache::remember($key, 8640, function () use ($mod) {
            return $mod->install_help;
        });

        // Parse screenshots.
        $screenshots = json_decode($mod->screenshots, true);
        
        // Loop through each and replace with index.
        if (is_array($screenshots))
        {
            $i = 1;
    
            foreach ($screenshots as $screenshot)
            {
                $html = '<img class="modScreenshot" src="' . $screenshot . '" alt="screenshot" />';
    
                // Replace instances in description and install.
                $description = str_replace('{' . $i . '}',  $html, $description);
                $install_help = str_replace('{' . $i . '}',  $html, $install_help);
            }
        }   
    
        // Parse downloads.
        $downloads = json_decode($mod->downloads, true);
   
        // Loop through each and replace with index.
        if (is_array($downloads))
        {
           $i = 1;
   
           foreach ($downloads as $download)
           {
               $html = '<a class="modDownload" href="' . $download['url'] . '" target="_blank">' . $download['name'] . '</a>';
   
               // Replace instances in description and install.
               $description = str_replace('{' . $i . '}',  $html, $description);
               $install_help = str_replace('{' . $i . '}',  $html, $install_help);
           }
        }

        $description = new HtmlString(Markdown::parse($description));
        $install_help = new HtmlString(Markdown::parse($install_help));
    }

    $base_url = Url::to('/view', array('mod' => $mod->custom_url));

    $headinfo = array
    (
        'title' => $mod->name . ' - Best Mods',
        'robots' => 'index, nofollow',
        'type' => 'article',
        'image' => $image,
        'icon' => Url::to('/images' . $icon),
        'description' => $mod->description_short,
        'item1' => $mod->total_views,
        'item2' => $mod->total_downloads,
        'url' => ($view == 'overview') ? $base_url : Url::to('/view', array('mod' => $mod->custom_url, 'view' => $view))
    );

    return view('global', ['page' => 'view', 'mod' => $mod, 'view' => $view, 'headinfo' => $headinfo, 'base_url' => $base_url, 'type' => $type,'id' => $id, 'name' => $name, 'name_short' => $name_short, 'image' => $image, 'protocol' => $protocol, 'url' => $url, 'custom_url' => $custom_url, 'description' => $description, 'install_help' => $install_help, 'description_short' => $description_short, 'downloads' => $downloads, 'screenshots' => $screenshots, 'games' => $games, 'seeds' => $seeds, 'classes' => $classes]);
})->middleware(['auth0.authenticate.optional']);

Route::match(['get', 'post'], '/create/{type?}', function (Request $request, $type='mod') {
    $auth0user = Auth::user();
    $db_user = User::find($auth0user->getAttribute('id'));

    // We'll use @can in the template in the future so it isn't just a blank page.
    if (!$db_user || !$db_user->hasRole('Admin'))
    {
        return 'NO PERMISSION';
    }

    $item_created = false;

    $new_type = $request->get('type', null);

    if ($new_type)
    {
        $id = $request->get('id', -1);

        $image = $request->file('image');
        $image_remove = ($request->get('image-remove', '') == 'on') ? true : false;

        // Handle Mod insert.
        if ($new_type == 'mod')
        {
            $seed = $request->get('seed', 0);
            $game = $request->get('game', 0);

            $name = $request->get('name', '');
            $url = $request->get('url', '');
            $custom_url = $request->get('custom_url', '');
            $description = $request->get('description', '');
            $description_short = $request->get('description_short', '');
            $install_help = $request->get('install_help', '');

            // Handle downloads (100 is max which should be enough, I hope, lol).
            $downloads = array();

            for ($i = 1; $i <= 100; $i++)
            {
                $data = $request->get('download-' . $i . '-name');

                // If we're not set, #break.
                if (!$data)
                {
                    break;
                }

                // We must be set, so add onto downloads array.
                $downloads[] = array
                (
                    'name' => $request->get('download-' . $i . '-name', 'Download'),
                    'url' => $request->get('download-' . $i . '-url', null),
                );
            }

             // Handle screenshots (50 is max which should be enough, I hope, lololozlzozlzozlzo).
            $screenshots = array();

            for ($i = 1; $i <= 100; $i++)
            {
                $data = $request->get('screenshot-' . $i . '-url', null);

                // If we're not set, #break.
                if (!$data)
                {
                    break;
                }

                // We must be set, so add onto screenshots array.
                $screenshots[] = $data;
            }

            $info = [
                'seed' => $seed,
                'game' => $game,

                'name' => $name,
                'url' => $url,
                'custom_url' => $custom_url,
                'description' => $description,
                'description_short' => $description_short,
                'install_help' => $install_help,

                'image' => '',

                'downloads' => json_encode($downloads),
                'screenshots' => json_encode($screenshots),

                'rating' => 0,
                'total_downloads' => 0,
                'total_views' => 0
            ];
            
            $mod = null;

            // Create or update.
            if ($id < 1)
            {
                $mod = Mod::create($info);
                $item_created = true;
            }
            else
            {
                unset($info['rating']);
                unset($info['total_downloads']);
                unset($info['total_views']);

                if (!$image_remove)
                {
                    unset($info['image']);
                }

                // Retrieve and update if exists.
                $mod = Mod::where('id', $id)->get()->first();

                if ($mod->exists)
                {
                    $mod->update($info);
                    $item_created = true;
                }
            }

            if ($image != null)
            {
                $ext = $image->clientExtension();

                $imgName = $mod->id . '.' . $ext;
                $mod->image = $imgName;
                
                $image->storePubliclyAs('images/mods', $imgName, 'public');
                
                $mod->save();
            }
        }
        elseif ($new_type == 'seed')
        {
            $name = $request->get('name', 'Seed');
            $protocol = $request->get('protocol', 'https');
            $url = $request->get('url', 'moddingcommunity.com');
            $classes = $request->get('classes', '');

            $image_banner = $request->file('image_banner');
            $image_banner_remove = ($request->get('image_banner-remove', '') == 'on') ? true : false;

            $info = [
                'name' => $name,
                'protocol' => $protocol,
                'url' => $url,
                'image' => '',
                'image_banner' => '',
                'classes' => ($classes) ? $classes : ''
            ];

            $seed = null;

            // Create or update.
            if ($id < 1)
            {
                $addInfo = $info;
                array_splice($addInfo, 2, 1);

                if (!$image_remove)
                {
                    unset($addInfo['image']);
                }

                if (!$image_banner_remove)
                {
                    unset($addInfo['image_banner']);
                }

                $seed = Seed::updateOrCreate(['url' => $url], $addInfo);
                $item_created = true;
            }
            else
            {
                // Retrieve and update if exists.
                $seed = Seed::find($id);

                if (!$image_remove)
                {
                    unset($info['image']);
                }

                if (!$image_banner_remove)
                {
                    unset($info['image_banner']);
                }

                if ($seed->exists)
                {
                    $seed->update($info);
                    $item_created = true;
                }
            }

            if ($image != null)
            {
                $ext = $image->clientExtension();

                $imgName = strtolower($seed->url) . '.' . $ext;
                $seed->image = $imgName;
                
                $image->storePubliclyAs('images/seeds', $imgName, 'public');
                
                $seed->save();
            }

            if ($image_banner != null)
            {
                $ext = $image_banner->clientExtension();

                $imgName = strtolower($seed->url) . '_full.' . $ext;
                $seed->image_banner = $imgName;
                
                $image_banner->storePubliclyAs('images/seeds', $imgName, 'public');

                $seed->save();
            }
        }
        else
        {
            $name = $request->get('name', 'Game');
            $name_short = $request->get('name_short', 'Game Short');
            $classes = $request->get('classes', '');

            $info = [
                'name' => $name,
                'name_short' => $name_short,
                'image' => '',
                'classes' => ($classes) ? $classes : ''
            ];

            $game = null;

            // Create or update.
            if ($id < 1)
            {
                $addInfo = $info;
                array_splice($addInfo, 1, 1);

                if (!$image_remove)
                {
                    unset($addInfo['image']);
                }

                $game = Game::updateOrCreate(['name_short' => $name_short], $addInfo);
                $item_created = true;
            }
            else
            {
                // Retrieve and update if exists.
                $game = Game::where('id', $id)->get()->first();

                if (!$image_remove)
                {
                    unset($info['image']);
                }

                if ($game->exists)
                {
                    $game->update($info);
                    $item_created = true;
                }
            }

            if ($image != null)
            {
                $ext = $image->clientExtension();

                $imgName = strtolower($game->name_short) . '.' . $ext;
                $game->image = $imgName;
                
                $image->storePubliclyAs('images/games', $imgName, 'public');
                
                $game->save();
            }
        }
    }

    $base_url = Url::to('/create');

    $headinfo = array
    (
        'title' => 'Submit - Best Mods',
        'robots' => 'index, nofollow',
        'type' => 'article',
        'url' => $base_url . '/' . $type
    );

    $games = Game::all();
    $seeds = Seed::all();

    return view('global', ['page' => 'create', 'headinfo' => $headinfo, 'base_url' => $base_url, 'games' => $games, 'seeds' => $seeds, 'type' => $type, 'item_created' => $item_created]);
})->middleware(['auth0.authenticate']);

/* Auth0 (Authentication) */
Route::get('/login', \Auth0\Laravel\Http\Controller\Stateful\Login::class)->name('login');
Route::get('/auth0/callback', \Auth0\Laravel\Http\Controller\Stateful\Callback::class)->name('auth0.callback');
Route::get('/logout', \Auth0\Laravel\Http\Controller\Stateful\Logout::class)->name('logout');