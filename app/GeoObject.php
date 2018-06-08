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

	protected $hidden = [];

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


    public function region() {
        switch ($this->type) {
            case 'country':
                return $this->hasMany('App\GeoObject', 'parent_id')->get();
                break;
            case 'city':
                return $this->hasOne('App\GeoObject', 'id', 'parent_id')->first();
                break;
            default:
                return $this;
                break;
        }
    }

    public function country() {
        switch ($this->type) {
            case 'city':
                return $this->region()->country();
                break;
            case 'region':
                return $this->hasOne('App\GeoObject', 'id', 'parent_id')->first();
                break;
            default:
                return $this;
                break;
        }
    }

    public function city() {
        switch ($this->type) {
            case 'country':
                $ids = $this->region()->pluck('id')->toArray();
                return self::cities()->whereIn('parent_id', $ids)->get();
                break;
            case 'region':
                return $this->hasMany('App\GeoObject', 'parent_id')->get();
                break;
            default:
                return $this;
                break;
        }
    }

    public static function countries() {
    	return GeoObject::where('type', 'country')->where('active', 1);
    }

    public static function regions() {
    	return GeoObject::where('type', 'region');
    }

    public static function cities() {
    	return GeoObject::where('type', 'city');
    }

    public function getProps() {
        return json_decode($this->properties, true);
    }

    public function getPropsTemplate($type) {
        switch ($type) {
            case 'country':
                $props = [
                    'phone_code'    => '',
                    'flag_image'    => '',
                    'capital'       => '',
                    'country_code'  => '',
                    'currency'      => '',
                ];

                break;

            case 'city':
                $props = [
                    'contact_email' => '',
                    'contact_phone' => '',
                    'time_zone'     => '',
                ];

                break;

            default:
                $props = null;
        }

        return $props;
    }

    public function setProps($new_props = []) {
        //создается коллекция новых значений
        $new_props = collect($new_props);

        //запрашивается шаблон параметров
        $template = $this->getPropsTemplate($this->type);

        //отсеиваются лишние поля в новых параметров по шаблону
        $new_props = $new_props->intersectByKeys($template);

        if ($this->properties) {

            //получение уже существующих параметров
            $props = $this->getProps();

            //отсеивание лишних полей новых параметров от уже существующих
            $new_props = $new_props->intersectByKeys($props);

            //отсеивание лишних полей уже существующих параметров от шаблонных
            $props = collect($props)->intersectByKeys($template)->toArray();

            //мержим старые данные и новые
            $new_props = array_merge($new_props->toArray(), $props);
        } else {
            $new_props = $new_props->toArray();
        }
        //мержим шаблонные и новые значения
        $props = json_encode(array_merge($template, $new_props));

        $this->update(['properties' => $props]);

    }

}