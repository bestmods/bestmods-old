<?php

namespace App\Models;

use Auth0\Laravel\Contract\Model\Stateful\User as StatefulUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

use Illuminate\Contracts\Auth\Access\Authorizable;

use Spatie\Permission\Traits\HasRoles;

class User extends \Illuminate\Database\Eloquent\Model implements Authorizable, StatefulUser, AuthenticatableUser
{
    use HasFactory;
    use Notifiable;
    use Authenticatable;
    use HasRoles;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $guard_name = 'auth0';

    protected $hidden = [];
    protected $casts = [];
    protected $fillable = 
    [
        'uid',
        'id',
        'name',
        'email',
    ];

    protected $roles = array();

    public function can($abilities, $arguments = [])
    {
        return true;
    }
}
