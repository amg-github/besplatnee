<?php 

return [
	// Транспорт
	[
		'name' => 'headings.transport',
		'aliases' => [
			'ru' => [
				[
					'local' => 'продажа_транспорта',
					'international' => 'prodaja_transporta',
					'property_id' => null,
					'property_value' => null,
				],
			],
			'en' => [
				[
					'local' => 'продажа_транспорта',
					'international' => 'prodaja_transporta',
					'property_id' => null,
					'property_value' => null,
				],
			],
		],
		'show_in_menu' => true,
		'properties' => [],
		'childrens' => [
			// автомобили
			[
				'name' => 'headings.auto',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_автомобилей',
							'international' => 'prodaja_avtomobiley',
							'property_id' => null,
							'property_value' => null,
						],
					],
					'en' => [
						[
							'local' => 'продажа_автомобилей',
							'international' => 'prodaja_avtomobiley',
							'property_id' => null,
							'property_value' => null,
						],
					],
				],
				'show_in_menu' => false,
				'properties' => ['model','series','auto_type','mileage','year_of_issue','transmission','fuel_type'],
				'childrens' => [
				],
			],
			// Мотоциклы и мототехника
			[
				'name' => 'headings.moto',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_мототехники',
							'international' => 'prodaja_mototehniki',
							'property_id' => null,
							'property_value' => null,
						],
					],
					'en' => [
						[
							'local' => 'продажа_мототехники',
							'international' => 'prodaja_mototehniki',
							'property_id' => null,
							'property_value' => null,
						],
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_moto'],
				'childrens' => [
				],
			],
			// Грузовики и спецтехника
			[
				'name' => 'headings.trucks',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_грузовиков',
							'international' => 'prodaja_gruzovikov',
							'property_id' => null,
							'property_value' => null,
						],
					],
					'en' => [
						[
							'local' => 'продажа_грузовиков',
							'international' => 'prodaja_gruzovikov',
							'property_id' => null,
							'property_value' => null,
						],
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_truck'],
				'childrens' => [
				],
			],
			// Водный транспорт
			[
				'name' => 'headings.water_transport',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_водного_транспорта',
							'international' => 'prodaja_vodnogo_transporta',
							'property_id' => null,
							'property_value' => null,
						],
					],
					'en' => [
						[
							'local' => 'продажа_водного_транспорта',
							'international' => 'prodaja_vodnogo_transporta',
							'property_id' => null,
							'property_value' => null,
						],
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_water_transport'],
				'childrens' => [
				],
			],
			// Запчасти и аксессуары
			[
				'name' => 'headings.spare_parts',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_запчастей',
							'international' => 'prodaja_zapchastey',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'продажа_запчастей',
							'international' => 'prodaja_zapchastey',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['spare_part_type'],
				'childrens' => [
				],
			],
		],
	],

	// Для дома и дачи
	[
		'name' => 'headings.for_home_and_cottages',
		'aliases' => [
			'ru' => [
				[
					'local' => 'для_дома_и_дачи',
					'international' => 'dlya_doma_i_dachi',
					'property_id' => null,
					'property_value' => null,
				]
			],
			'en' => [
				[
					'local' => 'для_дома_и_дачи',
					'international' => 'dlya_doma_i_dachi',
					'property_id' => null,
					'property_value' => null,
				]
			],
		],
		'show_in_menu' => false,
		'properties' => [],
		'childrens' => [
			// Бытовая техника
			[
				'name' => 'headings.appliances',
				'aliases' => [
					'ru' => [
						[
							'local' => 'бытовая_техника',
							'international' => 'bitovaya_technika',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'бытовая_техника',
							'international' => 'bitovaya_technika',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
				],
			],
			// Мебель и интерьер
			[
				'name' => 'headings.furniture_and_interior',
				'aliases' => [
					'ru' => [
						[
							'local' => 'мебель_и_интерьер',
							'international' => 'mebel_i_interier',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'мебель_и_интерьер',
							'international' => 'mebel_i_interier',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
				],
			],
			// Посуда и товары для кухни
			[
				'name' => 'headings.kitchenware_and_goods',
				'aliases' => [
					'ru' => [
						[
							'local' => 'посуда_и_товары_для_кухни',
							'international' => 'posuda_i_tovari_dlya_kuhni',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'посуда_и_товары_для_кухни',
							'international' => 'posuda_i_tovari_dlya_kuhni',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
				],
			],
			// Продукты питания
			[
				'name' => 'headings.food',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продукты_питания',
							'international' => 'producti_pitaniya',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'продукты_питания',
							'international' => 'producti_pitaniya',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
				],
			],
			// Ремонт и строительство
			[
				'name' => 'headings.repair_and_construction',
				'aliases' => [
					'ru' => [
						[
							'local' => 'ремонт_и_строительство',
							'international' => 'remont_i_stroitelstvo',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'ремонт_и_строительство',
							'international' => 'remont_i_stroitelstvo',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
				],
			],
			// Растения
			[
				'name' => 'headings.plants',
				'aliases' => [
					'ru' => [
						[
							'local' => 'растения',
							'international' => 'rasteniya',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'растения',
							'international' => 'rasteniya',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
				],
			],
		],
	],

	// Для бизнеса
	[
		'name' => 'headings.for_business',
		'aliases' => [
			'ru' => [
				[
					'local' => 'для_бизнеса',
					'international' => 'dlya_biznesa',
					'property_id' => null,
					'property_value' => null,
				]
			],
			'en' => [
				[
					'local' => 'для_бизнеса',
					'international' => 'dlya_biznesa',
					'property_id' => null,
					'property_value' => null,
				]
			],
		],
		'show_in_menu' => false,
		'properties' => [],
		'childrens' => [
			// Готовый бизнес
			[
				'name' => 'headings.ready_business',
				'aliases' => [
					'ru' => [
						[
							'local' => 'готовый_бизнес',
							'international' => 'gotoviy_biznes',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'готовый_бизнес',
							'international' => 'gotoviy_biznes',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
				],
			],
			// Оборудование для бизнеса
			[
				'name' => 'headings.equipment_for_business',
				'aliases' => [
					'ru' => [
						[
							'local' => 'оборудование_для_бизнеса',
							'international' => 'oborudovanie_dlya_biznesa',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'оборудование_для_бизнеса',
							'international' => 'oborudovanie_dlya_biznesa',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
				],
			],
		],
	],

	// Недвижимость
	[
		'name' => 'headings.real_estate',
		'aliases' => [
			'ru' => [
				[
					'local' => 'продажа_недвижимости',
					'international' => 'prodaja_nedvijimosti',
					'property_id' => null,
					'property_value' => null,
				]
			],
			'en' => [
				[
					'local' => 'продажа_недвижимости',
					'international' => 'prodaja_nedvijimosti',
					'property_id' => null,
					'property_value' => null,
				]
			],
		],
		'show_in_menu' => false,
		'properties' => [],
		'childrens' => [
			// Квартиры
			[
				'name' => 'headings.apartments',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_квартир',
							'international' => 'prodaja_kvartir',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'продажа_квартир',
							'international' => 'prodaja_kvartir',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_real_estate','type_of_apartments','type_of_room','number_of_rooms'],
				'childrens' => [
				],
			],
			// Комнаты
			[
				'name' => 'headings.rooms',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_комнат',
							'international' => 'prodaja_komnat',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'продажа_комнат',
							'international' => 'prodaja_komnat',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_real_estate','type_of_apartments','type_of_room','number_of_rooms'],
				'childrens' => [
				],
			],
			// Дома, дачи, коттеджи
			[
				'name' => 'headings.houses_cottages_cottages',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_домов_дач_коттеджей',
							'international' => 'prodaja_domov_dach_kottedjey',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'продажа_домов_дач_коттеджей',
							'international' => 'prodaja_domov_dach_kottedjey',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_real_estate','type_of_country_house'],
				'childrens' => [
				],
			],
			// Земельные участки
			[
				'name' => 'headings.land',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_земельных_участков',
							'international' => 'prodaja_zemelnih_uchastkov',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'продажа_земельных_участков',
							'international' => 'prodaja_zemelnih_uchastkov',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_land'],
				'childrens' => [
				],
			],
			// Гаражи и машиноместа
			[
				'name' => 'headings.garages_and_parking_places',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_гаражей_и_машиномест',
							'international' => 'prodaja_garajey_i_mashinomest',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'продажа_гаражей_и_машиномест',
							'international' => 'prodaja_garajey_i_mashinomest',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_garage'],
				'childrens' => [
				],
			],
			// Коммерческая недвижимость
			[
				'name' => 'headings.commercial_property',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_коммерческой_недвижимости',
							'international' => 'prodaja_kommercheskoy_nedvijimosti',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'продажа_коммерческой_недвижимости',
							'international' => 'prodaja_kommercheskoy_nedvijimosti',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_commercial_property'],
				'childrens' => [
				],
			],
			// Недвижимость за рубежом
			[
				'name' => 'headings.property_abroad',
				'aliases' => [
					'ru' => [
						[
							'local' => 'продажа_недвижимости_за_рубежом',
							'international' => 'prodaja_nedvijimosti_za_rubejom',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'продажа_недвижимости_за_рубежом',
							'international' => 'prodaja_nedvijimosti_za_rubejom',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
				],
			],
		],
	],
	// Бытовая электроника
	[
		'name' => 'headings.consumer_electronics',
		'aliases' => [
			'ru' => [
				[
					'local' => 'бытовая_эелектроника',
					'international' => 'bitovaya_elektronika',
					'property_id' => null,
					'property_value' => null,
				]
			],
			'en' => [
				[
					'local' => 'бытовая_эелектроника',
					'international' => 'bitovaya_elektronika',
					'property_id' => null,
					'property_value' => null,
				]
			],
		],
		'show_in_menu' => false,
		'properties' => [],
		'childrens' => [
			// Аудио и видео
			[
				'name' => 'headings.audio_and_video',
				'aliases' => [
					'ru' => [
						[
							'local' => 'аудио_видео',
							'international' => 'audio_video',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'аудио_видео',
							'international' => 'audio_video',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Игры, приставки и программы
			[
				'name' => 'headings.games_consoles_and_programs',
				'aliases' => [
					'ru' => [
						[
							'local' => 'игры_приставки_и_программы',
							'international' => 'igri_pristavki_i_programmi',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'игры_приставки_и_программы',
							'international' => 'igri_pristavki_i_programmi',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Настольные компьютеры
			[
				'name' => 'headings.desktop_computers',
				'aliases' => [
					'ru' => [
						[
							'local' => 'настольные_компьтеры',
							'international' => 'nastolynie_kompyuteri',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'настольные_компьтеры',
							'international' => 'nastolynie_kompyuteri',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Ноутбуки
			[
				'name' => 'headings.laptops',
				'aliases' => [
					'ru' => [
						[
							'local' => 'ноутбуки',
							'international' => 'noutboki',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'ноутбуки',
							'international' => 'noutboki',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Оргтехника и расходники
			[
				'name' => 'headings.office_equipment_and_consumables',
				'aliases' => [
					'ru' => [
						[
							'local' => 'оргтехника',
							'international' => 'orgtechnika',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'оргтехника',
							'international' => 'orgtechnika',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Планшеты и электронные книги
			[
				'name' => 'headings.tablets_and_e_books',
				'aliases' => [
					'ru' => [
						[
							'local' => 'планшеты_и_электронные_книги',
							'international' => 'plansheti_i_elektronie_knigi',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'планшеты_и_электронные_книги',
							'international' => 'plansheti_i_elektronie_knigi',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Телефоны
			[
				'name' => 'headings.phones',
				'aliases' => [
					'ru' => [
						[
							'local' => 'телефоны',
							'international' => 'telefoni',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'телефоны',
							'international' => 'telefoni',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Товары для компьютера
			[
				'name' => 'headings.computer_products',
				'aliases' => [
					'ru' => [
						[
							'local' => 'товары_для_компьютеры',
							'international' => 'tovari_dlya_kompyutera',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'товары_для_компьютеры',
							'international' => 'tovari_dlya_kompyutera',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Фототехника
			[
				'name' => 'headings.photographic_equipment',
				'aliases' => [
					'ru' => [
						[
							'local' => 'фототехника',
							'international' => 'fototechnika',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'фототехника',
							'international' => 'fototechnika',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
		],
	],

	// Работа
	[
		'name' => 'headings.work',
		'aliases' => [
			'ru' => [
				[
					'local' => 'работа',
					'international' => 'rabota',
					'property_id' => null,
					'property_value' => null,
				]
			],
			'en' => [
				[
					'local' => 'работа',
					'international' => 'rabota',
					'property_id' => null,
					'property_value' => null,
				]
			],
		],
		'show_in_menu' => false,
		'properties' => ['field_of_activity', 'type_of_employment'],
		'childrens' => [
			[
				'name' => 'headings.vacancies',
				'aliases' => [
					'ru' => [
						[
							'local' => 'вакансии',
							'international' => 'vacansii',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'вакансии',
							'international' => 'vacansii',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['working_conditions'],
				'childrens' => [
				],
			],
			[
				'name' => 'headings.summary',
				'aliases' => [
					'ru' => [
						[
							'local' => 'резюме',
							'international' => 'rezume',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'резюме',
							'international' => 'rezume',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['experience', 'education'],
				'childrens' => [
				],
			],
		],
	],
	// Услуги
	[
		'name' => 'headings.services',
		'aliases' => [
			'ru' => [
				[
					'local' => 'услуги',
					'international' => 'uslugi',
					'property_id' => null,
					'property_value' => null,
				]
			],
			'en' => [
				[
					'local' => 'услуги',
					'international' => 'uslugi',
					'property_id' => null,
					'property_value' => null,
				]
			],
		],
		'show_in_menu' => false,
		'properties' => ['type_of_service'],
		'childrens' => [
			// Предложение услуг
			[
				'name' => 'headings.offer_of_services',
				'aliases' => [
					'ru' => [
						[
							'local' => 'предложение_услуг',
							'international' => 'predlojenie_uslug',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'предложение_услуг',
							'international' => 'predlojenie_uslug',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
		],
	],
	// Хобби и отдых
	[
		'name' => 'headings.hobbies_and_leisure',
		'aliases' => [
			'ru' => [
				[
					'local' => 'хобби_и_отдых',
					'international' => 'hobbi_i_otdih',
					'property_id' => null,
					'property_value' => null,
				]
			],
			'en' => [
				[
					'local' => 'хобби_и_отдых',
					'international' => 'hobbi_i_otdih',
					'property_id' => null,
					'property_value' => null,
				]
			],
		],
		'show_in_menu' => false,
		'properties' => [],
		'childrens' => [
			// Билеты и путешествия
			[
				'name' => 'headings.tickets_and_travel',
				'aliases' => [
					'ru' => [
						[
							'local' => 'билеты_и_путешествия',
							'international' => 'bileti_i_puteshestviya',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'билеты_и_путешествия',
							'international' => 'bileti_i_puteshestviya',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Велосипеды
			[
				'name' => 'headings.bicycles',
				'aliases' => [
					'ru' => [
						[
							'local' => 'велосипеды',
							'international' => 'velosipedi',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'велосипеды',
							'international' => 'velosipedi',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Книги и журналы
			[
				'name' => 'headings.books_and_magazines',
				'aliases' => [
					'ru' => [
						[
							'local' => 'книги_и_журналы',
							'international' => 'knigi_i_jurnali',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'книги_и_журналы',
							'international' => 'knigi_i_jurnali',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Коллекционирование
			[
				'name' => 'headings.collecting',
				'aliases' => [
					'ru' => [
						[
							'local' => 'коллекционирование',
							'international' => 'kollekcionirovalie',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'коллекционирование',
							'international' => 'kollekcionirovalie',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Музыкальные инструменты
			[
				'name' => 'headings.musical_instruments',
				'aliases' => [
					'ru' => [
						[
							'local' => 'музыкальные_инструменты',
							'international' => 'muzikalnie_instrumenti',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'музыкальные_инструменты',
							'international' => 'muzikalnie_instrumenti',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Охота и рыбалка
			[
				'name' => 'headings.hunting_and_fishing',
				'aliases' => [
					'ru' => [
						[
							'local' => 'охота_и_рыбалка',
							'international' => 'ohota_i_ribalka',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'охота_и_рыбалка',
							'international' => 'ohota_i_ribalka',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
			// Спорт и отдых
			[
				'name' => 'headings.sports_and_leisure',
				'aliases' => [
					'ru' => [
						[
							'local' => 'спорт_и_отдых',
							'international' => 'sport_i_otdih',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'спорт_и_отдых',
							'international' => 'sport_i_otdih',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
		],
	],
	// Личные вещи
	[
		'name' => 'headings.personal_things',
		'aliases' => [
			'ru' => [
				[
					'local' => 'личные_вещи',
					'international' => 'lichnie_veshchi',
					'property_id' => null,
					'property_value' => null,
				]
			],
			'en' => [
				[
					'local' => 'личные_вещи',
					'international' => 'lichnie_veshchi',
					'property_id' => null,
					'property_value' => null,
				]
			],
		],
		'show_in_menu' => false,
		'properties' => [],
		'childrens' => [
			// Одежда, обувь, аксессуары
			[
				'name' => 'headings.clothes_shoes_accessories',
				'aliases' => [
					'ru' => [
						[
							'local' => 'одежда_обувь_аксессуары',
							'international' => 'obuv_jdejda_aksessuari',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'одежда_обувь_аксессуары',
							'international' => 'obuv_jdejda_aksessuari',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_clothes','womens_element_of_clothing','mens_element_of_clothing','type_of_shoes','size_of_womens_clothing','size_of_mens_clothing','size_of_womens_shoes','size_of_mens_shoes'],
				'childrens' => [
					
				],
			],
			// Детская одежда и обувь
			[
				'name' => 'headings.childrens_clothing_and_footwear',
				'aliases' => [
					'ru' => [
						[
							'local' => 'детская_одежда_и_обувь',
							'international' => 'detskaya_odejda_i_obuv',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'детская_одежда_и_обувь',
							'international' => 'detskaya_odejda_i_obuv',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_children_clothes','element_of_children_clothes','type_of_children_shoes','size_of_children_clothing','size_of_children_shoes'],
				'childrens' => [
					
				],
			],
			// Товары для детей и игрушки
			[
				'name' => 'headings.goods_for_children_and_toys',
				'aliases' => [
					'ru' => [
						[
							'local' => 'товары_для_детей_и_игрушки',
							'international' => 'tovari_dlya_detey_i_igrushki',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'товары_для_детей_и_игрушки',
							'international' => 'tovari_dlya_detey_i_igrushki',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_products_for_children','type_of_childrens_furniture'],
				'childrens' => [
					
				],
			],
			// Часы и украшения
			[
				'name' => 'headings.watches_and_jewelery',
				'aliases' => [
					'ru' => [
						[
							'local' => 'часы_и_украшения',
							'international' => 'chasi_i_ukrasheniya',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'часы_и_украшения',
							'international' => 'chasi_i_ukrasheniya',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => ['type_of_jewelry','type_of_bijouterie'],
				'childrens' => [
					
				],
			],
			// Красота и здоровье
			[
				'name' => 'headings.beauty_and_health',
				'aliases' => [
					'ru' => [
						[
							'local' => 'красота_и_здоровье',
							'international' => 'krasota_i_zdorovie',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'красота_и_здоровье',
							'international' => 'krasota_i_zdorovie',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [
					
				],
			],
		],
	],
	// Животные
	[
		'name' => 'headings.animals',
		'aliases' => [
			'ru' => [
				[
					'local' => 'животные',
					'international' => 'jivotnie',
					'property_id' => null,
					'property_value' => null,
				]
			],
			'en' => [
				[
					'local' => 'животные',
					'international' => 'jivotnie',
					'property_id' => null,
					'property_value' => null,
				]
			],
		],
		'show_in_menu' => false,
		'properties' => [],
		'childrens' => [
			// Собаки
			[
				'name' => 'headings.dogs',
				'aliases' => [
					'ru' => [
						[
							'local' => 'собаки',
							'international' => 'sobaki',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'собаки',
							'international' => 'sobaki',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [

				],
			],
			// Кошки
			[
				'name' => 'headings.cats',
				'aliases' => [
					'ru' => [
						[
							'local' => 'кошки',
							'international' => 'koshki',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'кошки',
							'international' => 'koshki',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [

				],
			],
			// Птицы
			[
				'name' => 'headings.birds',
				'aliases' => [
					'ru' => [
						[
							'local' => 'птицы',
							'international' => 'ptici',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'птицы',
							'international' => 'ptici',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [

				],
			],
			// Аквариум
			[
				'name' => 'headings.aquarium',
				'aliases' => [
					'ru' => [
						[
							'local' => 'аквариум',
							'international' => 'akvarium',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'аквариум',
							'international' => 'akvarium',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [

				],
			],
			// Другие животные
			[
				'name' => 'headings.other_animals',
				'aliases' => [
					'ru' => [
						[
							'local' => 'другие_животные',
							'international' => 'drugie_jivotnie',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'другие_животные',
							'international' => 'drugie_jivotnie',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [

				],
			],
			// Товары для животных
			[
				'name' => 'headings.goods_for_pets',
				'aliases' => [
					'ru' => [
						[
							'local' => 'товары_для_животных',
							'international' => 'tovari_dlya_jivotnih',
							'property_id' => null,
							'property_value' => null,
						]
					],
					'en' => [
						[
							'local' => 'товары_для_животных',
							'international' => 'tovari_dlya_jivotnih',
							'property_id' => null,
							'property_value' => null,
						]
					],
				],
				'show_in_menu' => false,
				'properties' => [],
				'childrens' => [

				],
			],
		],
	],
];