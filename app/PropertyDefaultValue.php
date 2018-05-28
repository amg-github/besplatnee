<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyDefaultValue extends Model
{
    protected $fillable = [
    	'property_id',
    	'parent_value',
    	'title',
    	'value',
    ];
}
