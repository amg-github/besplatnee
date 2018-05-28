<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
    	'id',
    	'name',
    	'code',
    ];

    public function regions() {
    	return $this->hasMany('App\Region');
    }

    public function cities() {
    	return $this->hasMany('App\City');
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
