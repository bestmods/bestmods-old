<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mods', function (Blueprint $table) {
            $table->dropUnique('seed');
            $table->dropUnique('url');
            $table->unique(array('seed', 'url'));
        });

    }

    public function down()
    {
    }
};
