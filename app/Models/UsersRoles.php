<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersRoles extends Model
{
    protected $table = 'users_roles'; //si on veut changer le nom de la table
    protected $primaryKey = ['user_id','role_id'];
    public $timestamps = false;
    public $incrementing = false; //pour les cle primaires multiples
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'role_id',
    ];

}
