@if(request()->route()->controller->breadcrumbs()->count() > 1)
<!-- Breadcrumbs -->
<div class="breadcrumbs-wrapper">
    <ul class="breadcrumbs">
    @foreach(request()->route()->controller->breadcrumbs() as $breadcrumb)
    	@if($loop->index > 0) 
    		<span class="separation">/</span>
    	@endif
        <li><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a></li>
    @endforeach
    </ul>
</div>
<!-- End breadcrumbs -->
@endif