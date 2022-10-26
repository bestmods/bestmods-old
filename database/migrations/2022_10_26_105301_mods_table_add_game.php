<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mods', function (Blueprint $table) {
            $table->bigInteger('game', false, true);
            $table->bigInteger('rating', false, true);
            $table->bigInteger('total_downloads', false, true);
            $table->bigInteger('total_views', false, true);
            $table->index('game');
        });
    }

    public function down()
    {
    }
};
