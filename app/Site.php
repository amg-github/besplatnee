<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
    	'allias',
    	'description',
    	'site_url',
    	'logo',
    ];

    protected $hidden = [
    	'creator_id',
    	'owner_id',
    	'type',
    	'status',
    	'main_page',
    ];

    public function pages() {
        return $this->hasMany('App\SitePage');
    }

    public function mainpage() {
        return $this->hasOne('App\SitePage', 'site_id', 'main_page');
    }
}
