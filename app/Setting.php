<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;


    protected $fillable = [
    	'value',
    ];

    protected $hidden = [];


    public static function getOption($name, $defaultValue) {
    	$setting = Setting::where('name', $name)->first();
    	return $setting ? $setting->value : $defaultValue;
    }
}
