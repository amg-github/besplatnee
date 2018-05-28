<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MegaAdvert extends Model
{
    protected $fillable = [
    	'advert_id',
    	'border_color',
        'text_top',
        'text_bottom',
    	'background_color',
    	'font_color',
    	'border_width',
    	'font_width',
    ];

    public function advert() {
    	return $this->belongsTo('App\Advert');
    }

    public function getImage($width, $height) {
        return '<div class="banner-image" style="' . $this->getStyle($width, $height) . '">' 
            . '<div class="text_top">' . $this->text_top . '</div>'
            . '<div class="text_bottom">' . $this->text_bottom . '</div>'
        . '</div>';
    }

    public function getStyle($width, $height) {
        $styles = "width:" . $width . 'px;height:' . $height . 'px;border-style:solid;';

        if($this->border_width) {
            $styles .= 'border-width:' . $this->border_width . 'px;';
        }

        if($this->border_color) {
            $styles .= 'border-color:' . $this->border_color . ';';
        }

        if($this->font_width) {
            $styles .= 'font-size: ' . $this->font_width . 'px;';
        }

        if($this->font_color) {
            $styles .= 'color: ' . $this->font_color . ';';
        }

        if($this->background_color) {
            $styles .= 'background: ' . $this->background_color . ';';
        }

        return $styles;
    }
}
