<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
    	'name',
    	'code',
    	'sign',
    	'is_international',
    ];

    protected $hidden = [];

    protected $cast = [
    	'is_international' => 'boolean',
    ];
}
