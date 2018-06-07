<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeadingAlias extends Model
{
	public $_property_value = null;

    protected $fillable = [
    	'language',
    	'alias_local',
    	'alias_international',
    	'property_id',
    	'property_value',
    	'auto_words_before',
    	'auto_words_after',
        'heading_id',
    ];

    public function heading() {
    	return $this->belongsTo('App\Heading');
    }

    public function property() {
    	return $this->belongsTo('App\Property');
    }

    public function propertyValue() {
    	if(!$this->_property_value) {
    		$this->_property_value = \App\PropertyDefaultValue::where('property_id', $this->property_id)
    			->where('value', $this->property_value)
    			->get()
    			->first();
    	}

    	return $this->_property_value;
    }

    public function getName() {
    	if($this->property_id && $this->propertyValue()) {
    		return __($this->propertyValue()->title);
    	} else {
    		return __($this->heading->name);
    	}
    }

    public function getUrl($sort = null) {
    	return route('category', [
            'alias' => $this->alias_international,
            'city' => 'v_' . config('area')->genitive_alias,
            'sort' => $sort ? $sort : __('sorts.fakeupdated_at.alias'),
        ]);
    }
}
