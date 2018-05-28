$(function () { 
	$(document).ready(function () {

	    window.yandexMapSetCoords = function (map, coords) {
	        map.panTo(coords, {duration: 1000});

	        window.yandexMapPlacemark = new ymaps.Placemark(coords, {}, {
	            preset: 'islands#nightCircleDotIcon'
	        });

	        map.geoObjects.removeAll();
	        map.geoObjects.add(window.yandexMapPlacemark);
	    }

		window.yandexMapGeoCode = function (map, coords, callback) {
	        var myReverseGeocoder = ymaps.geocode(coords);

	        myReverseGeocoder.then(
	            function (res) {
	                var address = res.geoObjects.get(0).properties.get(0).text;
	                callback(address);
	            },
	            function (err) {
	                
	            }
	        );
	    }

	    window.SITE = {
	        InitUploadFiles: function (callbacks, url, type, multiple) {
	            if(multiple === undefined) { multiple = true; }

	            var inputId = 'file-uploader-tmp-input-besplatnee-net';

	            if($('#' + inputId).length == 0) {
	                $('body').append('<input type="file" ' + (multiple ? 'multiple' : '') + ' id="' + inputId + '" style="opacity: 0; position: absolute; bottom: 0; left: 0; width: 0; height: 0;">');
	                $('#' + inputId).on('change', function (e) {
	                    window.SITE.uploadFiles(this, callbacks, url, type, multiple);
	                });
	            }

	            $('#' + inputId).trigger('click');
	        },
	        uploadFiles: function (el, callbacks, url, type, multiple) {
	            var formData = new FormData();

	            formData.append('filetype', type);
	        
	            $.each(el.files, function (i, f) {
	                formData.append('uploadfiles[]', f, f.name);
	            });

	            callbacks.start();

	            $.ajax({
	                headers: {
	                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	                },
	                url: url,
	                data: formData,
	                dataType: 'json',
	                async: true,
	                type: 'post',
	                cache: false,
	                processData: false,
	                contentType: false,
	                success: function(res){
	                    var inputId = 'file-uploader-tmp-input-besplatnee-net';
	                    if(res.success) {
	                        $.each(res.data, function (i, filename) {
	                            callbacks.success(filename);
	                        });
	                    } else {
	                    	callbacks.failure(res.errors);
	                    }

	                    $('#' + inputId).remove();
	                },
	                error: function (XMLHttpRequest, textStatus, errorThrown) {
	                    var inputId = 'file-uploader-tmp-input-besplatnee-net';
	                    $('#' + inputId).remove();
	                    callbacks.failure();
	                },
	                xhr: function() {
		                var myXhr = $.ajaxSettings.xhr();

		                if(myXhr.upload){
		                    myXhr.upload.addEventListener('progress', function (e) {
		                    	if(e.lengthComputable){
							        var state = (e.loaded * 100) / e.total;

							        callbacks.progress(state);
							    }  
		                    }, false);
		                }

		                return myXhr;
			        }
	            });
	        },
	        request: function (data, callback) {
	            $.ajax({
	                headers: {
	                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	                },
	                url: '/ajax',
	                method: 'post',
	                data: data,
	                cache: false,
	                dataType: 'json',
	                success:function (res) {
	                    callback(res);
	                },
	                error: function (XMLHttpRequest, textStatus, errorThrown) {
	                    console.log(XMLHttpRequest.responseText);
	                    callback({success: false});
	                }
	            });
	        },
	        template: function (name, data) {
	            var template = $('template[name="' + name + '"]').html();

	            $.each(data, function (key, value) {
	                template = template.replace(new RegExp('#' + key.toUpperCase() + '#', 'g'), value);
	            });

	            return $(template);
	        },
	        adverts: {
	            action: function (id, action, callback, data) {
	                data = data || {};
	                data.id = id;
	                data.action = 'advert_' + action;

	                SITE.request(data, callback);
	            },
	            follow: function (id) {
	                SITE.modal.checkSolution('Добавить в избранное?', function () {
	                    SITE.adverts.action(id, 'follow', function () {
	                        SITE.modal.notifycation('Объявление успешно добавленно в избранное');
	                    });
	                });
	            },
	            hide: function (id) {
	                SITE.modal.checkSolution('Скрыть это объявление?', function () {
	                    SITE.adverts.action(id, 'hide', function () {
	                        //SITE.modal.notifycation('Теперь это объявление скрыто для вас');
	                    });

	                    $('.post-item-wrapper[data-id="' + id + '"]').fadeOut(300, function () {
	                        $('.post-item-wrapper[data-id="' + id + '"]').remove();
	                    });
	                });
	            },
	            remove: function (id) {
	                SITE.modal.checkSolution('Удалить из газеты?', function () {
	                    SITE.adverts.action(id, 'remove', function () {
	                        SITE.modal.notifycation('Заявка на удаление объявления успешно составлена');
	                    });
	                });
	            },
	            commenting: function (id) {
	                var $comment = $('.post-item-wrapper[data-id="' + id + '"] .post-item-comment');
	                if($comment.find('textarea').length == 0) {
	                    var text = $comment.html();
	                    $comment.html('<textarea rows="6">' + text + '</textarea>');
	                }
	                
	                $comment.find('textarea').focus();
	                $comment.find('textarea').on('blur', function () {
	                    $comment.html($comment.find('textarea').val());
	                    SITE.adverts.action(id, 'commenting', function (res) {

	                    }, {
	                        comment: $comment.html()
	                    });
	                });
	            },
	            editfield: function (id, field, type) {
	                var $contain = $('.post-item-wrapper[data-id="' + id + '"] [data-field="' + field + '"]');
	                $contain.addClass('edited');
	                var oldtext = '';
	                if($contain.find(type).length == 0) {
	                    var text = $contain.html();
	                    if(field == 'price') {
	                        text = text.replace(' ', '');
	                    }
	                    oldtext = text;
	                    switch(type) {
	                        case 'input': $contain.html('<input type="text" value="' + text + '">'); break;
	                        case 'textarea': $contain.html('<textarea rows="6">' + text + '</textarea>'); break;
	                        default: return ;
	                    }
	                } else {
	                    oldtext = $contain.find(type).val();
	                }

	                $contain.find(type).focus();

	                function onChange(e) {
	                    $contain.html($contain.find(type).val());
	                    SITE.modal.checkSolution('Сохранить изменения?', function () {
	                        SITE.adverts.action(id, 'editfield', function (res) {
	                            if(!res.success) {
	                                $contain.html(oldtext);
	                            }
	                        }, {
	                            value: $contain.html(),
	                            field: field
	                        });
	                    });

	                    $contain.removeClass('edited');

	                    e.stopPropagation();
	                }

	                $contain.find(type).on('blur', onChange);

	                $contain.find('input').on('keypress', function (e) {
	                    if(e.which == 13) {
	                        $contain.find(type).blur();
	                        return false;
	                    }

	                });
	            },
	            query_remove: function(id, callback) {
	            	data = {};
	            	if (id != '') {
	            		data.id = id;
	            		data.action = 'advert_query_remove';
	            		SITE.request(data, callback);
	            	}
	            },
	            vip_accessibility: function(heading, callback, city = null) {
	            	data = {};
	            	if (city != null) {
	            		data.city 		= city;
	            	}
	            	if (heading != '') {
	            		data.heading 	= heading;
	            		data.action 	= 'advert_vip_accessibility';
	            		SITE.request(data, callback);
	            	}
	            },
	        },
	        banners: {
	            click: function (id) {
	                data = {
	                    id: id,
	                    action: 'banner_click'
	                };

	                SITE.request(data, function () {});
	            },
	            hide: function (id) {
	                data = {
	                    id: id,
	                    action: 'banner_hide'
	                };
	                
	                SITE.modal.checkSolution('Скрыть это объявление?', function () {
	                    SITE.request(data, function () {
	                        $('.right-sidebar-banner-wrapper a[data-id="' + id + '"],.left-sidebar-banner-wrapper a[data-id="' + id + '"]').fadeOut(300, function () {
	                        	$('.right-sidebar-banner-wrapper a[data-id="' + id + '"],.left-sidebar-banner-wrapper a[data-id="' + id + '"]').show().addClass('hidden');
	                        });
	                    });
	                });

	                return false;
	            },
	            show: function (id) {
	                data = {
	                    id: id,
	                    action: 'banner_show'
	                };
	                
                    SITE.request(data, function () {
                        $('.right-sidebar-banner-wrapper a[data-id="' + id + '"],.left-sidebar-banner-wrapper a[data-id="' + id + '"]').removeClass('hidden');
                    });

	                return false;
	            }
	        },
	        headings: {
	            getProperties: function (id, callback) {
	                SITE.request({
	                    'action': 'headings_getproperties',
	                    'id': id
	                }, callback);
	            }
	        },
	        modal: {
	            exec: function (title, body, buttons, attributes) {
	                var settings = $.extend({
	                    id: 'modal-' +Math.round(Math.random() * 10000),
	                    class: 'modal fade',
	                    tabindex: -1,
	                    role: 'dialog',
	                    'aria-labelledby': 'exampleModalLabel',
	                    'aria-hidden': true,
	                    title: title,
	                    body: body,
	                    modalClass: ''
	                }, attributes);

	                var attributes = '';
	                for(var key in settings) {
	                    attributes += key + '="' + settings[key] + '" ';
	                }

	                var htmlButtons = '';
	                for(var title in buttons) {
	                    var type = buttons[title].type || 'secondary';
	                    var action = buttons[title].action || '';

	                    if(action == 'close') {
	                        action = 'data-dismiss="modal"';
	                    } else {
	                        if(typeof action == 'function') {
	                            var id = settings.id + '-button-' + Math.round(Math.random() * 10000);
	                            callback = action;
	                            action = 'id="' + id + '"';
	                            $(document).on('click', '#' + id, function(e) {
	                                callback.call();
	                                e.stopPropagation();
	                                e.preventDefault();
	                            });
	                        } else {
	                            action = 'onclick="' + action + ';event.stopPropagation();event.preventDefault();"'
	                        }
	                    }

	                    htmlButtons += '<button type="button" class="btn btn-' + type + '" ' + action + '>' + title + '</button>';
	                }

	                var $modal = $('<div ' + attributes + '>' +
	                    '<div class="modal-dialog ' + settings.modalClass + '" role="document">' +
	                        '<div class="modal-content">' +
	                            '<div class="modal-header">' +
	                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
	                                    '<span aria-hidden="true">&times;</span>' +
	                                '</button>' +
	                                '<h5 class="modal-title" id="' + settings['aria-labelledby'] + '">' + settings.title + '</h5>' +
	                            '</div>' +
	                            '<div class="modal-body">' + settings.body + '</div>' +
	                            '<div class="modal-footer">' + htmlButtons + '</div>' +
	                        '</div>' +
	                    '</div>' +
	                '</div>');

	                $('body').append($modal);
	                $modal.modal('show');
	                $modal.on('hidden.bs.modal', function (e) {
	                    $(this).remove();
	                });

	                return $modal;
	            },
	            checkSolution: function (description, callback) {
	                $modal = this.exec('', '<center>' + description + '</center>', {
	                    'Да': {
	                        type: 'primary',
	                        action: function () {
	                            if(callback.call() !== false) {
	                                $modal.modal('hide');
	                            }
	                        }
	                    },
	                    'Нет': {
	                        type: 'secondary',
	                        action: 'close',
	                    }
	                }, {
	                    modalClass: 'modal-sm'
	                });

	                return $modal;
	            },
	            notifycation: function (text) {
	                $modal = this.exec('', '<center>' + text + '</center>', {
	                    'Ok': {
	                        type: 'primary',
	                        action: 'close'
	                    }
	                }, {
	                    modalClass: 'modal-sm'
	                });

	                return $modal;
	            }
	        },
	        location: {
	            getCities: function (country_id, firstletter, callback) {
	                SITE.request({
	                    action: 'country_getcities',
	                    id: country_id,
	                    firstletter: firstletter
	                }, callback);
	            },
	            getRegions: function (country_id, callback) {
	                SITE.request({
	                    action: 'country_getregions',
	                    id: country_id
	                }, callback);
	            },
	            getCitiesByRegion: function (region_id, callback) {
	                SITE.request({
	                    action: 'country_getcitiesbyregion',
	                    id: region_id
	                }, callback);
	            },
	            getFirstLetters: function (country_id, callback) {
	                SITE.request({
	                    action: 'country_getfirstletters',
	                    id: country_id
	                }, callback);
	            },
	            setLanguage: function (id) {
	                SITE.request({
	                    action: 'language_set',
	                    id: id
	                }, function (r) {
	                    location.reload();
	                });
	            },
	            getAdminRegions: function (country_id, callback) {
	                SITE.request({
	                    action: 'admin_get_access_regions',
	                    id: country_id
	                }, callback);
	            },
	            getAdminCities: function (region_id, callback) {
	                SITE.request({
	                    action: 'admin_get_access_cities',
	                    id: region_id
	                }, callback);
	            },
	            checkCurrentLocation: function (latitude, longitude, callback) {
	            	SITE.request({
	                    action: 'location_check_entry',
	                    long: longitude,
	                    lat: latitude
	                }, callback);
	            }
	        },
	        language: {
	        	get: function (key) {
	        		return key;
	        	}
	        },
	        properties: {
	        	builder: {
	        		make: function ($contain, p, template) {
	                    var $p = this[p.type](p);
	                    var defaultValue = p.default == null || p.default == 0 ? "" : p.default;

	                    $p.addClass('advert-property');

	                    $p.val(defaultValue);
	                    $p.attr('data-default', defaultValue);
	                    $p.attr('data-name', p.name);

	                    $p.attr('data-parent', p.parent_name);

	                    if($p.val() == '') {
	                        $p.addClass('empty');
	                    }

	                    if(template) {
	                        $p = SITE.template(template, {
	                            property_title: p.title,
	                            property_input: $p.wrap('<div/>').parent().html(),
	                            property_description: p.description,
	                            property_error: ''
	                        });
	                    }

	                    $p.addClass('property-container');

	                    if(p.parent_id > 0) {
	                        $p.hide();
	                    }

	        			return $p;
	        		},
	        		text: function (p) {

	        		},
	        		textarea: function (p) {
	        			var $p = $('<textarea/>');
	        			$p.attr('name', this.getName(p));
	        			$p.attr('placeholder', p.title);
	        			$p.val(p.default);

	        			return $p;
	        		},
	        		select: function (p) {
	        			var $p = $('<select/>');

	        			$p.attr('name', this.getName(p));

	        			$p.append('<option class="placeholder" value="">' + p.title + '</option>');

	        			$.each(p.values, function (i, val) {
	        				var $val = $('<option/>');
	        				$val.attr('value', val.value);
	        				$val.html(val.title);

	                        var parents = [];
	                        $.each(val.parents, function (j, parent) {
	                            parents.push(parent.value);
	                        });

	                        if(parents.length > 0) {
	        					$val.attr('data-parent-values', parents.join(','));
	        				}

	        				$p.append($val);
	        			});

	        			return $p;
	        		},
	        		numeric: function (p) {
	        			return this['numeric_' + p.options.view](p);
	        		},
	        		numeric_input: function (p) {
	        			var $p = $('<input/>');
	        			$p.attr('type', 'numeric')
	        			$p.attr('name', this.getName(p));
	        			$p.attr('placeholder', p.title);

	                    values = [];
	                    $.each(p.values, function (i, value) {
	                        $.each(value.parents, function (j, parent) {
	                            values.push(parent.value);
	                        });
	                    });

	                    $p.attr('data-parent-values', values.join(','));

	                    console.log(p);
	        			$p.val(p.default);

	        			return $p;
	        		},
	        		numeric_select: function (p) {
	        			return this.select(p);
	        		},
	        		getName: function (p, prefix) {
	        			prefix = prefix || 'properties';
	        			return prefix + '[' + p.name + ']';
	        		}
	        	},
	        	get: function (name) {

	        	},
	        	getById: function (id) {

	        	}
	        },
	        gobjects: {
	        	getRegion: function (parent_id, callback) {
	        		data = {};
	        		if (parent_id != '') {
	        			data.parent_id = parent_id;
	        			data.action = 'geoobjects_getbyparentid';
	        			SITE.request(data, callback);
	        		}
	        	}
	        },
	        ui: {
	            dropdown: {
	                make: function (element) {
	                    $(element).each(function (i, dropdown) {
	                        var defaultValue = $(this).attr('data-default');
	                        var name = $(this).attr('data-name');

	                        var $input = $('<input/>').attr({
	                            type: 'hidden',
	                            name: name
	                        }).appendTo(this);

	                        var $viewport = $('<div/>').addClass('dropdown-viewport').appendTo(this);
	                        var $items = $('<div/>').addClass('dropdown-items').appendTo(this);

	                        $(this).find('.dropdown-item').each(function () {
	                            var value = $(this).attr('data-value');
	                            var title = $(this).html();
	                            var image = $(this).attr('data-image');

	                            if(value == defaultValue) {
	                                SITE.ui.dropdown.setValue(dropdown, value);
	                            }

	                            $(this).appendTo($items);

	                            if($(this).is('[data-image]')) {
	                                $(this).addClass('dropdown-item-image');
	                                $(this).css('background-image', 'url(' + image + ')');
	                            }

	                            $(this).on('click', function (e) {
	                                SITE.ui.dropdown.setValue(dropdown, $(this).attr('data-value'));

	                                $items.hide();

	                                e.stopPropagation();
	                                e.preventDefault();
	                            });
	                        });

	                        $(this).on('click', function () {
	                            $(this).find('.dropdown-items').toggle();
	                        });

	                        SITE.ui.dropdown.setValue(dropdown, $(this).find('[data-checked]').attr('data-value'));
	                    });
	                },
	                setValue: function (item, value) {
	                    var $input = $(item).find('input');
	                    var $viewport = $(item).find('.dropdown-viewport');

	                    var $value = $(item).find('.dropdown-item[data-value="' + value + '"]').removeAttr('data-checked').eq(0);
	                    $input.val(value);
	                    $viewport.html($value.html());

	                    if($value.is('[data-image]')) {
	                        $viewport.css('background-image', 'url(' + $value.attr('data-image') + ')');
	                        $viewport.addClass('dropdown-item-image');
	                    }

	                    $value.attr('data-checked', '');

	                    $(item).trigger('change');
	                }
	            }
	        }
	    };

	});
});