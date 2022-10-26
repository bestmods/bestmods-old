<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class sitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generates a Sitemap.';

    public function handle()
    {
        SitemapGenerator::create('https://bestmods.io/')->writeToFile(public_path('sitemap.xml'));

        return 0;
    }
}
