<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'description',
    ];

    public function roles() {
        return $this->belongsToMany(Role::class,'roles_permissions');
    }

     public function users() {
        return $this->belongsToMany(User::class,'users_permissions');
    }

}
