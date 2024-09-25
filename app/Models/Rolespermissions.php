<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rolespermissions extends Model
{
    protected $table = 'roles_permissions'; //si on veut changer le nom de la table
    protected $primaryKey = ['roles_id','permissions_id'];
    public $incrementing = false; //pour les cle primaires multiples
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'roles_id',
        'permissions_id',        
    ];
   
}
