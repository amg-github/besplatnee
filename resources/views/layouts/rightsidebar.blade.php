<div class="block-left right-sidebar-banner-wrapper">
	@foreach(Besplatnee::banners()->getPosition('right', isset($category) ? $category->id : 0) as $banner)  
    	<a href="{{ $banner->url }}" target="__blank" onclick="SITE.banners.click({{ $banner->id }})" data-id="{{ $banner->id }}">
    	@if(Auth::check())
    		<button class="hide-banner" title="Скрыть объявление" onclick="SITE.banners.hide({{ $banner->id }});event.preventDefault();event.stopPropagation()">X</button>
    	@endif
    		<img src="{{ $banner->image }}" alt="{{ $banner->hover_text }}">
    		<div class="banner-counter">
    			<!--Просмотров:&nbsp;{{ $banner->viewcount }}<br>-->Переходов:&nbsp;{{ $banner->clickcount }}
    		</div>
    	</a>
    @endforeach
</div>