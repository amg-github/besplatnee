<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Heading extends Model
{

    protected $fillable = [
    	'name', 'parent_id', 'sortindex', 'active', 'show_in_top_menu'
    ];

    protected $casts = [
        'active' => 'boolean',
        'show_in_top_menu' => 'boolean',
    ];

    public function childrens() {
        return $this->hasMany('App\Models\Heading', 'parent_id', 'id');
    }

    public function parent() {
    	return $this->belongsTo('App\Models\Heading', 'parent_id', 'id');
    }

    // public function url() {
    //     return $this->morphOne('App\Models\Url', 'urlable');
    // }
}
