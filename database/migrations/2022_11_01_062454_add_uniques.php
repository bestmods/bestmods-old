<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seeds', function (Blueprint $table) {
            $table->unique('url');
        });

        Schema::table('games', function (Blueprint $table) {
            $table->unique('name_short');
        });
    }

    public function down()
    {
    }
};
