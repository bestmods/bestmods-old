<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mod extends Model
{
    const CREATED_AT = 'creation_at';
    const UPDATED_AT = 'updated_at';

    protected $connection = 'mysql';
    protected $table = 'mods';

    protected $primarykey = array('id');
    public $incrementing = true;
    public $timestamps = true;

    public static $columns = array
    (
        'id',
        'name',
        'description', 
        'description_short',
        'install_help',
        'url',
        'custom_url',
        'image',
        'downloads',
        'screenshots',
        'created_at',
        'updated_at',
        'rating',
        'total_views',
        'total_downloads'
    );

    protected $fillable = array
    (
        'name',
        'description', 
        'description_short',
        'install_help',
        'url',
        'custom_url',
        'image',
        'downloads',
        'screenshots',
        'rating',
        'total_views',
        'total_downloads'
    );

    public function seedReal()
    {
        return $this->hasOne(Seed::class, 'id', 'seed');
    }

    public function gameReal()
    {
        return $this->hasOne(Game::class, 'id', 'game');
    }

    public function uniqueIds()
    {
        return array(array('seed', 'url'), 'custom_url');
    }

    public function scopeGeneral($query)
    {
        return $query->select($this->columns);
    }

    public function scopeExclude($query, $columns)
    {
        return $query->select(array_diff($this->columns, $columns));
    }
}