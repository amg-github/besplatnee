<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advert extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'heading_id', 
        'context_id',
    	'name', 
    	'main_phrase',
        'approved', 
        'price',
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
        'blocked' => 'boolean',
        'active' => 'boolean',
        'show_phone' => 'boolean',
        'duplicate_in_all_cities' => 'boolean',
        'approved' => 'boolean',
    ];

    public function creator() {
        return $this->belongsTo('App\Models\User');
    }

    public function geoObjects() {
        return $this->belongsToMany('App\Models\GeoObject');
    }

    public function heading() {
        return $this->belongsTo('App\Models\Heading');
    }

    public function medias() {
        return $this->morphMany('App\Models\Media', 'mediable');
    }

    public function owner() {
        return $this->belongsTo('App\Models\User');
    }
}
