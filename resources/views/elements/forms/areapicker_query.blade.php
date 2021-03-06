<?php
	$config = [
		'countries' => collect($allowed['countries'] ?? []),
		'regions' => collect($allowed['regions'] ?? []),
		'cities' => collect($allowed['cities'] ?? []),
		'names' => array_merge([
			'all' => 'duplicate_in_all_cities',
			'countries' => 'country_ids.*',
			'regions' => 'region_ids.*',
			'cities' => 'city_ids.*',
		], $names ?? []),
		'titles' => array_merge([
			'main' => 'admin.citypicker',
			'all' => 'admin.duplicate_in_all_cities',
			'country' => 'admin.in_country',
			'region' => 'admin.in_region',
			'city' => 'admin.in_city',
		], $titles ?? []),
		'controlls' => [
			'all' => $controlls['all'] ?? true,
			'country' => $controlls['country'] ?? true,
			'region' => $controlls['region'] ?? true,
			'city' => $controlls['city'] ?? true,
			'current' => [
				'country' => $controlls['current_country'] ?? true,
				'region' => $controlls['current_region'] ?? true,
				'city' => $controlls['current_city'] ?? true,
			],
		],
	];

	if($config['cities']->isNotEmpty()) {
		$config['regions']->merge(\App\City::whereIn('id', $config['cities'])->pluck('region_id'));
	}


	if($config['regions']->isNotEmpty()) {
		$config['countries']->merge(\App\City::whereIn('id', $config['regions'])->pluck('country_id'));
	} else {
		$config['countries'] = \App\Country::pluck('id');
	}
	//dd(request()->all());
?>
<div class="create-ad-row">
	<div class="row">
		<div class="col-xs-4">
			<div class="create-ad-title">{{ $config['titles']['main'] }}</div>
		</div>
		<div class="col-xs-8 advert-city-list">
			
			@foreach(request()->input($config['names']['cities'], []) as $city_id)
				@if($city_id != config('area')->id || $city_id == config('area')->id && !$config['controlls']['current']['city'])
					@if($city = \App\City::find($city_id))
						<label class="col-xs-3 checkbox-label">
			    			<input type="checkbox" name="{{ point_to_bracket($config['names']['cities']) }}" value="{{ $city_id }}" checked>
			    			{{ $city->country->getName() }}, {{ $city->region->getName() }}, {{ $city->getName() }}
						</label>
					@endif
				@endif
			@endforeach
		</div>

        <div class="city-add col-xs-offset-4 col-xs-8" style="padding-left: 15px; padding-top: 24px">
            <label>Добавить город:</label><br>
            <select id="advert-city-add-country">
                <option value="" checked>Страна</option>
            	@foreach(\App\Country::whereIn('id', $config['countries'])->get() as $country) 
                <option value="{{ $country->id }}">{{ $country->getName() }}</option>
            	@endforeach
            </select>

            <select id="advert-city-add-region">
                <option value="" checked>Регион</option>
            </select>

            <select id="advert-city-add-city">
                <option value="" checked>Город</option>
            </select>

            <button id="advert-city-add-push">+</button>
        </div>

        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                $('#advert-city-add-country').on('change', function () {
                    SITE.location.getRegions($(this).val(), function (r) {
                        if(r.success) {
                			var allowedRegions = {!! $config['regions']->toJson() !!}

                            $('#advert-city-add-region option.region-item').remove();
                            $.each(r.data, function () {
                            	if(!allowedRegions.length || allowedRegions.indexOf(this.id) >= 0) {
                                	$('#advert-city-add-region').append('<option class="region-item" value="' + this.id + '">' + this.nominative_local + '</option>');
                                }
                            });
                        }
                    });
                });

                $('#advert-city-add-region').on('change', function () {
                    SITE.location.getCitiesByRegion($(this).val(), function (r) {
                        if(r.success) {
                			var allowedCities = {!! $config['cities']->toJson() !!}

                            $('#advert-city-add-city option.city-item').remove();
                            $.each(r.data, function () {
                            	if(!allowedCities.length || allowedCities.indexOf(this.id) >= 0) {
                                	$('#advert-city-add-city').append('<option class="city-item" value="' + this.id + '">' + this.nominative_local + '</option>');
                                }
                            });
                        }
                    });
                });

                $('#advert-city-add-push').on('click', function () {
                	var country_id = $('#advert-city-add-country').val();
                	var region_id = $('#advert-city-add-region').val();
                    var city_id = $('#advert-city-add-city').val();

                    if(city_id != '') {  

	                    if($('[type="checkbox"][name="{{ point_to_bracket($config['names']['cities']) }}"][value="' + city_id + '"]').length == 0) {
	                        $('.advert-city-list').append($('<label class="col-xs-3 checkbox-label">' +
	                            '<input type="checkbox" name="{{ point_to_bracket($config['names']['cities']) }}" value="' + city_id + '" checked>' +
	                            $('#advert-city-add-country option:selected').html() + ', ' +
	                            $('#advert-city-add-region option:selected').html() + ', ' +
	                            $('#advert-city-add-city option:selected').html() +
	                        '</label>'));
	                    }

	                } else {
	                	alert('Пожалуйста, выберите город!');
	                }

                    return false;
                });
            });
        </script>
	</div>
</div>