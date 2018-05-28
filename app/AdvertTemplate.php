<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertTemplate extends Model
{
    protected $fillable = [
    	'name',
    	'border_width',
    	'border_color',
    	'background',
    	'font_size',
    	'font_color',
    	'bold',
    ];

    protected $hidden = [];

    protected $cast = [
    	'bold' => 'boolean',
    ];

    public function buildStyles() {
    	$styles = '';

    	if($this->border_width != null) {
    		$styles .= 'border-style:solid;border-width:' . $this->border_width . 'px;';
    	}

    	if($this->border_color != null) {
    		$styles .= 'border-color:' . $this->border_color . ';';
    	}

    	if($this->background != null) {
    		$styles .= 'background:' . $this->background . ';';
    	}

    	if($this->font_size != null) {
    		$styles .= 'font-size:' . $this->font_size . 'px;';
    	}

    	if($this->font_color != null) {
    		$styles .= 'color:' . $this->font_color . ';';
    	}

    	if($this->bold) {
    		$styles .= 'font-weight:bold;';
    	}

    	return $styles;
    }
}
