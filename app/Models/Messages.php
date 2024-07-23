<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $table = 'messages'; //si on veut changer le nom de la table
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true; //pour les cle primaires multiples

    protected $fillable = [
        'name',
        'firstname',
        'sujet',
        'email',
        'message',
        'date',
    ];
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

}