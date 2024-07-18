<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Codes extends Model
{
    protected $table = 'codes'; //si on veut changer le nom de la table
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true; //pour les cle primaires multiples

    protected $fillable = [
        'name',
        'desc',
        'front_page',
        'document_id',
    ];
    
    public function codeDocuments()
    {
        return $this->hasMany(ProjectsDocuments::class, 'id_code', 'id');
    }

    public function documents()
    {
        return $this->hasManyThrough(Documents::class, CodesDocuments::class, 'id_code', 'id', 'id', 'id_document');
    }


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
