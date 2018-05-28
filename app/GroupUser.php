<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{

    protected $table = 'group_user';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 
        'group_id',
    ];

	public function user() {
		return $this->hasOne('App\User');
	}

}
