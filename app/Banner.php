<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
    	'name',
    	'hover_text',
    	'contact_information',
    	'url',
    	'image',
        'image_id',
    	'heading_id',
    	'comment',
    	'duplicate_in_all_cities',
        'width',
        'height',
        'position',
        'organization_id',
        'status',
        'price',
        'deleted_at',
        'active',
        'block_number',
        'banner_number',
    ];

    protected $hidden = [
    	'creator_id',
    	'sortindex',
    ];

    protected $cast = [
    	'active' => 'boolean',
        'duplicate_in_all_cities' => 'boolean',
    ];

    public function creator() {
        return $this->belongsTo('App\User');
    }

    public function heading() {
        return $this->belongsTo('App\Heading');
    }

    public function image() {
        return $this->belongsTo('App\BannerImage');
    }

    public function countries() {
        return $this->belongsToMany('App\Country');
    }

    public function regions() {
        return $this->belongsToMany('App\Region');
    }

    public function cities() {
        return $this->belongsToMany('App\City');
    }

    public function geoObjects() {
        return $this->belongsToMany('App\GeoObject');
    }

    public function getUrl() {
        if(is_numeric($this->url)) {
            $advert = \App\Advert::find($this->url);
            return $advert->getUrl();
        }

        return $this->url;
    }

    public function getImageTemplate() {
        $image = $this->image()->first();
        
        if($image) {
            return $image->getImage($this->width, $this->height);
        } else {
            return '<img src="' . asset($this->image) . '" width="' . $this->width . '" height="' . $this->height . '" style="width:auto;max-width:' . $this->width . 'px">';
        }
    }

    public function setDeletedAt($period) {
        $this->deleted_at = \Carbon\Carbon::now()->addWeeks($period);
    }

    public function getPeriod() {
        $deleted_at = \Carbon\Carbon::parse($this->deleted_at);
        $updated_at = \Carbon\Carbon::parse($this->updated_at);
        return $this->deleted_at ? $deleted_at->diffInWeeks($updated_at) : 0;
    }

    public function placement($global = false, $countries = [], $regions = [], $cities = []) {
        $blankAreas = [];

        if($global && !$this->placementToWorld()) {
            $blankAreas[] = [
                'type' => 'world',
                'id' => 1,
            ]; 
        }

//        foreach($this->placementToCountries($countries) as $country) {
//            $blankAreas[] = [
//                'type' => 'country',
//                'id' => $country,
//            ];
//        }
//
//        foreach($this->placementToRegions($regions) as $region) {
//            $blankAreas[] = [
//                'type' => 'region',
//                'id' => $region,
//            ];
//        }
//
//        foreach($this->placementToCities($cities) as $city) {
//            $blankAreas[] = [
//                'type' => 'city',
//                'id' => $city,
//            ];
//        }

        foreach($this->placementToObject($countries) as $country) {
            $blankAreas[] = [
                'type' => 'country',
                'id' => $country,
            ];
        }

        foreach($this->placementToObject($regions) as $region) {
            $blankAreas[] = [
                'type' => 'region',
                'id' => $region,
            ];
        }

        foreach($this->placementToObject($cities) as $city) {
            $blankAreas[] = [
                'type' => 'city',
                'id' => $city,
            ];
        }

        return $blankAreas;
    }

    public function placementToWorld() {
        $this->duplicate_in_all_cities = Banner::where('active', true)
            ->where('heading_id', $this->heading_id)
            ->where('duplicate_in_all_cities', true)
            ->where('id', '!=', $this->id)
            ->count() > 0;

        $this->save();

        return $this->duplicate_in_all_cities;
    }

    public function placementToCountries($countries) {
        $filled = [];
        $blank = [];

        foreach($countries as $country) {
            if(Banner::where('active', true)
                ->whereHas('countries', function ($q) use ($country) {
                    $q->where('id', $country);
                })
                ->where('heading_id', $this->heading_id)
                ->where('banner_number', $this->banner_number)
                ->where('block_number', $this->block_number)
                ->where('id', '!=', $this->id)
                ->count() > 0) {

                $blank[] = $country;
            } else {
                $filled[] = $country;
            }
        }

        $this->countries()->sync($filled);

        return $blank;
    }

    public function placementToRegions($regions) {
        $filled = [];
        $blank = [];

        foreach($regions as $region) {
            if(Banner::where('active', true)
                ->whereHas('regions', function ($q) use ($region) {
                    $q->where('id', $region);
                })
                ->where('heading_id', $this->heading_id)
                ->where('banner_number', $this->banner_number)
                ->where('block_number', $this->block_number)
                ->where('id', '!=', $this->id)
                ->count() > 0) {

                $blank[] = $region;
            } else {
                $filled[] = $region;
            }
        }

        $this->regions()->sync($filled);

        return $blank;
    }

    public function placementToCities($cities) {
        $filled = [];
        $blank = [];

        foreach($cities as $city) {
            if(Banner::where('active', true)
                ->whereHas('cities', function ($q) use ($city) {
                    $q->where('id', $city);
                })
                ->where('heading_id', $this->heading_id)
                ->where('banner_number', $this->banner_number)
                ->where('block_number', $this->block_number)
                ->where('position', $this->position)
                ->where('id', '!=', $this->id)
                ->count() > 0) {

                $blank[] = $city;
            } else {
                $filled[] = $city;
            }
        }

        $this->cities()->sync($filled);

        return $blank;
    }

    public function placementToObject($objects) {
        $filled = [];
        $blank = [];

        foreach($objects as $object) {
            if(Banner::where('active', true)
                    ->whereHas('geoObjects', function ($q) use ($object) {
                        $q->where('id', $object);
                    })
                    ->where('heading_id', $this->heading_id)
                    ->where('banner_number', $this->banner_number)
                    ->where('block_number', $this->block_number)
                    ->where('position', $this->position)
                    ->where('id', '!=', $this->id)
                    ->count() > 0) {

                $blank[] = $object;
            } else {
                $filled[] = $object;
            }
        }

        $this->geoObjects()->sync($filled);

        return $blank;
    }

    public function inCity($city_id) {
        return $this->cities->filter(function ($item) use($city_id) {
            return $item->id == $city_id;
        })->count() > 0;
    }

    public function inRegion($region_id) {
        return $this->regions->filter(function ($item) use($region_id) {
            return $item->id == $region_id;
        })->count() > 0;
    }

    public function inCountry($country_id) {
        return $this->countries->filter(function ($item) use($country_id) {
            return $item->id == $country_id;
        })->count() > 0;
    }

    public function inObject($obj_id) {
        return $this->geoObjects->filter(function ($item) use ($obj_id) {
                return $item->id == $obj_id;
            })->count() > 0;
    }
}
