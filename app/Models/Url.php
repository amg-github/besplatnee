<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [];

    protected $hidden = [
    	'object_type'
    	'object_id', 
    	'url', 
    ];

    public $timestapms = false;

    public function urlable() {
    	return $this->morphTo();
    }
}
