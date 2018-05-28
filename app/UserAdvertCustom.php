<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAdvertCustom extends Model
{
    protected $fillable = [
    	'hidden',
    	'favorite',
    	'comment',
    	'sort',
    ];

    protected $hidden = [
    	'advert_id',
    	'user_id',
    ];

    protected $cast = [
    	'hidden' => 'boolean',
    	'favorite' => 'boolean',
    ];
}
