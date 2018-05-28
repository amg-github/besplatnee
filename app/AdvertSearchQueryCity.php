<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertSearchQueryCity extends Model
{

	public $timestamps = false;

    protected $fillable = [
    	'advert_query_id', 
        'city_id', 
    ];

    protected $hidden = ['created_at', 'updated_at'];

}
