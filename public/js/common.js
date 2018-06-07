$(function () {

    $(document).ready(function () {

        function setLocation(city) {
            Cookies.set('city', city);
        }

        function showLocationModal(city_name = 'Москва', city_alias = 'moskva', country_id = 1) {
            var modal = $('.location-popup');

            modal.attr('data-city-id', city_alias);
            modal.find('p').text('Ваш город - ' + city_name + '?');

            $('.country-flag[href="'+ country_id +'"]').click();

            modal.show();
        }

        $('.location-popup .button-yes').on('click', function() {
            $(this).parent().hide();

            location.href = 'http://' + $(this).parent().data('city-id') + '.besplatnee.net';

            // setLocation($(this).parent().data('city-id'));
        });

        $('.location-popup .button-no').on('click', function() {
            $(this).parent().hide();
        });

        ymaps.ready(function () {

            var domain = location.href;

            if (domain.indexOf('http://besplatnee.net') > -1) {ј
                var geolocation = ymaps.geolocation;
                var coords = [];

                geolocation.get({
                    provider: 'yandex',
                    mapStateAutoApply: true
                }).then(function (result) {
                    coords = result.geoObjects.position;

                    SITE.location.checkCurrentLocation(coords[0], coords[1], function(res) {
                        showLocationModal(res.data[0].name, res.data[0].name, res.data[0].id);
                    });

                });

                geolocation.get({
                    provider: 'browser',
                    mapStateAutoApply: true
                }).then(function (result) {
                    coords = result.geoObjects.position;

                    SITE.location.checkCurrentLocation(coords[0], coords[1], function(res) {
                        showLocationModal(res.data[0].name, res.data[0].name, res.data[0].id);
                    });
                });
            }

        });


        


        /* Start post slider */
        $('.post-item-wrapper .owl-posts').owlCarousel({
            items: 1,
            nav: true,
            dots: false,
            navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>']
        });

        $('.ad-post-gallery').owlCarousel({
            items: 4,
            nav: true,
            dots: false,
            margin: 5,
            navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>']
        });

        $('.add-post-main-preview').magnificPopup({
            delegate: 'a',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
            }
        });

        $('.country-flags').owlCarousel({
            items: 21,
            nav: true,
            dots: false,
            navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>']
        });

        /*
         * Делаем модальное окно для видео
         */
        $('.post-item-video a, .video-banner').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
        });

        /*
         * Выравниваем блоки в версии Е 
         */
        var maxHeight = 0;
        $('.version-e .post-item-wrapper').each(function (i, item) {
            if(maxHeight < $(item).height()) {
                maxHeight = $(item).height();
            }

            if((i + 1) % 5 == 0) {
                for(var j = (i + 1) - 5; j < (i + 1); j++) {
                    $('.version-e .post-item-wrapper:eq(' + j + ') .post-item-content').height(maxHeight - 165);
                }

                maxHeight = 0;
            }
        });


        /* Author: Rosherh.
         * Делаем так, чтобы при клине на элемент из списка в нижнем меню выводились подменю по стилю (Аккардион).
         */
        let navBottomNavigation = function () {

            let index = null;
            const heightParent = $('#header .header-wrapper');
            const height = heightParent.height();

            $('#nav-bottom-navigation > li').on('click', function (e) {

                // Отключаем нашу обработку клика для пункта "Еще"
                if ($(this).find('> a').text() === $('#nav-bottom-navigation > li:last-child > a').text()) {
                    return true;
                }

                // Если у какого-то пункта нет подменю, по идеи это обычная ссылка, то переходим по ней
                if (!$(this).find('> ul').length > 0) {
                    $(this).removeClass('active');
                    return true;
                }

                if (index != $(this).index()) {
                    var el = $('#nav-bottom-navigation > li:eq(' + index + ')');
                    el.removeClass('active');

                    // Делаем анимацию активности
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                    } else {
                        $(this).addClass('active');
                    }

                    // Если меню активно, то увеличиваем высоту родителя блока, с учетом размера подменю
                    if ($(this).hasClass('active')) {
                        heightParent.removeAttr('style');
                        heightParent.height(height + $(this).find('> ul.sub-nav-bottom-navigation').height());
                    }

                    index = $(this).index();
                } else {
                    // Делаем анимацию активности
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                        heightParent.removeAttr('style');
                    } else {
                        $(this).addClass('active');
                        heightParent.height(height + $(this).find('> ul.sub-nav-bottom-navigation').height());
                    }
                }
                return false;
            });

            $('.sub-nav-bottom-navigation a').on('click', function (e) {
                location.href = $(this).attr('href');
                return false;
            });
        };
        /* Всю логику выносим в функцию, чтобы инкапсулировать переменные и предотвратить ошибки. */
        navBottomNavigation();

        /*$(window).on('scroll', function () {
            if($('body').hasClass('version-c')) { return ;}
            if($(window).scrollTop() > 322) {
                //$('#header').height($('#header').height());
                $('#content').css('margin-top', $('#header').height() + 'px');
                $('#header').addClass('header-fixable');
            } else {
                $('#header').removeClass('header-fixable');
                $('#content').css('margin-top', '0px');
            }
        });*/

        // работа с созданием объявления

        $(document).on('click', '.site-action', function (e) {
            SITE.adverts[$(this).attr('data-action')]($(this).parents('.post-item-wrapper').attr('data-id'));
            
            e.preventDefault();
            e.stopPropagation();
            return false;
        });

        $(document).on('change', 'select#categories', function (e) {
            if ($(this).val() != "") {
                var vip_ids = [1,2,3,4,5];
                var vip     = $('#vip');
                
                vip.empty();
                SITE.adverts.vip_accessibility($(this).val(), function(res) {
                    if(res.success) {
                        vip.empty();
                        vip.append('<option value="">Выберите номер VIP размещения объявления</option>');

                        $.each(vip_ids, function(index, val) {
                            if (res.data.indexOf(val) > -1) {
                                vip.append('<option value="' + val + '" disabled>' + val + '</option>');
                            } else {
                                vip.append('<option value="' + val + '">' + val + '</option>');
                            }
                        });
                    }
                });
            }
        });

        $(document).on('change', '#select_countries', function(e) {
            var val = $(this).val();
            var reg = $('#select_regions');

            if (val != '') {
                SITE.gobjects.getRegion(val, function(res) {
                    if(res.success) {
                        reg.empty();

                        reg.append('<option value=""></option>');

                        $.each(res.data, function(index, value) {
                            reg.append('<option value='+value.id+'>'+value.name+'</option>');
                        });
                    }
                });
            }
        });

        $(document).on('change', '.advert-queries-checkbox', function(e) {
            if ($('.advert-queries-checkbox:checked').length > 0) {
                $('.advert-queries-remove').show();
            } else {
                $('.advert-queries-remove').hide();
            }
        });

        $(document).on('click', '.advert-queries-remove', function(e) {

            $('.advert-queries-checkbox:checked').each(function () {
                var val = $(this).val();

                SITE.adverts.query_remove(val, function(res){
                    console.log(res);
                });
            });

            alert('Удалено');

            location.reload();
        });

        $(document).on('click', '.site-advert-field:not(.edited)', function (e) {
            var id = $(this).parents('.post-item-wrapper').attr('data-id');
            var field = $(this).attr('data-field');
            var type = $(this).attr('data-type');

            SITE.adverts.editfield(id, field, type);

            e.preventDefault();
            e.stopPropagation();
            return false;
        });


        $(document).on('click', '.country-flags .country-flag', function (e) {


            $(this).parents('.country-flags').find('.country-flag').removeClass('active');
            $(this).addClass('active');
            var country_id = $(this).attr('href');

            SITE.location.getFirstLetters(country_id, function (res) {
                if(res.success) {
                    $('.country-firstletters').trigger('destroy.owl.carousel').removeClass('owl-carousel owl-loaded');
                    $('.country-firstletters').html('');
                    $.each(res.data, function () {
                        $('.country-firstletters').append('<a class="country-firstletter" href="' + country_id + '">' + this.firstletter + '</a>')
                    });

                    $('.country-firstletters').owlCarousel({
                        items: 25,
                        nav: true,
                        dots: false,
                        navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>']
                    });

                    $('.country-firstletters').addClass('owl-carousel');

                    $('.country-firstletters .country-firstletter:eq(0)').trigger('click');
                }
            });

            e.stopPropagation();
            e.preventDefault();
        });

        $(document).on('click', '.country-firstletters .country-firstletter', function (e) {
            var country_id = $(this).attr('href');
            var firstletter = $(this).html();

            SITE.location.getCities(country_id, firstletter, function (res) {
                if(res.success) {
                    $('.country-cities').html('');
                    $.each(res.data, function () {
                        $('.country-cities').append('<div class="col-xs-3"><a class="country-city" href="http://' + this.alias + '.' + location.hostname.split('.').slice(-2).join('.') + '/">' + this.name + '</a></div>');
                    });
                }
            });

            e.stopPropagation();
            e.preventDefault();
        });


        $('.country-flags .country-flag.active').trigger('click');

        $('#options-category').on('change', function (e) {
            SITE.headings.getProperties($(this).val(), function (r) {
            	var $searchContain = $('#extended-search-block form');
            	$searchContain.html('');

                if(r.success) {
                	$.each(r.data, function (idx, property) {
                		if(property.type == 'select' || property.options.view == 'select' || property.type == 'checkbox' || property.type == 'radio') {
                            if(Object.keys(property.values).length > 0) {
                                var $property = SITE.properties.builder.make($searchContain, property);

                                $searchContain.append($property);
                            }
                		}
                	});

                    $('#search-engine-hidden-properties>*').each(function () {
                        $searchContain.find('[name="' + $(this).attr('name') + '"]').val($(this).val());
                        $searchContain.find('[name="' + $(this).attr('name') + '"]').trigger('change');
                    });

                    $('#extended-search-block').show();
                }
            });
        });

        $('#options-category').trigger('change');

        $(document).on('change', '.advert-property', function (e) {
            var $advertProperty = $(this);
            var $formContainer = $advertProperty.parents('form');
            var advertPropertyName = $(this).attr('data-name');
            var advertPropertyValue = $(this).val();

            if(advertPropertyValue == '') {
                $advertProperty.addClass('empty');
            } else {
                $advertProperty.removeClass('empty');
            }
            
            $formContainer.find('.advert-property[data-parent="' + advertPropertyName + '"]').each(function () {
                var $children = $(this);
                var $childrenContainer = $children.hasClass('property-container') ? $children : $children.parents('.property-container');

                if($children.is('select')) {
                    var visibleCount = 0;

                    $children.find('option[data-parent-values]').hide().each(function () {
                        var $option = $(this);
                        var parentValues = $option.attr('data-parent-values') == '' ? [] : $option.attr('data-parent-values').split(',');

                        if(parentValues.length == 0 || parentValues.indexOf(advertPropertyValue) >= 0) {
                            visibleCount++;
                            $option.show();
                        }
                    });

                    if(visibleCount == 0) {
                        $childrenContainer.hide();
                        $children.val('');
                    } else {
                        $childrenContainer.show();
                        $children.val($children.attr('data-default'));
                    }

                } else {
                     var parentValues = $children.attr('data-parent-values') == '' ? [] : $children.attr('data-parent-values').split(',');
                     if(parentValues.length == 0 || parentValues.indexOf(advertPropertyValue) >= 0) {
                        $childrenContainer.show();
                        $children.val($children.attr('data-default'));
                     } else {
                        $childrenContainer.hide();
                     }
                }
                
                $children.trigger('change');
            });
        });

        $(document).on('click', '#search-engine-go', function () {
            $('#search-engine-hidden-properties').html('');

            $('#extended-search-block form>*').each(function () {
                var name = $(this).attr('name');
                if($('#search-engine-hidden-properties [name="' + name + '"]').length == 0) {
                    $('#search-engine-hidden-properties').append('<input type="hidden" name="' + name + '">');
                }

                $('#search-engine-hidden-properties [name="' + name + '"]').val($(this).val());

            });
        });

        SITE.ui.dropdown.make('.dropdown-list');

        $('.banner-wrapper .block-hide-button').on('click', function () {
            $(this).parent().hide();
        });

    });

})
;
