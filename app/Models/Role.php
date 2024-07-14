<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;
    //protected $primaryKey = ['demandeur_id','date_demande'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'description',
    ];

    public function permissions() {

        return $this->belongsToMany(Permissions::class,'roles_permissions');

     }

    public function users() {

        return $this->belongsToMany(\App\User::class,'users_roles');

    }

    public function projet() {

        return $this->belongsToMany(Projet::class,'users_projet');

    }
}
