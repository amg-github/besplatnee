<div class="banner-wrapper clearfix">
<?php 
	$banners = Besplatnee::banners()->getBanners('footer', isset($category) ? $category->id : null, isset($block_number) ? $block_number : null);
?>
@if(count($banners) > 0)
	@for($i = 0; $i < 6; $i++)
		@if(isset($banners[$i]))
	    <div class="banner-item-wrapper">
	    	@if($url = $banners[$i]->getUrl())
		        <a href="{{ $url }}" target="__blank">
		            <img src="{{ $banners[$i]->image }}" title="{{ $banners[$i]->hover_text }}" alt="{{ $banners[$i]->hover_text }}">
		        </a>
		    @else
		    	<img src="{{ $banners[$i]->image }}" title="{{ $banners[$i]->hover_text }}" alt="{{ $banners[$i]->hover_text }}">
		    @endif
	    </div>
	    @else
	    <div class="banner-item-wrapper">
	    	<img src="" alt="">
	    </div>
	    @endif
	@endfor
@endif

</div>