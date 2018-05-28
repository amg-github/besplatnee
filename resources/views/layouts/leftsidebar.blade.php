<div class="block-left left-sidebar-banner-wrapper">
    @foreach(Besplatnee::banners()->getPosition('left', isset($category) ? $category->id : 0) as $banner) 
    	<a href="{{ $banner->url }}" target="__blank" onclick="SITE.banners.click({{ $banner->id }})" data-id="{{ $banner->id }}">
    	@if(Auth::check())
    		<button class="hide-banner" title="@lang('adverts.banner.hide')" onclick="SITE.banners.hide({{ $banner->id }});event.preventDefault();event.stopPropagation()">X</button>
    	@endif
    		<img src="{{ $banner->image }}" alt="{{ $banner->hover_text }}">
    		<div class="banner-counter">
    			<!--Просмотров:&nbsp;{{ $banner->viewcount }}<br>-->@lang('adverts.banner.hit'):&nbsp;{{ $banner->clickcount }}
    		</div>
    	</a>
    @endforeach
</div>