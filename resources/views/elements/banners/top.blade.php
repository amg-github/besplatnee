<!-- Banner outer page -->
<div class="banner-wrapper">
    <div class="banner-two-item clearfix">
    @foreach(Besplatnee::banners()->getPosition('top', isset($category) ? $category->id : 0) as $banner) 
        <div class="{{ $version == 'a' || $version == 'b' ? 'block-left col-xs-12' : 'col-xs-6' }}">
            <div class="row">
                <a href="{{ $banner->url }}">
                    <img src="{{ $banner->image }}" alt="{{ $banner->hover_text }}">
                </a>
            </div>
        </div>
    @endforeach
    </div>
</div>
<!-- End banner outer page -->