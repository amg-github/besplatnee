<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $timestamps = false;


    protected $fillable = [
    	'name',
    	'value',
    ];

    protected $hidden = [];

    public static function getOption($name, $defaultValue) {
    	$setting = Settings::where('name', $name)->first();
    	return $setting ? $setting->value : $defaultValue;
    }
}
