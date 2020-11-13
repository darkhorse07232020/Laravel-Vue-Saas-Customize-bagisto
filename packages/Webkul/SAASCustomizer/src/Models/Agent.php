<?php

namespace Webkul\SAASCustomizer\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Webkul\SAASCustomizer\Notifications\SuperAdminResetPassword;
use Webkul\SAASCustomizer\Contracts\Agent as AgentContract;

class Agent extends Authenticatable implements AgentContract
{
    use Notifiable;

    protected $table = 'super_admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'api_token', 'status',
    ];
    // 'role_id', 

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'api_token', 'remember_token',
    ];

    // /**
    //  * Get the role that owns the admin.
    //  */
    // public function role()
    // {
    //     return $this->belongsTo(RoleProxy::modelClass());
    // }

    /**
    * Send the password reset notification.
    *
    * @param  string  $token
    * @return void
    */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SuperAdminResetPassword($token));
    }

    // /**
    //  * Checks if admin has permission to perform certain action.
    //  *
    //  * @param  String  $permission
    //  * @return Boolean
    //  */
    // public function hasPermission($permission)
    // {
    //     if ($this->role->permission_type == 'custom' && ! $this->role->permissions)
    //         return false;

    //     return in_array($permission, $this->role->permissions);
    // }
}