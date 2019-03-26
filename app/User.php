<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
      return $this->belongsToMany(Role::class);
    }

    /**
    * @param string|array $roles
    */
    public function authorizeRoles($roles)
    {
      if (is_array($roles)) {
          return $this->hasAnyRole($roles) || 
                 abort(403, 'This action is unauthorized.');
      }
      return $this->hasRole($roles) || 
             abort(403, 'This action is unauthorized.');
    }
    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyRole($roles)
    {
      return null !== $this->roles()->whereIn('name', $roles)->first();
    }
    /**
    * Check one role
    * @param string $role
    */
    public function hasRole($role)
    {
      return null !== $this->roles()->where('name', $role)->first();
    }    


    public function offices()
    {
      return $this->belongsToMany(Office::class);
    }

    /**
    * @param string|array $roles
    */
    public function authorizeOffices($offices)
    {
      if (is_array($offices)) {
          return $this->hasAnyOffice($offices) || 
                 abort(403, 'This action is unauthorized.');
      }
      return $this->hasOffice($offices) || 
             abort(403, 'This action is unauthorized.');
    }
    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyOffice($offices)
    {
      return null !== $this->offices()->whereIn('name', $offices)->first();
    }
    /**
    * Check one role
    * @param string $role
    */
    public function hasOffice($office)
    {
      return null !== $this->offices()->where('name', $office)->first();
    }    

    /**
    * Check one role
    * @param string $role
    */
    public function hasActiveOffice()
    {
        return null !== $this->offices()->where('active', TRUE)->first();
    }    

    /**
    * Check one role
    * @param string $role
    */
    public function hasOffices()
    {
      return null !== $this->offices()->first();
    }

    /**
    * Check one role
    * @param string $role
    */
    public function getActiveOffice()
    {
      return $this->offices()->where('active', TRUE)->first();
    }

}
