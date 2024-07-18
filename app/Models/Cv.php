<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    protected $table = 'cv'; //si on veut changer le nom de la table
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true; //pour les cle primaires multiples

    protected $fillable = [
        'name',
        'desc',
        'front_page',
    ];
    
    public function file()
    {
        return $this->belongsTo(Documents::class, 'document_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

}