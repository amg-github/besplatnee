<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
    	'name',
    	'title',
    	'description',
    ];

    protected $cast = [

    ];

    public function groups() {
    	return $this->belongsToMany('App\Group');
    } 
}
