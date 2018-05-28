<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerImage extends Model
{
    protected $fillable = [
    	'text_top',
    	'text_bottom',
        'border_width',
        'border_color',
        'font_size',
        'font_color',
        'background',
    ];

    protected $hidden = [];

    protected $cast = [];

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

        if($this->font_size) {
            $styles .= 'font-size: ' . $this->font_size . 'px;';
        }

        if($this->font_color) {
            $styles .= 'color: ' . $this->font_color . ';';
        }

        if($this->background) {
            $styles .= 'background: ' . $this->background . ';';
        }

        return $styles;
    }
}
