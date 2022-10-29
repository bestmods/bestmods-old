<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap as map;
use Spatie\Sitemap\Tags\Url;

class sitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generates a Sitemap.';

    public function handle()
    {
        $sitemap = map::create();
        $sitemap->add((Url::create(config('app.url') . '/')));
        $mods = Db::table('mods')->join('games', 'mods.game', '=', 'games.id')->join('seeds', 'mods.seed', '=', 'seeds.id')->select(array('mods.id', 'games.name AS gname', 'games.name_short AS gname_short', 'mods.name AS name', 'seeds.name AS sname', 'description_short', 'mods.url AS murl', 'seeds.url AS surl', 'custom_url', 'mods.image AS mimage', 'seeds.image AS simage', 'downloads', 'created_at', 'updated_at', 'rating', 'total_downloads', 'total_views', 'games.image AS gimage'))->get();

        $json = array('data' => array());
        
        // We have to format it for DataTables.
        foreach ($mods as $mod)
        {
            $item = 0;

            if (empty($mod->custom_url))
            {
                $item = $mod->id;
            }
            else
            {
                $item = $mod->custom_url;
            }

            $sitemap->add(Url::create(config('app.url') . '/view/' . $item)->setPriority(0.5));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
        
        return 0;
    }
}
