<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    protected $table = 'projects'; //si on veut changer le nom de la table
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = true; //pour les cle primaires multiples

    protected $fillable = [
        'name',
        'desc',
        'path',
        'front_page',
    ];
    
    public function projectDocuments()
    {
        return $this->hasMany(ProjectsDocuments::class, 'id_project', 'id');
    }

    public function documents()
    {
        return $this->hasManyThrough(Documents::class, ProjectsDocuments::class, 'id_project', 'id', 'id', 'id_document');
    }
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

}