<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
	protected $fillable = [
		'country_id',
		'region_id',
		'name_key',
        'contact_phone',
        'contact_email',
		'active',
		'latitude',
        'longitude',
        'northeast_latitude',
        'northeast_longitude',
        'southwest_latitude',
        'southwest_longitude',
	];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function users() {
    	return $this->belongsToMany('App\User');
    }

    public function adverts() {
    	return $this->belongsToMany('App\Advert');
    }

    public function region() {
    	return $this->belongsTo('App\Region');
    }

    public function country() {
    	return $this->belongsTo('App\Country');
    }

    public function aliases() {
        return $this->hasMany('App\AreaName', 'key', 'name_key');
    }

    public function getName() {
        $this->loadNames();

        return $this->nominative_local;
    }

    public function getForName() {
        $this->loadNames();

        return $this->genitive_local;
    }

    public function getInName() {
        $this->loadNames();

        return $this->ergative_local;
    }

    public function getInternationalName() {
        $this->loadNames();

        return $this->nominative_international;
    }

    public function getInternationalForName() {
        $this->loadNames();

        return $this->genitive_international;
    }

    public function getInternationalInName() {
        $this->loadNames();

        return $this->ergative_international;
    }

    public function loadNames() {
        if(
            !$this->nominative_local ||
            !$this->genitive_local ||
            !$this->ergative_local ||
            !$this->nominative_international ||
            !$this->genitive_international ||
            !$this->ergative_international) {
            
            $names = $this->aliases()->where('language', \App::getLocale())->first();

            if($names) {
                $this->nominative_local = $names->nominative_local;
                $this->genitive_local = $names->genitive_local;
                $this->ergative_local = $names->ergative_local;
                $this->nominative_international = $names->nominative_international;
                $this->genitive_international = $names->genitive_international;
                $this->ergative_international = $names->ergative_international;
            }
        }
    }
}
