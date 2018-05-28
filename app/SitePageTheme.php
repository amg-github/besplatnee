<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SitePageTheme extends Model
{
    protected $fillable = [
    	'font_size',
    	'font_family',
    	'font_color',
    	'background_color',
    ];
}
