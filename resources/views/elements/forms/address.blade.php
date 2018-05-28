<div class="create-ad-row">
	<div class="row">
        <div class="col-xs-4">
            <div class="create-ad-title">{{ $title }}</div>
        </div>
        <div class="col-xs-8">
            <p class="col-xs-12">{{ $desc }}</p>
            <div class="error-message">{{ $errors->first($inputname) }}</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <input class="col-xs-9" type="text" name="{{ $inputname }}" placeholder="{{ $help }}" value="{{ request()->input($inputname) }}">

            <input type="hidden" name="{{ $longname }}" value="{{ request()->input($longname, \Config::get('area')->longitude) }}">
            <input type="hidden" name="{{ $latname }}" value="{{ request()->input($latname, \Config::get('area')->latitude) }}">

            <button class="col-xs-3 ymap-search">{{ $findtitle }}</button>
        </div>
    </div>
	<div class="row">
        <div class="col-xs-12">
            
            <script>
            	window.ymaps || 
            	document.write('<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU">\x3C/script>');
            </script>
            <div class="col-xs-12">
                <div id="{{ $id }}" style="margin-top: 24px; width: 100%; height: 280px"></div>
            </div>
            <script type="text/javascript">
                ymaps.ready(init);
                window.{{ $id }};

                function init(){     
                    window.{{ $id }} = new ymaps.Map("{{ $id }}", {
                        center: [{{ request()->input($latname, \Config::get('area')->latitude) }}, {{ request()->input($longname, \Config::get('area')->longitude) }}],
                        zoom: 12
                    });

			        $('#{{ $id }}').parents('.create-ad-row').find('[name="{{ $inputname }}"]').on('keydown', function(e) {
			            if (e.which == 13) {
			                $(this).parent().find('button').trigger('click');
			            
			                e.preventDefault();
			            }
			        });

			        $('#{{ $id }}').parents('.create-ad-row').find('button.ymap-search').on('click', function (e) {
			            var myGeocoder = ymaps.geocode($('#{{ $id }}').parents('.create-ad-row').find('[name="{{ $inputname }}"]').val());

			            myGeocoder.then(
			                function (res) {
			                    var coords = res.geoObjects.get(0).geometry.getCoordinates();
			                    
			                    window.yandexMapSetCoords({{ $id }}, coords);
            					$('#{{ $id }}').parents('.create-ad-row').find('[name="{{ $latname }}"]').val(coords[0]);
            					$('#{{ $id }}').parents('.create-ad-row').find('[name="{{ $longname }}"]').val(coords[1]);
			                },
			                function (err) {
			                    
			                }
			            );

			            e.stopPropagation();
			            e.preventDefault();
			        });

                    window.{{ $id }}.events.add('click', function (e) {
                        var coords = e.get('coords');
                        window.yandexMapSetCoords({{ $id }}, coords);
                        window.yandexMapGeoCode({{ $id }}, coords, function (address) {
                        	$('#{{ $id }}').parents('.create-ad-row').find('[name="{{ $inputname }}"]').val(address);
                        });

            			$('#{{ $id }}').parents('.create-ad-row').find('[name="{{ $latname }}"]').val(coords[0]);
            			$('#{{ $id }}').parents('.create-ad-row').find('[name="{{ $longname }}"]').val(coords[1]);
                    });

                    window.yandexMapSetCoords({{ $id }}, [{{ request()->input($latname, \Config::get('area')->latitude) }},{{ request()->input($longname, \Config::get('area')->longitude) }}]);

        			$('#{{ $id }}').parents('.create-ad-row').find('[name="{{ $latname }}"]').val(coords[0]);
        			$('#{{ $id }}').parents('.create-ad-row').find('[name="{{ $longname }}"]').val(coords[1]);
                    window.yandexMapGeoCode({{ $id }}, [{{ request()->input($latname, \Config::get('area')->latitude) }},{{ request()->input($longname, \Config::get('area')->longitude) }}]);

                    myMap.events.add('actiontick', function(e) {
                        var action = e.get('action');

                        

                        /*if(action._actionManager._zoom == 14) {*/
                            /*if(action._actionManager._globalPixelCenter[0] < 2450068.73)
                                action._actionManager._globalPixelCenter[0] = 2450068.73;
                            }*/
                        /*}*/
                    });
                }
            </script>
        </div>
    </div>
</div>