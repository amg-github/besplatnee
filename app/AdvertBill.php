<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertBill extends Model
{
    protected $fillable = [
        'advert_id',
        'type',
    	'advert_template_id',
    	'deleted_at',
    	'status',
    	'price',
    ];

    protected $hidden = [];

    protected $cast = [];

    public function advert() {
    	return $this->belongsTo('App\Advert')->withTrashed();
    }

    public function template() {
    	return $this->belongsTo('App\AdvertTemplate', 'advert_template_id', 'id');
    }
}
