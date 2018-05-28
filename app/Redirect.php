<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model {

	protected $fillable = [
		'source',
		'target',
		'active',
	];

	protected $cats = [
		'active' => 'boolean',
	];

}