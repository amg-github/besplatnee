<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 
        'lastname', 
        'patronymic', 
        'phone',
        'email', 
        'photo', 
        'gender',
        'duplicate_in_all_cities',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token', 
        'active', 
        'blocked', 
        'created_at', 
        'updated_at',
        'admin_cities',
    ];

    protected $casts = [
        'active' => 'boolean',
        'blocked' => 'boolean',
        'admin_cities' => 'array',
        'duplicate_in_all_cities' => 'boolean',
    ];

    public $genders = [
        'undefined',
        'male',
        'female',
    ];

    /**
     * Связи
     */

    /**
     * Принадлежность к группам пользователей
     */
    public function groups() {
        return $this->belongsToMany('App\Models\Group');
    }
    /**
     * Доступные города
     */
    public function geoObjects() {
        return $this->belongsToMany('App\Models\GeoObject');
    }
    /**
     * Телефон
     */
    public function phone() {
        return $this->hasOne('App\Models\Phone');
    }
    /**
     * Проверка прав
     */
    public function checkPolicy($policy) {
        foreach($this->groups as $group) {
            if($group->checkPolicy($policy)) {
                return true;
            }
        }

        return false;
    }
}
