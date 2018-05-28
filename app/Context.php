<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Context extends Model
{
    protected $fillable = [
    	'user_id',
    	'name',
    	'allias',
    	'type',
    	'description',
    	'active',
    	'address',
    	'logo',
    	'theme_id',
    ];

    protected $hidden = [
    	'blocked',
    	'status',
    ];

    protected $cast = [
    	'active' => 'boolean',
    	'blocked' => 'boolean',
    ];

    public function adverts() {
    	return $this->hasToMany('App\Advert');
    }
}
