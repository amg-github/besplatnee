<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name', 
        'description',
        'parent_id',
        'sudo',
    ];

    protected $hidden = [
        'created_at', 
        'updated_at', 
        'pivot',
    ];

    protected $casts = [
        'sudo' => 'boolean',
    ];

    public function parent() {
        return $this->belongsTo('App\Models\Group');
    }

    public function permissions() {
        return $this->belongsToMany('App\Models\Permission');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User');
    }

	public function checkPolicy($policy) {
		return $this->sudo 
            || $this->permissions->pluck('name')->contains($policy) 
            || ($this->parent && $this->parent->checkPolicy($policy));
	}
}
