<?php 

namespace App;

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

	protected $hidden = [

	];

	protected $cast = [
		'active' => 'boolean',
		'properties' => 'array',
	];

    public function makeChain() {
        $parent = $this->parent_id;
        $chain = [];

        if (!is_null($parent)) {
            $key = 0;
            $chain[$key] = $this->parent;
            $parent = $chain[$key]->parent_id;

            while (!is_null($parent)) {
                ++$key;
                $chain[$key] = $chain[$key-1]->parent;
                $parent = $chain[$key]->parent_id;
            } 
        }

        return $chain = collect(array_reverse($chain));
    }

    public function parent() {
    	return $this->belongsTo('App\GeoObject', 'parent_id', 'id');
    }

    public function childrens() {
    	return $this->hasMany('App\GeoObject', 'parent_id', 'id');
    }

    public function getFullName() {
    	return $this->fullname ? $this->fullname : $this->generateFullName();
    }

    public function updateFullName($parent_name = null) {
    	$this->generateFullName($parent_name);
    	$this->save();

    	foreach($this->childrens as $children) {
    		$children->updateFullName($this->fullname);
    	}
    }

    public function generateFullName($parent_name = null) {
    	if($parent_name) {
    		$this->fullname = $parent_name . ', ' . $this->name;
    	} else {
    		if($this->parent) {
    			$this->fullname = $this->parent->getFullName() . ', ' . $this->name;
    		} else {
    			$this->fullname = $this->name;
    		}
    	}

    	return $this->fullname;
    }

    public function isCountry() {
    	return $this->type == 'country';
    }

    public function isRegion() {
    	return $this->type == 'region';
    }

    public function isCity() {
    	return $this->type == 'city';
    }

    public static function countries() {
    	return GeoObject::where('type', 'country');
    }

    public static function regions() {
    	return GeoObject::where('type', 'region');
    }

    public static function cities() {
    	return GeoObject::where('type', 'city');
    }
}