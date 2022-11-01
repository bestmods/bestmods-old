<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seed extends Model
{
    protected $connection = 'mysql';
    protected $table = 'seeds';

    protected $primarykey = array('id');
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = array
    (
        'name',
        'protocol', 
        'url',
        'image'
    );

    public function uniqueIds()
    {
        return array('url');
    }

    public function mod()
    {
        return $this->belongsToMany(Mod::class, 'seed');
    }
}
