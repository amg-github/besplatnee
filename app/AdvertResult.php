<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertResult extends Model
{
    protected $fillable = [
        'geo_object_id',
        'heading_id',
        'postcount'
    ];

    protected $primaryKey = 'geo_object_id';

    public $timestamps = false;
}
