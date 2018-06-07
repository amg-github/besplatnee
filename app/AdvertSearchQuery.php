<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertSearchQuery extends Model
{

    protected $fillable = [
    	'query', 
        'alias', 
        'city', 
        'follows', 
        'city_name', 
        'doubled',
        'active', 
        'city_alias', 
        'approved',
    ];

    protected $cast = [
        'active'    => 'boolean',
        'doubled'   => 'boolean',
    ];

    public function cities() {
    	return $this->where('alias', $this->alias)->pluck('city_name')->all();
    }

    public function follows_count() {
    	return $this->where('alias', $this->alias)->sum('follows');
    }

    public function getLink() {
    	return '//'.(\Config::get('area') ? \Config::get('area')->alias.'.' : '').env('APP_DOMAIN').'/поиск-'.$this->alias.'-в-городе-'.(\Config::get('area') ? \Config::get('area')->name : 'мире').'-poisk-'.str_replace('-', '_', app('slug')->make($this->alias)).'-v-gorode-'.app('slug')->make((\Config::get('area') ? \Config::get('area')->name : 'мире'));
    }

    public function citiesNew() {
        return $this->belongsToMany('App\GeoObject', 'advert_search_query_cities', 'advert_query_id', 'city_id', 'id','id');
    }

    public function queryCity() {
        return $this->hasMany('App\AdvertSearchQueryCity', 'advert_query_id');
    }

    public function searchResults() {
        $result = new \App\AdvertSearchQueryResult;

        return $result->where('search_id', $this->id)->where('city_id', \Config::get('area')->id)->first();
    }
}
