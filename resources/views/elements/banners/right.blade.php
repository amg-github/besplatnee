<?php 
	$banners = Besplatnee::banners()->getBanners('right', isset($category) ? $category->id : null, isset($block_number) ? $block_number : null);
?>
@if(count($banners) > 0)
	@for($i = 0; $i < 6; $i++)
		@if(isset($banners[$i]))
		    @if($url = $banners[$i]->getUrl())
			    <a href="{{ $url }}" class="{{ collect(\Session::get('userdata.banners.hidden.ids', []))->contains($banners[$i]->id) ? 'hidden' : '' }}" target="__blank" onclick="SITE.banners.click({{ $banners[$i]->id }})" data-id="{{ $banners[$i]->id }}">
			        @if(Auth::check())
			            <button class="hide-banner" title="Скрыть объявление" onclick="SITE.banners.hide({{ $banners[$i]->id }});event.preventDefault();event.stopPropagation()">X</button>
			        @endif
		            <img src="{{ $banners[$i]->image }}" title="{{ $banners[$i]->hover_text }}">
		            <!--<div class="banner-counter">
		                Переходов:&nbsp;{{ $banners[$i]->clickcount }}
		            </div>-->
		            <div class="show-button" onclick="SITE.banners.show({{ $banners[$i]->id }});event.preventDefault();event.stopPropagation()">Показать объявление</div>
		        </a>
		    @else
		    	<a class="{{ collect(\Session::get('userdata.banners.hidden.ids', []))->contains($banners[$i]->id) ? 'hidden' : '' }}" data-id="{{ $banners[$i]->id }}">
			        @if(Auth::check())
			            <button class="hide-banner" title="Скрыть объявление" onclick="SITE.banners.hide({{ $banners[$i]->id }});event.preventDefault();event.stopPropagation()">X</button>
			        @endif
		            <img src="{{ $banners[$i]->image }}" title="{{ $banners[$i]->hover_text }}">
		            <!--<div class="banner-counter">
		                Переходов:&nbsp;{{ $banners[$i]->clickcount }}
		            </div>-->
		            <div class="show-button" onclick="SITE.banners.show({{ $banners[$i]->id }});event.preventDefault();event.stopPropagation()">Показать объявление</div>
		        </a>
		    @endif
	    @else

	    @endif
	@endfor
@endif