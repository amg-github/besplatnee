<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $fillable = [
    	'property_id',
    	'heading_id',
    	'alias',
    	'sortindex',
    	'caption',
    	'direction',
    ];

    protected $hidden = [

    ];

    public function heading() {
    	return $this->belongsTo('App\Heading');
    }

    public function property() {
    	return $this->belongsTo('App\Property');
    }
}
