<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use Psr\Http\Message\ServerRequestInterface;

use \Illuminate\Support\HtmlString;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Cache;
use Illuminate\Mail\Markdown;

use App\Models\Seed;
use App\Models\Game;
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

        $json['data'][] = array($mod->id, $img, $mod->name, $mod->description_short, isset($mod->gameReal->name) ? $mod->gameReal->name : '', isset($mod->seedReal->name) ? $mod->seedReal->name : '', $mod->rating, $mod->total_downloads, $mod->total_views, $mod->url, isset($mod->seedReal->url) ? $mod->seedReal->url : '', $mod->custom_url, '', isset($mod->gameReal->image) ? $mod->gameReal->image : '', isset($mod->seedReal->image) ? $mod->seedReal->image : '', isset($mod->seedReal->protocol) ? $mod->seedReal->protocol : 'https', isset($mod->seedReal->classes) ? $mod->seedReal->classes : '', isset($mod->gameReal->classes) ? $mod->gameReal->classes : '');
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

    $icon = null;
    $type = null;
    $id = null;
    $gameReal = null;
    $seedReal = null;
    $name = null;
    $name_short = null;
    $image = null;
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
        $image = 'mods/default.png';

        if (!empty($mod->seed->image))
        {
            // Get filename and extension.
            $parts = explode(".", $mod->seed->image, 2);

            if (is_array($parts) && count($parts) > 1)
            {
                $image = 'seeds/' . $parts[0] . '_full.' . $parts[1];
            }
        }

        if (!empty($mod->image))
        {
            $image = 'mods/' . $mod->image;
        }

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

        $icon = 'bestmods-icon.png';

        $key = 'mod_desc.'.$mod->id;

        $description = Cache::remember($key, 8640, function () use ($mod)  {
            return new HtmlString(Markdown::parse($mod->description));
        });
    
        $key = 'mod_install.'.$mod->id;
    
        $install_help =  Cache::remember($key, 8640, function () use ($mod) {
            return new HtmlString(Markdown::parse($mod->install_help));
        });
    }

    $base_url = Url::to('/view', array('mod' => $mod->custom_url));

    $headinfo = array
    (
        'title' => $mod->name . ' - Best Mods',
        'robots' => 'noindex, nofollow',
        'type' => 'article',
        'image' => Url::to('/images/' . $image),
        'icon' => Url::to('/images' . $icon),
        'description' => $mod->description_short,
        'item1' => $mod->total_views,
        'item2' => $mod->total_downloads,
        'url' => Url::to('/view', array('mod' => $mod->custom_url, 'view' => $view))
    );

    return view('global', ['page' => 'view', 'mod' => $mod, 'view' => $view, 'headinfo' => $headinfo, 'base_url' => $base_url, 'type' => $type,'id' => $id, 'name' => $name, 'name_short' => $name_short, 'image' => $image, 'protocol' => $protocol, 'url' => $url, 'custom_url' => $custom_url, 'description' => $description, 'install_help' => $install_help, 'description_short' => $description_short, 'downloads' => $downloads, 'screenshots' => $screenshots, 'games' => $games, 'seeds' => $seeds, 'gameReal' => $gameReal, 'seedReal' => $seedReal, 'classes' => $classes]);
})->middleware(['auth0.authenticate.optional']);

Route::match(['get', 'post'], '/create/{type?}', function (ServerRequestInterface $request, $type='mod') {
    // Check if we're inserting.
    $post_data = $request->getParsedBody();
    $item_created = false;

    if (isset($post_data['type']))
    {
        $new_type = $post_data['type'];
        $id = isset($post_data['id']) ? intval($post_data['id']) : -1;

        // Handle Mod insert.
        if ($new_type == 'mod')
        {
            
            $seed = isset($post_data['seed']) ? intval($post_data['seed']) : 0;
            $game = isset($post_data['game']) ? intval($post_data['game']) : 0;

            $name = isset($post_data['name']) ? $post_data['name'] : 'Mod Name';
            $url = isset($post_data['url']) ? $post_data['url'] : '';
            $custom_url = isset($post_data['custom_url']) ? $post_data['custom_url'] : '';
            $description = isset($post_data['description']) ? $post_data['description'] : '';
            $description_short = isset($post_data['description_short']) ? $post_data['description_short'] : '';
            $install_help = isset($post_data['install_help']) ? $post_data['install_help'] : '';
            $image = isset($post_data['image']) ? $post_data['image'] : '';

            // Handle downloads (100 is max which should be enough, I hope, lol).
            $downloads = array();

            for ($i = 1; $i <= 100; $i++)
            {
                // If we're not set, #break.
                if (!isset($post_data['download-' . $i . '-name']) || !isset($post_data['download-' . $i . '-url']))
                {
                    break;
                }

                // We must be set, so add onto downloads array.
                $downloads[] = array
                (
                    'name' => $post_data['download-' . $i . '-name'],
                    'url' => $post_data['download-' . $i . '-url'],
                );
            }

             // Handle screenshots (50 is max which should be enough, I hope, lololozlzozlzozlzo).
            $screenshots = array();

            for ($i = 1; $i <= 100; $i++)
            {
                // If we're not set, #break.
                if (!isset($post_data['screenshot-' . $i . '-url']))
                {
                    break;
                }

                // We must be set, so add onto screenshots array.
                $screenshots[] = $post_data['screenshot-' . $i . '-url'];
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
                'image' => $image,

                'downloads' => json_encode($downloads),
                'screenshots' => json_encode($screenshots),

                'rating' => 0,
                'total_downloads' => 0,
                'total_views' => 0
            ];

            // Create or update.
            if ($id < 1)
            {
                Mod::create($info);
                $item_created = true;
            }
            else
            {
                unset($info['rating']);
                unset($info['total_downloads']);
                unset($info['total_views']);

                // Retrieve and update if exists.
                $mod = Mod::where('id', $id)->get()->first();

                if ($mod->exists)
                {
                    $mod->update($info);
                    $item_created = true;
                }
            }
        }
        elseif ($new_type == 'seed')
        {
            $name = isset($post_data['name']) ? $post_data['name'] : 'Seed Name';
            $protocol = isset($post_data['protocol']) ? $post_data['protocol'] : 'https';
            $url = isset($post_data['url']) ? $post_data['url'] : 'moddingcommunity.com';
            $image = isset($post_data['image']) ? $post_data['image'] : '';
            $classes = isset($post_data['classes']) ? $post_data['classes'] : '';

            $info = [
                'name' => $name,
                'protocol' => $protocol,
                'url' => $url,
                'image' => $image,
                'classes' => $classes
            ];

            // Create or update.
            if ($id < 1)
            {
                Seed::create($info);
                $item_created = true;
            }
            else
            {
                // Retrieve and update if exists.
                $seed = Seed::where('id', $id)->get()->first();

                if ($seed->exists)
                {
                    $seed->update($info);
                    $item_created = true;
                }
            }
        }
        else
        {
            $name = isset($post_data['name']) ? $post_data['name'] : 'Game Name';
            $name_short = isset($post_data['name_short']) ? $post_data['name_short'] : 'Game Name Short';
            $image = isset($post_data['image']) ? $post_data['image'] : '';
            $classes = isset($post_data['classes']) ? $post_data['classes'] : '';

            $info = [
                'name' => $name,
                'name_short' => $name_short,
                'image' => $image,
                'classes' => $classes
            ];

            // Create or update.
            if ($id < 1)
            {
                Game::create($info);
                $item_created = true;
            }
            else
            {
                // Retrieve and update if exists.
                $game = Game::where('id', $id)->get()->first();

                if ($game->exists)
                {
                    $game->update($info);
                    $item_created = true;
                }
            }
        }
    }

    $base_url = Url::to('/create');

    $headinfo = array
    (
        'title' => 'Submit - Best Mods',
        'robots' => 'noindex, nofollow',
        'type' => 'article',
        'url' => $base_url . '/' . $type
    );

    $games = Game::all();
    $seeds = Seed::all();

    return view('global', ['page' => 'create', 'headinfo' => $headinfo, 'base_url' => $base_url, 'games' => $games, 'seeds' => $seeds, 'type' => $type, 'item_created' => $item_created]);
})->middleware(['auth0.authenticate:mods:create']);

/* Auth0 (Authentication) */
Route::get('/login', \Auth0\Laravel\Http\Controller\Stateful\Login::class)->name('login');
Route::get('/logout', \Auth0\Laravel\Http\Controller\Stateful\Logout::class)->name('logout');
Route::get('/auth0/callback', \Auth0\Laravel\Http\Controller\Stateful\Callback::class)->name('auth0.callback');