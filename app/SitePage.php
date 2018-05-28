<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    protected $fillable = [
    	'name',
    	'content',
    	'allias',
    	'menuindex',
    	'theme_id',
    ];

    protected $hidden = [
    	'site_id',
    ];
}
