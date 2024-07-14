<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectsDocuments extends Model
{
    protected $table = 'projects_documents'; //si on veut changer le nom de la table
    public $timestamps = false;
    public $incrementing = false; //pour les cle primaires multiples

    // protected $fillable = [
    // ];
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

}
