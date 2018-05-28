<?php 
$headings = \App\Heading::whereNull('parent_id')
	->with(['childrens' => function ($q) {
		$q->orderBy('sortindex', 'asc');
	}])
	->orderBy('sortindex', 'asc')
	->get()
	->toArray();

dd($headings);
//\App\Models\GeoObject::find(5078)
// $offset = $request->input('offset', 0);
// $limit = $request->input('limit', 50);

// $_table = $request->input('table', 'advert_city');
// $_object = $request->input('object', 'advert');
// $_subject = $request->input('subject', 'city');
// $_object_id = $request->input('object_id', $_object . '_id');
// $_subject_id = $request->input('subject_id', $_subject . '_id');
// $_target = $request->input('target', $_object . '_geo_object');

// $objects = \DB::table($_table)
// 	->orderBy($_object_id, 'asc')
// 	->orderBy($_subject_id, 'asc')
// 	->offset($offset)
// 	->limit($limit)
// 	->get();

// $subject_ids = $objects->pluck($_subject_id);

// $geo_objects = \DB::table('geo_objects')
// 	->select('id', 'oldid')
// 	->whereIn('oldid', $subject_ids)
// 	->get()
// 	->pluck('id', 'oldid');

// $object_geo_objects = [];

// foreach($objects as $object) {
// 	if(isset($geo_objects[$object->{$_subject_id}])) {
// 		$object_geo_objects[] = [
// 			$_object_id => $object->{$_object_id},
// 			'geo_object_id' => $geo_objects[$object->{$_subject_id}],
// 		];
// 	}
// }

// if(count($object_geo_objects) > 0) {
// 	\DB::table($_target)->insert($object_geo_objects);
// 	return '1';
// }

// return '0';