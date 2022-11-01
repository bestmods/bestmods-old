<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $connection = 'mysql';
    protected $table = 'games';

    protected $primarykey = array('id');
    public $incrementing = true;
    public $timestamps = false;

    public static $columns = array
    (
        'id',
        'name',
        'name_short',
        'image'
    );

    protected $fillable = array
    (
        'name',
        'name_short', 
        'image'
    );

    public function mod()
    {
        return $this->belongsToMany(Mod::class, 'game');
    }
}
