<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        /* Creat BestMods base tables */
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 128);
            $table->string('name_short', 32);
        });

        Schema::create('seeds', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 128);
            $table->string('url', 128);
            $table->string('image', 128);
        });

        Schema::create('mods', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('seed');
            $table->unique('id', 'seed');

            $table->string('name', 128);
            $table->text('description');
            $table->tinyText('description_short');

            $table->string('url', 128);
            $table->string('custom_url', 128);
            $table->string('image', 128);
            $table->text('install_help');

            $table->string('downloads', 2048);
            $table->string('screenshots', 2048);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('games');
        Schema::dropIfExists('seeds');
        Schema::dropIfExists('mods');
    }
};
