<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        // Make columns nullable that should be.
        Schema::table('seeds', function (Blueprint $table) {
            $table->string('image', 255)->nullable()->change();
            $table->string('image_banner', 255)->nullable()->change();
            $table->string('classes', 255)->nullable()->change();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->string('image', 255)->nullable()->change();
            $table->string('classes', 255)->nullable()->change();
        });

        Schema::table('mods', function (Blueprint $table) {
            $table->string('custom_url', 128)->nullable()->change();
            $table->string('image', 255)->nullable()->change();

            // We're also changing these to text since a 2048 byte char may not be enough.
            $table->text('downloads')->nullable()->change();
            $table->text('screenshots')->nullable()->change();
        });
    }

    public function down()
    {
    }
};
