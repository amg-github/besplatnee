<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = [];

    protected $hidden = [
    	'id', 
    	'user_id', 
    	'verify_code',
        'verify',
        'blocked', 
    ];

    protected $casts = [
        'verify' => 'boolean',
        'blocked' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
