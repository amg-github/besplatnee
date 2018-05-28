<?php 

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class GeoObject extends Model {

	protected $fillable = [
		'parent_id',
		'type',
		'active',
		'latitude',
		'longitude',
		'northeast_latitude',
		'northeast_longitude',
		'southwest_latitude',
		'southwest_longitude',
		'properties',
		'name',
        'fullname',
		'alias',
		'genitive_name',
		'genitive_alias',
		'accusative_name',
		'accusative_alias',
		'dative_name',
		'dative_alias',
		'ergative_name',
		'ergative_alias',
		'created_at',
		'updated_at',
	];

	protected $hidden = [];

	protected $cast = [
		'active' => 'boolean',
		'properties' => 'array',
	];

    public $types = [
        'country',
        'region',
        'city',
    ];

    public function adverts() {
        return $this->belongsToMany('App\Models\Advert');
    }

    public function childrens() {
        return $this->hasMany('App\Models\GeoObject', 'parent_id', 'id');
    }

    public function grandparent() {
    	return $this->parent ? $this->parent->parent : null;
    }

    public function parent() {
    	return $this->belongsTo('App\Models\GeoObject', 'parent_id', 'id');
    }

    public function users() {
        return $this->belongsToMany('App\Models\Users');
    }
}