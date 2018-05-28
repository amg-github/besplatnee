<div class="city-add row">
	@if($config['country_show'])
	<div class="col-xs-{{ $config['item_size'] }}">
	    <select id="advert-city-add-country" style="width:100%" name="country_id">
	        <option value="" {{ request()->has('country_id') ? '' : 'selected' }}>Страна</option>
	        @if($config['strict_find'])
	        <!--<option value="all" style="background: #eee" {{ request()->input('country_id') == 'all' ? 'selected' : '' }}>Во всех городах</option>-->
	        <option value="duplicate" style="background: #eee" {{ request()->input('country_id') == 'duplicate' ? 'selected' : '' }}>Через весь мир</option>
	        @endif
	    	@foreach($config['countries'] as $country) 
	        <option value="{{ $country->id }}" {{ request()->input('country_id') == $country->id ? 'selected' : '' }}>{{ $country->getName() }}</option>
	    	@endforeach
	    </select>
	</div>
	@endif

	@if($config['region_show'])
	<div class="col-xs-{{ $config['item_size'] }}">
	    <select id="advert-city-add-region" style="width:100%" name="region_id">
	        <option value="" {{ request()->has('region_id') ? '' : 'selected' }}>Регион</option>
	        @if($config['strict_find'])
	        <!--<option class="strict_value" value="all" style="{!! $config['regions']->count() > 0 ? 'display: none;background: #eee' : '' !!}" {{ request()->input('region_id') == 'all' ? 'selected' : '' }}>Во всех городах страны</option>-->
	        <option class="strict_value" value="duplicate" style="{!! $config['regions']->count() > 0 ? '' : 'display: none;' !!}background: #eee" {{ request()->input('region_id') == 'duplicate' ? 'selected' : '' }}>Через всю страну</option>
	        @endif
	    	@foreach($config['regions'] as $region) 
	        <option class="region-item" value="{{ $region->id }}" {{ request()->input('region_id') == $region->id ? 'selected' : '' }}>{{ $region->getName() }}</option>
	    	@endforeach
	    </select>
	</div>
	@endif

	@if($config['city_show'])
	<div class="col-xs-{{ $config['item_size'] }}">
	    <select id="advert-city-add-city" style="width:100%" name="city_id">
	        <option value=""  {{ request()->has('city_id') ? '' : 'selected' }}>Город</option>
	        @if($config['strict_find'])
	        <!--<option class="strict_value" value="all" style="display: none;background: #eee" {{ request()->input('city_id') == 'all' ? 'selected' : '' }}>Во всех городах региона</option>-->
	        <option class="strict_value" value="duplicate" style="{!! $config['cities']->count() > 0 ? '' : 'display: none;' !!}background: #eee" {{ request()->input('city_id') == 'duplicate' ? 'selected' : '' }}>Через весь регион</option>
	        @endif
	    	@foreach($config['cities'] as $city) 
	        <option class="city-item" value="{{ $city->id }}" {{ request()->input('city_id') == $city->id ? 'selected' : '' }}>{{ $city->getName() }}</option>
	    	@endforeach
	    </select>
	</div>
	@else
	<input type="hidden" name="city_ids[]" value="{{ config('area')->id }}">
	@endif
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        $('#advert-city-add-country').on('change', function () {
            $('#advert-city-add-region option.region-item').remove();
            $('#advert-city-add-city option.city-item').remove();
            $('#advert-city-add-region option.strict_value').hide();
            $('#advert-city-add-city option.strict_value').hide();

            if($(this).val() != "" && $(this).val() != 'all' && $(this).val() != 'duplicate') {

	            SITE.location.getAdminRegions($(this).val(), function (r) {
	                if(r.success) {
	            		$('#advert-city-add-region option.strict_value').show();
	                    $.each(r.data, function () {
	                    	$('#advert-city-add-region').append('<option class="region-item" value="' + this.id + '">' + this.nominative_local + '</option>');
	                    });
	                }
	            });

	        }
        });

        $('#advert-city-add-region').on('change', function () {
            $('#advert-city-add-city option.city-item').remove();
            $('#advert-city-add-city option.strict_value').hide();

            if($(this).val() != "" && $(this).val() != 'all' && $(this).val() != 'duplicate') {

	            SITE.location.getAdminCities($(this).val(), function (r) {
	                if(r.success) {
	            		$('#advert-city-add-city option.strict_value').show();
	                    $.each(r.data, function () {
	                    	$('#advert-city-add-city').append('<option class="city-item" value="' + this.id + '">' + this.nominative_local + '</option>');
	                    });
	                }
	            });

	        }
        });
    });
</script>