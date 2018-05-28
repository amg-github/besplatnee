<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertMedia extends Model
{
    protected $fillable = [
    	'path', 
    	'name', 
    	'type', 
    ];
}
