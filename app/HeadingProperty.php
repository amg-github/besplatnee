<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeadingProperty extends Model
{
    protected $table = 'heading_property';

    protected $fillname = [
    	'heading_id',
    	'property_id',
    	'sort',
    ];
}
