<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //  //https://laravel.com/docs/7.x/upgrade#date-serialization
    //  protected function serializeDate(DateTimeInterface $date)
    //  {
    //      return $date->format('d-m-Y H:i:s');
    //  }

     /**
      * le role_id appartient à la table roles
      * permet de faire Auth::user()->roles()->pluck('nom')->first() pour obtenir le nom du rôle du user
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     public function roles() {
         return $this->belongsToMany(Role::class,'users_roles');
     }

     /**
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function users_entites() {
         return $this->belongsTo(UsersEntites::class,'id','user_id');
     }

     /**
      * @return bool
     */
     public function canImpersonate()
     {
         $admin_roles = ['administrateur', 'salarie'];
         if(in_array($this->roles()->value('nom'), $admin_roles)) {
             return true;
         }
     }

     /**
      * @return bool
     */
     public function canBeImpersonated()
     {
         return true;
     }

     public function isImpersonating(): bool
     {

         return session()->has($this->getSessionKey());
     }

     /**
      * Check if the current user is impersonated.
      *
      * @param   void
      * @return  bool
     */
     public function isImpersonated()
     {
         //on force la session id_ent_for sur le user impersonated
         if(app(ImpersonateManager::class)->isImpersonating()) {
             $admin_roles = ['administrateur', 'salarie'];
             if(in_array($this->roles()->value('nom'), $admin_roles)) {
                 session()->put('id_ent_for', collect(array(497)));
                 session()->save();
                 return true;
             }
             session(['id_ent_for' => $this->users_entites()->get()->pluck('id_ent_for')]);
             return true;
         }

     }
}
