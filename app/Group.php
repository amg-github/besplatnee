<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name', 
        'description',
        'parent_id',
        'policies',
        'sudo',
    ];

    protected $hidden = [
        'created_at', 
        'updated_at', 
        'pivot',
    ];

    protected $casts = [
        'policies' => 'array',
        'sudo' => 'boolean',
    ];

	public function users() {
		return $this->belongsToMany('App\User');
	}

    public function permissions() {
        return $this->belongsToMany('App\Permission');
    }

	public function checkPolicy($policy) {
		return $this->sudo || $this->permissions->pluck('name')->contains($policy) || ($this->parent && $this->parent->checkPolicy($policy));
	}

    public function parent() {
        return $this->belongsTo('App\Group');
    }
}
