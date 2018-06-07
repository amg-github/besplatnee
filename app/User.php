<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    const GENDER_UNDEFINED = 0;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 
        'lastname', 
        'patronymic', 
        'phone',
        'email', 
        'photo', 
        'gender',
        'duplicate_in_all_cities',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token', 
        'active', 
        'blocked', 
        'created_at', 
        'updated_at',
        'admin_cities',
    ];

    protected $casts = [
        'active' => 'boolean',
        'blocked' => 'boolean',
        'admin_cities' => 'array',
        'duplicate_in_all_cities' => 'boolean',
    ];

    public function applications() {
        return $this->belongsToMany('App\Application');
    }

    public function groups() {
        return $this->belongsToMany('App\Group');
    }

    public function countries() {
        return $this->belongsToMany('App\GeoObject')->where('type', 'country');
    }

    public function regions() {
        return $this->belongsToMany('App\GeoObject')->where('type', 'region');
    }

    public function cities() {
        return $this->belongsToMany('App\GeoObject')->where('type', 'city');
    }

    public function phone() {
        return $this->hasOne('App\Phone');
    }

    public function checkPolicy($policy) {
        foreach($this->groups as $group) {
            if($group->checkPolicy($policy)) {
                return true;
            }
        }

        return false;
    }

    public function checkPolicies($policies) {
        foreach($policies as $policy) {
            if(!$this->checkPolicy($policy)) {
                return false;
            }
        }

        return true;
    }

    public function accessAdminCity($city) {
        if($this->duplicate_in_all_cities) { return true; }

        if($this->cities()->where('id', $city->id)->count() > 0) { return true; }
        if($this->regions()->where('id', $city->region_id)->count() > 0) { return true; }
        if($this->countries()->where('id', $city->country_id)->count() > 0) { return true; }

        return false;
    }

    public function accessAdminRegion($region) {
        if($this->duplicate_in_all_cities) { return true; }

        if($this->regions()->where('id', $region->id)->count() > 0) { return true; }
        if($this->countries()->where('id', $region->country_id)->count() > 0) { return true; }

        return false;
    }

    public function accessAdminCountry($country) {
        if($this->duplicate_in_all_cities) { return true; }

        if($this->countries()->where('id', $country->id)->count() > 0) { return true; }

        return false;
    }

    public function accessAdminCityId($city_id) {
        if($this->inAllCities() || $this->cities->pluck('id')->contains($city_id)) { return true; }

        $city = \App\City::find($city_id);

        return $this->accessAdminCountryId($city->country_id) || $this->accessAdminRegionId($city->region_id);
    }

    public function accessAdminRegionId($region_id) {
        if($this->inAllCities() || $this->regions->pluck('id')->contains($region_id)) { return true; }

        return $this->accessAdminCountryId(\App\Region::find($region_id)->country_id);
    }

    public function accessAdminCountryId($country_id) {
        return $this->inAllCities() || $this->countries->pluck('id')->contains($country_id);
    }

    public function isSuperAdmin() {
        foreach($this->groups as $group) {
            if($group->sudo) {
                return true;
            }
        }

        return false;
    }

    public function inAllCities() {
        return $this->duplicate_in_all_cities || $this->isSuperAdmin();
    }

    public function fullname() {
        return implode(' ', [
            $this->firstname,
            $this->patronymic,
            $this->lastname,
        ]);
    }

    public function isAccessToAdminPanel() {
        return $this->checkPolicy('cpanel_access') && $this->accessAdminCity(\Config::get('area'));
    }

    public function customAdverts() {
        return $this->belongsToMany('App\Advert', 'user_advert_customs', 'user_id', 'advert_id')->withPivot('comment', 'hidden', 'favorite', 'sort');
    }

    public function customBanners() {
        return $this->belongsToMany('App\Banner', 'user_banner_custom', 'user_id', 'banner_id')->withPivot('hidden');
    }

    public function getAccessCountries($return_ids = false) {
        if($this->inAllCities()) { return $return_ids ? \App\GeoObject::where('type', 'country')->pluck('id') : \App\GeoObject::where('type', 'country')->get(); }

        $allowedCountriesForAllowedRegions = $this->regions->pluck('parent_id');
        $allowedCountriesForAllowedCities = $this->cities->regions->pluck('parent_id');
        $allowedCountries = $this->countries->pluck('id');
        $country_ids = $allowedCountries->merge($allowedCountriesForAllowedRegions)->merge($allowedCountriesForAllowedCities)->unique();

        return $return_ids ? $country_ids : \App\GeoObject::whereIn('id', $country_ids)->get();
    }

    public function getAccessRegions($country_id = null, $return_ids = false) {
        if($this->inAllCities()) {
            if($country_id) {
                $query = \App\GeoObject::where('parent_id', $country_id);
                return $return_ids ? $query->pluck('id') : $query->get();
            } else {
                return $return_ids ? \App\GeoObject::where('type', 'region')->pluck('id') : \App\GeoObject::where('type', 'region')->get();
            }
        } else {
            $allowedRegionIdsByAllowedCities = $this->cities()->where('active', true)->distinct()->pluck('parent_id');
            $allowedRegionIds = $this->regions()->pluck('id')->merge($allowedRegionIdsByAllowedCities);

            $query = \App\GeoObject::whereIn('id', $allowedRegionIds);
            if($country_id) {
                $query->where('parent_id', $country_id);
            }

            return $return_ids ? $query->pluck('id') : $query->get();
        }
    }

    public function getAccessCities($region_id = null, $country_id = null, $return_ids = false) {
        $query = \App\GeoObject::where('active', true);

        if($region_id) {
            $query->where('parent_id', $region_id);
        }

        if($country_id) {
            $query->where('parent_id', $country_id);
        }

        if(!$this->inAllCities()) {
            $allowedCities = $this->cities->pluck('id');
            $allowedRegions = $this->regions->pluck('id');
            $allowedCountries = $this->countries->pluck('id');

            $query->where(function ($query) use ($allowedCities, $allowedRegions, $allowedCountries) {
                $query->whereIn('id', $allowedCities);
            });
        }

        return $return_ids ? $query->pluck('id') : $query->get();
    }
}
