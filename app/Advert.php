<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Property;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advert extends Model
{
    use SoftDeletes;

    const DO_NOT_DUPLICATE_TO_ALL_CITIES = 0;
    const DUPLICATE_TO_ALL_CITIES = 1;

    const TYPE_MEGA     = 3;
    const TYPE_DEFAULT  = 4;

    protected $fillable = [
        'type',
        'mega_text_top',
        'mega_text_bottom',
        'template_id',
        'vip',
        'accented',
    	'heading_id', 
        'context_id',
    	'name', 
    	'main_phrase',
        'approved', 
        'price',
        'alias_local',
        'alias_international',
        'active',
        'show_phone',
        'content',
        'extend_content',
        'address', 
        'send_to_print',
        'site_url',
        'duplicate_in_all_cities',
        'latitude',
        'longitude',
        'contacts',
        'fullname',
        'unpublished_on',
        'owner_id',
        'creator_id',
    ];

    protected $hidden = [
    	'blocked',
        'status',
        'viewcount',
        'clickcount',
        'owner_id',
        'creator_id',
    ];

    protected $cast = [
        'blocked'                   => 'boolean',
        'active'                    => 'boolean',
        'show_phone'                => 'boolean',
        'duplicate_in_all_cities'   => 'boolean',
        'approved'                  => 'boolean',
    ];

    public $thumbs = [
        [585, 350],
        [180, 160],
    ];

    public function properties() {
    	return $this->belongsToMany('App\Property')->withPivot('value');
    }

    public function context() {
    	return $this->belongsTo('App\Context');
    }

    public function heading() {
    	return $this->belongsTo('App\Heading');
    }

    public function owner() {
        return $this->belongsTo('App\User');
    }

    public function creator() {
        return $this->belongsTo('App\User');
    }

    public function geoObjects() {
        return $this->belongsToMany('App\GeoObject');
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

    public function medias() {
    	return $this->hasMany('App\AdvertMedia');
    }

    public function megaAdvert() {
        return $this->hasOne('App\MegaAdvert');
    }

    public function template() {
        return $this->hasOne('App\AdvertTemplate', 'id', 'template_id');
    }

    public function bills() {
        // return $this->hasMany('App\AdvertBill')->where('deleted_at', '>', \Carbon\Carbon::now())->where('advert_template_id', '>' , 0)->orderBy('deleted_at', 'DESC');
        return $this->hasMany('App\AdvertBill')->where('advert_template_id', '>' , 0)->orderBy('deleted_at', 'DESC');
    }

    public function allBills() {
        return $this->hasMany('App\AdvertBill')->orderBy('deleted_at', 'DESC');
    }

    public function getMegaImage($width, $height) {
        
        $template = $this->template()->first();

        if ($template != null) {
            $template = $template->buildStyles();
        }

        return '<div class="banner-image" style="' . $template . '">' 
            . '<div class="text_top">' . $this->mega_text_top . '</div>'
            . '<div class="text_bottom">' . $this->mega_text_bottom . '</div>'
        . '</div>';
    }

    public function getProperty($name, $default = '') {
        $property = $this->properties()->where('name', $name)->first();
        return $property ? $property->pivot->value : $default;
    }

    public function setProperty($name, $value) {
        $property_id = Property::where('name', $name)->value('id');
        if($property_id) {
            $this->properties()->syncWithoutDetaching($property_id, ['value' => $value]);
        }
    }

    public function setProperties($properties) {
        if(count($properties) > 0) {
            $property_ids = Property::whereIn('name', array_keys($properties))->pluck('id', 'name');
        } else {
            $property_ids = [];
        }

        $advertProperties = [];
        foreach($property_ids as $name => $id) {
            $advertProperties[$id] = ['value' => $properties[$name]];
        }

        $this->properties()->sync($advertProperties);
    }

    public function setCities($cities = []) {
        $this->cities()->sync($cities);
    }

    public function setRegions($regions = []) {
        if(is_array($regions)) {
            $this->regions()->sync($regions);
        }
    }

    public function setCountries($countries = []) {
        if(is_array($countries)) {
            $this->countries()->sync($countries);
        }
    }

    public function definedLocation() {
        if($this->address == '' && $this->latitude == 0 && $this->longitude == 0) { return ; }

        $yapi = new \Yandex\Geo\Api;

        if($this->address == '') {
            $yapi->setPoint($this->longitude, $this->latitude);
        } else {
            $yapi->setQuery($this->address);
        }

        $yapi->setLimit(1)
            ->setLang(\Yandex\Geo\Api::LANG_RU)
            ->load();

        if($response = $yapi->getResponse()->getFirst()) {

            if($this->address == '') {
                $this->address = $response->getAddress();
            } else {
                $this->latitude = $response->getLatitude();
                $this->longitude = $response->getLongitude();
            }

        }
    }

    public function setOwner($owner = null) {
        if($owner == null) {
            $owner = $this->creator_id == null ? 1 : $this->creator_id;
        }

        if($this->creator_id == null) {
            $this->creator_id = \Auth::check() ? \Auth::id() : $owner;
        }

        $this->owner_id = $owner;
    }

    public function setPhotos($photos) {
        $this->medias()->where('type', 'image')->whereNotIn('path', $photos)->delete();
        $existsPaths = $this->medias()->where('type', 'image')->whereIn('path', $photos)->pluck('path')->toArray();

        foreach($photos as $photo) {
            if(in_array($photo, $existsPaths)) { continue ; }

            if(!file_exists(public_path($photo))) { continue ; }

            $url = $this->generateThumb(public_path($photo));

            $this->medias()->save(new \App\AdvertMedia([
                'path' => 'storage/' . $url,
                'name' => basename($url),
                'type' => 'image',
            ]));
        }
    }

    public function generateThumbs() {
        for($i = 0; $i < count($this->thumbs); $i++) {
            $this->medias()->where('type', 'preview_' . $this->thumbs[$i][0] . 'x' . $this->thumbs[$i][1])->delete();
            foreach($this->medias()->where('type', 'image')->get() as $image) {
                $url = $this->generateThumb($this->getPhotoPath() . $image->name, $this->thumbs[$i][0], $this->thumbs[$i][1]);

                $this->medias()->save(new \App\AdvertMedia([
                    'path' => 'storage/' . $url,
                    'name' => $image->name,
                    'type' => 'preview_' . $this->thumbs[$i][0] . 'x' . $this->thumbs[$i][1],
                ]));
            }
        }
    }

    public function generateThumb($sourcePath, $width = null, $height = null) {
        $filename = basename($sourcePath);
        $newfilename = md5($filename. time()) . '.' . \File::extension($filename);
        $destPath = $this->getPhotoPath($width, $height) . $newfilename;
        $destUrl = $this->getPhotoUrl($width, $height) . $newfilename;

        \Storage::disk('public')->makeDirectory($this->getPhotoUrl($width, $height));

        $image = \Image::make($sourcePath);

        if($width != null && $height != null) {
            $image->fit($width, $height);
        }
        
        $image->save($destPath);

        chmod($destPath, 0777);

        return $destUrl;
    }

    public function setVideos($videos) {
        $this->medias()->where('type', 'video')->delete();

        foreach($videos as $video) {
            $this->medias()->save(new \App\AdvertMedia([
                'path' => $video,
                'name' => $video,
                'type' => 'video',
            ]));
        }
    }

    public function getPhotoUrl($width = null, $height = null) {
        $baseUrl = 'media/photos/adverts/' . $this->id .'/';

        if($width != null && $height != null) {
            $baseUrl .= 'preview/' . $width . 'x' . $height . '/';
        }

        return $baseUrl;
    }

    public function getPhotoPath($width = null, $height = null) {
        return public_path('storage/' . $this->getPhotoUrl($width, $height));
    }

    public function geVideoUrl() {
        return 'media/videos/adverts/' . $this->id .'/';
    }

    public function getVideoPath() {
        return public_path('storage/' . $this->getVideoUrl());
    }

    public function alias() {
        return Str::lower(app('slug')->make($this->name));
    }

    public function localAlias() {
        $alias = Str::lower($this->content);
        $alias = preg_replace('~[^a-zа-я0-9]+~u', '_', $alias);

        while(Str::contains('__', $alias)) {
            $alias = Str::replaceFirst('__', '_', $alias);
        }

        $alias = trim($alias, "_");

        return implode('_', array_slice(explode('_', $alias), 0, 5));
    }

    public function internationalAlias($local_alias = null) {
        if($local_alias == null) {
            $local_alias = $this->localAlias();
        }

        return str_replace('-', '_', Str::lower(app('slug')->make($local_alias)));
    }

    public function makeUrl() {

        $alias_local = $this->localAlias();
        $alias_international = $this->internationalAlias($alias_local);

        $this->fill([
            'alias_local'           =>  $alias_local,
            'alias_international'   =>  $alias_international,
        ]);

        $this->save();
    }

    public function getUrl($city_id = null) {
        $city = $city_id == null ? \Config::get('area') : \App\City::find($city_id);

        if(!$city) {
            $city = \Config::get('area');
        }

        $alias_local = $this->alias_local ? $this->alias_local : $this->localAlias();
        $alias_international = $this->alias_international ? $this->alias_international : $this->internationalAlias($alias_local);
        $city_local = str_replace(array ('-', ' '), '_', Str::lower($city->getInName()));
        $city_international = str_replace('-', '_', Str::lower($city->getInternationalInName()));

        return route('advert', [
            'alias_local'           => $alias_local,
            'alias_international'   => $alias_international,
            'city_local'            => 'в_' . $city_local,
            'city_international'    => 'v_' . $city_international,
            'id'                    => $this->id,
        ]);
    }

    public function pickup($bill) {
        // dd($this->id);

        if(isset($bill['change'])) {

            $advertBill = new \App\AdvertBill;

            $advertBill->updateOrCreate(
            [
                'type'      => $bill['type'],
                'advert_id' => $this->id,
            ],
            [
                'status' => isset($bill['status']) ? $bill['status'] : 3 ,
                'advert_template_id' => isset($bill['advert_template_id']) ? $bill['advert_template_id'] : null,
                'price' => isset($bill['price']) ? $bill['price'] : 0,
                'deleted_at' => isset($bill['deleted_at']) ? $bill['deleted_at'] : \Carbon\Carbon::now()->addWeeks($bill['period']),
            ]);
        }
    }



    public function setType($type_id) {
        $this->update(['type' => $type_id]);
    }

    public function buildPickupStyles() {
        $advertBill = $this->bills->where('status', 0)->first();

        if($advertBill) {
            $template = $advertBill->template;
            return $template->buildStyles();
        }

        return '';
    }

    public function getVideos() {
        if(!$this->_videos) { $this->_videos = collect(); }

        foreach($this->medias as $media) {
            if($media->type == 'video') {
                $this->_videos->push($media);
            }
        }

        return $this->_videos;
    }

    public function getImages($w = null, $h = null, $name = null) {
        if(!$this->_images) { $this->_images = collect(); }

        if($w == null || $h == null) {
            $key =  'original';
            $type = 'image';
        } else {
            $key =  $w . 'x' . $h;
            $type = 'preview_' . $key;
        }

        if(!$this->_images->has($key)) { 
            $this->_images->put($key, collect());

            foreach($this->medias as $media) {
                if($media->type == $type) {
                    $this->_images->get($key)->push($media);
                }
            }
        }

        if($name == null) {
            return $this->_images->get($key);
        } else {
            foreach($this->_images->get($key) as $image) {
                if($image->name == $name) {
                    return $image;
                }
            }

            return null;
        }
    }

    public function getHeadingPath() {
        $path = collect();

        $heading = $this->heading->parent;

        while($heading) {
            $path->prepend([
                'heading_id' => $heading->id,
                'title' => __($heading->name),
                'url' => $heading->getUrl(),
                'properties' => [],
            ]);

            $heading = $heading->parent;
        }

        $properties = $this->properties;

        if($properties->count() > 0) {
            $headings = \App\HeadingAlias::where('heading_id', $this->heading_id)
                ->where(function ($q) use ($properties) {
                    $q->orWhereNull('property_id');

                    foreach($properties as $property) {
                        $q->orWhere(function ($q) use ($property) {
                            $q->where('property_id', $property->id)->where('property_value', $property->pivot->value);
                        });
                    }
                })
                ->where('language', \App::getLocale())
                ->orderBy('parent_id')
                ->get();

            $endOfPath = false;
            $parent_id = null;

            while(!$endOfPath) {
                $endOfPath = true;

                foreach($headings as $heading) {

                    if($heading->parent_id == $parent_id) {
                        $path->push([
                            'heading_id' => $heading->heading_id,
                            'title' => $heading->getName(),
                            'url' => $heading->getUrl(),
                            'properties' => [],
                        ]);

                        $parent_id = $heading->id;
                        $endOfPath = false;
                    }
                }
            }
        } else {
            $path->push([
                'heading_id' => $this->heading->id,
                'title' => __($this->heading->name),
                'url' => $this->heading->getUrl(),
                'properties' => [],
            ]);
        }

        return $path;
    }

    public function isArchive() {
        return !empty($this->unpublished_on) && strtotime($this->unpublished_on) < time();
    }

    public function getHeadingUrl() {
        return $this->heading->getUrl(['view-mode' => 'b']) . '#advert-' . $this->id;
    }

    public function getCreatedAtForCurrentTimeZone($timezone = 'Europe/Samara', $format = 'Y-m-d H:i:s') {
        $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at, 'UTC');
        $date->setTimezone($timezone);
        return $date->format($format);
    }
}
