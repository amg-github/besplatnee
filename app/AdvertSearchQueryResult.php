<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertSearchQueryResult extends Model
{

    protected $fillable = [
    	'search_id', 'count', 'adverts', 'city_id'
    ];

    protected $primaryKey = ['search_id','city_id'];

    public $incrementing = false;

    // public function query() {
    //     return $this->hasOne('App\AdvertSearchQuery');
    // }

}
