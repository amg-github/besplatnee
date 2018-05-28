<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
    	'code',
    	'active',
    	'title',
    ];

    protected $cast = [
    	'active' => 'boolean',
    ];
}
