<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
    	'name',
    	'contacts',
    	'description',
    	'solver',
    	'responsible',
    ];

    protected $hidden = [
    	'user_id',
    	'creator_id',
    	'manager_id',
    	'status',
    ];
}
