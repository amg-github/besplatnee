<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
    	'path', 
    	'name', 
    	'type', 
    ];

    public function mediable() {
    	return $this->morphTo();
    }
}
