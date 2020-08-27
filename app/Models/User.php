<?php

namespace App\Models;

use Storage;
use App\Traits\AvatarTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, AvatarTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role','company_id','email_verified_at','company_role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // User is employer
    public function is_company(){
        return $this->role == 'company';
    }

    // User is company admin
    public function is_company_admin(){
        return $this->company_role == 'admin';
    }
    
    // Company
    public function company()
    {
        return $this->belongsTo('App\Models\Companies', 'user_id');
    }
}
