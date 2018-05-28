<li class="header-categories-all-item header-categories-all-item_parent">
    <a class="header-categories-all-link"
       href="{{ $heading->getUrl() }}">
       @lang($heading->name)
    </a>
    @foreach(\Besplatnee::headings()->getChildrens($heading) as $subHeading)
    	@include('elements.header.categories.item', [
    		'heading' => $subHeading,
    	])
    @endforeach
</li>