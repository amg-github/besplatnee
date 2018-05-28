<?php 
namespace App\Facades;

use App\Heading;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class HeadingsManager extends ModelsManager {
	public $model = Heading::class;

	public function get($heading_id, $removed = false) {
		$heading = Heading::where('headings.id', $heading_id)
			->leftJoin('heading_aliases', function ($join) {
				$join->on('heading_aliases.heading_id', '=', 'headings.id');
			})
			->where('heading_aliases.language', \App::getLocale());

		if($removed && $this->hasSoftDelete()) {
			$heading->withTrashed();
		}

		return $heading->first(['headings.*', 'heading_aliases.alias_local', 'heading_aliases.alias_international']);
	}

	public function getEmpty() {
		$data = parent::getEmpty();

		$data['active'] = true;
		$data['show_in_top_menu'] = false;
		$data['sortindex'] = 0;

		$data['childrens'] = [];
		$data['aliases'] = [];
		$data['properties'] = [];

		return $data;
	}

	public function setData($heading, $data) {
		$data = array_merge($this->getEmpty(), $data);

		$heading->fill($data);

		/*if(empty($heading->allias)) {
			$heading->allias = Str::lower(app('slug')->make($heading->name));
		}*/

		if($heading->sortindex == 0) {
			$heading->sortindex = Heading::where('parent_id', $heading->parent_id)->max('sortindex');
			$heading->sortindex++;
		}

		$heading->save();
		$heading->setAliases($data['aliases']);
		$heading->setProperties($data['properties']);

		foreach($data['childrens'] as $children) {
			$children['parent_id'] = $heading->id;
			$this->add($children);
		}

		return $heading;
	}

	public function getByAlias($alias) {
		return \App\Heading::whereHas('aliases', function ($query) use ($alias) {
            $query->where('alias_international', $alias);
        })->first();
	}

	public function getTree() {
		return Heading::where('headings.active', true)
			->where('headings.parent_id', null)
			->leftJoin('heading_aliases', function ($join) {
				$join->on('heading_aliases.heading_id', '=', 'headings.id');
			})
			->where('heading_aliases.language', \App::getLocale())
			->whereNull('heading_aliases.property_id')
			->orderBy('headings.sortindex')
			->get(['headings.*', 'heading_aliases.alias_local', 'heading_aliases.alias_international']);
	}

	public function generateUri($heading, $properties = []) {
		if(is_int($heading)) {
			$heading = $this->get($heading);
		}

		if(is_object($heading)) {
			$alias_international = $heading->getInternationalAlias();
			$heading = $heading->toArray();
			$heading['alias_international'] = $alias_international;
		}

		return route('category', array_merge([
            'alias' => $heading['alias_international'],
            'city' => 'v_' . str_replace('-', '_', config('area')->getInternationalInName()),
            'sort' => __('sorts.fakeupdated_at.alias'),
        ], $properties));
	}

	public function getMainList() {
		return \App\Heading::where('headings.active', true)
            ->where('headings.show_in_top_menu', true)
            ->orderBy('headings.sortindex')
            ->leftJoin('heading_aliases', function ($join) {
				$join->on('heading_aliases.heading_id', '=', 'headings.id');
			})
			->where('heading_aliases.language', \App::getLocale())
			->whereNull('heading_aliases.property_id')
            ->get(['headings.*', 'heading_aliases.alias_local', 'heading_aliases.alias_international']);
	}

	public function _getTree($parent_id, $depth) {
		$headings = $this->getList([
			'parents' => [$parent_id],
			'level' => $depth,
			'sort' => [
				'parent_id' => 'asc',
				'sortindex' => 'asc',
			],
		]);

		$tree = [];
		foreach($headings as $heading) {
			if($heading->parent_id == null) {
				$tree[$heading->id]['root'] = $heading;
				$tree[$heading->id]['childrens'] = [];
			} else {
				if(isset($tree[$heading->parent_id])) {
					$tree[$heading->parent_id]['childrens'][] = $heading;
				}
			}
		}

		return $tree;
	}

	public function getTreeArray() {
		$data = [];
		foreach($this->getTree() as $sheet) {
			$childs = [];

			foreach($sheet->childrens as $child) {
				$childs[] = [
					'title' => $child->name,
					'value' => $child->id,
				];
			}

			$data[] = [
				'title' => $sheet->name,
				'value' => $sheet->id,
				'childs' => $childs,
			];
		}

		return $data;
	}

	public function getChildrens($heading) {
		if(is_object($heading)) {
			if($heading->_childrens) {
				return $heading->_childrens;
			} else {
				$id = $heading->id;
			}
		} else {
			$id = $heading;
		}

		$heading->_childrens = Heading::where('headings.active', true)
			->where('headings.parent_id', $id)
			->leftJoin('heading_aliases', function ($join) {
				$join->on('heading_aliases.heading_id', '=', 'headings.id');
			})
			->where('heading_aliases.language', \App::getLocale())
			->whereNull('heading_aliases.property_id')
			->orderBy('headings.sortindex')
			->get(['headings.*', 'heading_aliases.alias_local', 'heading_aliases.alias_international']);

		return $heading->_childrens;
	}

	public function getChildrensCount($heading) {
		if(is_object($heading)) {
			return $heading->_childrens 
				? $heading->_childrens->count() 
				: $heading->childrens->count();
		} else {
			return \App\Heading::where('active', true)->where('parent_id', $heading)->count();
		}
	}

	public function getProperties($id) {
		$heading = $this->get($id);
		if(!$heading) { return collect(); }
		$property_ids = $heading->properties()->pluck('id');

		$parent = $heading->parent()->first();
		if($parent) {
			$property_ids = $property_ids->merge($parent->properties()->pluck('id'));
		}

		return \App\Property::whereIn('id', $property_ids)->get();
	}

	public function propertyMakeValues($property) {
		$values = [];

		if(!is_object($property)) {
			$property = \App\Property::where('id', $property)->orWhere('name', $property)->first();
		}

		if(!$property) { return $values; }

		if($property->type == 'numeric' && $property->options['view'] == 'select') {

			$parents = [];

			foreach($property->values as $value) {
				$parent = \App\PropertyDefaultValue::where('id', $value->parent_value)->orderBy('id')->first();
				$parents[] = $parent ? $parent : ['id' => 0, 'title' => null, 'value' => null];
			}

			for($value = $property->options['min']; $value <= $property->options['max']; $value = $value + $property->options['step']) {
				$values[$value] = [
					'id' => $value - $property->options['min'] + 1,
					'title' => trans_choice($property->options['select-value'], $value),
					'value' => $value,
					'parents' => $parents,
				];
			}
		} else {
			foreach($property->values as $value) {
				$parents = isset($values[$value->value]) ? $values[$value->value]['parents'] : [];

				$parent = \App\PropertyDefaultValue::where('id', $value->parent_value)->orderBy('id')->first();
				if($parent) {
					$parents[] = $parent->toArray();
				}

				$values[$value->value] = [
					'id' => $value->id,
					'title' => __($value->title),
					'value' => $value->value,
					'parents' => $parents,
				];
			}

			foreach($values as $value => $data) {
				if(count($values[$value]['parents']) == 0) {
					$values[$value]['parents'] = [['id' => 0, 'title' => null, 'value' => null]];
				}
			}
		}

		return $values;
	}

	public function getList($properties = []) {
		$properties = array_merge([
			'parents' => [null],
			'level' => 0,
			'limit' => 0,
			'offset' => 0,
			'sort' => ['parent_id' => 'ASC', 'sortindex' => 'ASC'],
			'paginate' => false,
			'maxLimit' => 0,
			'return' => 'model', // model, array
			'not-ids' => [],
			'actived' => true,
			'showedMenu' => null,
		], $properties);

		if($properties['maxLimit'] > 0 && $properties['limit'] > $properties['maxLimit']) {
			$properties['limit'] = $properties['maxLimit'];
		}

		$headings = \App\Heading::with('aliases')
			->with('properties');

		$isEmptyParents = count($properties['parents']) == 0;
		$isNullParent = count($properties['parents']) == 1 && $properties['parents'][0] == null;
		$isZeroParent = count($properties['parents']) == 1 && $properties['parents'][0] == 0;

		if($properties['level'] == 0) {
			if(!$isEmptyParents && !$isNullParent && !$isZeroParent) {
				$headings->whereIn('parent_id', $properties['parents']);
			}
		} else {
			if($isEmptyParents || $isNullParent || $isZeroParent) {
				$ids = \DB::table('headings')->whereNull('parent_id')->pluck('id')->all();
			} else {
				$ids = \DB::table('headings')->whereIn('parent_id', $properties['parents'])->pluck('id')->all();
			}

			$parents = $ids;
			for($i = 0; $i < $properties['level']; $i++) {
				if(count($parents) == 0) { break ; }
				$parents = \DB::table('headings')->whereIn('parent_id', $parents)->pluck('id')->all();
				$ids = array_merge($ids, $parents);
			}

			$headings->whereIn('id', $ids);
		}

		$headings->whereNotIn('id', $properties['not-ids']);

		if($properties['actived'] != null) {
			$headings->where('active', $properties['actived']);
		}

		if($properties['showedMenu'] != null) {
			$headings->where('show_in_top_menu', $properties['showedMenu']);
		}

		foreach($properties['sort'] as $by => $dir) {
			$headings->orderBy($by, $dir);
		}

		if($properties['maxLimit'] > 0) {
			$headings->take($properties['maxLimit']);
		}

		// print_r( $headings->getBindings() );
		// dd($headings->toSql());

		if($properties['paginate']) {
			$headings = $headings->paginate($properties['limit']);
		} else {
			if($properties['limit'] > 0) {
				$headings->take($properties['limit'])->skip($properties['offset']);
			}

			$headings = $headings->get();
		}

		switch($properties['return']) {
			case 'model': break;
			case 'array': 
					$headings = $adverts->toArray();
				break;
			default: break;
		}

		return $headings;
	}

	public function getPath($heading) {
		if(!is_object($heading)) {
			$heading = $this->get($heading);
		}

        $headings = new Collection;
        do {
            $headings->prepend($heading);
            $heading = $heading->parent;
        } while($heading);

        return $headings;
	}

	public function getPathWithProperties($heading, $properties = []) {
		if(!is_object($heading)) {
			$heading = $this->get($heading);
		}

		$headings = $this->getPath($heading);

		$path = [];
		foreach($headings as $h) {
			$path[] = [
				'caption' => $h->name,
				'url' => $h->getUrl(),
			];
		}

		if(count($properties) > 0) {

			$parent_id = $heading->aliases()
				->where('language', \App::getLocale())
				->first()->id;

			do {
				$q = \App\HeadingAlias::where('language', \Config::get('language')->code)
					->where('heading_id', $heading->id)
					->where('parent_id', $parent_id);

				
				$q->where(function ($q) use ($properties) {
					foreach($properties as $id => $value) {
						$q->orWhere(function ($q) use ($id, $value) {
							$q->where('property_id', $id);
							$q->where('property_value', $value);
						});
					}
				});

				$alias = $q->first();
				if($alias) {
					$propertyValue = \App\PropertyDefaultValue::where('property_id', $alias->property_id)
							->where('value', $alias->property_value)->first();

					if(!$propertyValue) { continue ; }

					$path[] = [
						'caption' => $propertyValue->title,
						'url' => route('category', [
				            'alias' => $alias->alias_international,
				            'city' => 'v_' . \Config::get('area')->aliases()->where('language',  \App::getLocale())->first()->ergative_international,
				            'sort' => 'novye',
				        ]),
					];

					$parent_id = $alias->id;
				}

			} while($alias);

		}

		return $path;
	}

	/*public function getNextProperty($heading, $property_parent = null) {
		if(!is_object($heading)) {
			$heading = \App\Heading::find($heading);
		}

		if(!$heading) { return null; }

		$alias = $heading->aliases()->where('property_id', $property_parent)->first();
		if(!$alias) { return null; }

		$alias = $heading->aliases()->where('')
	}*/

	public function getChildrenWithProperties($heading, $property_parent = null, $property_value = null) {
		$childrens = [];

		if(!is_object($heading)) {
			$heading = \App\Heading::find($heading);
		}

		if(!$heading) { return $childrens; }

		if(count($heading->childrens) > 0) {
			foreach($heading->childrens as $child) {
				$childrens[] = [
					'caption' => $child->name,
					'url' => $child->getUrl(),
				];
			}
		} else {

			$parentAlias = \App\HeadingAlias::where('heading_id', $heading->id)
				->where('property_id', $property_parent)
				->where('property_value', $property_value)
				->where('language', \Config::get('language')->code)->first();

			if(!$parentAlias) { return $childrens; }


			foreach(\App\HeadingAlias::where('heading_id', $heading->id)
				->where('parent_id', $parentAlias->id)
				->where('language', \Config::get('language')->code)
				->orderBy('id')
				->get() as $child) {

				$propertyValue = \App\PropertyDefaultValue::where('property_id', $child->property_id)
					->where('value', $child->property_value)->first();

				if(!$propertyValue) { continue ; }

				$childrens[] = [
					'caption' => $propertyValue->title,
					'url' => route('category', [
			            'alias' => $child->alias_international,
			            'city' => 'v_' . \Config::get('area')->aliases()->where('language',  \App::getLocale())->first()->ergative_international,
			            'sort' => 'novye',
			        ]),
				];

			}

		}

		return $childrens;
	}	

	public function getFiltersByAlias($alias) {
		$filters = [];

		$headingAlias = \App\HeadingAlias::where('alias_international', $alias)
			->where('language', \Config::get('language')->code)->first();

		while($headingAlias && $headingAlias->parent_id != null) {
			$filters[$headingAlias->property_id] = $headingAlias->property_value;

			$headingAlias = \App\HeadingAlias::where('heading_id', $headingAlias->heading_id)
				->where('id', $headingAlias->parent_id)->first();
		}

		return $filters;
	}

	public function getAdvertProperties($heading_id = null) {
		$headings = \App\Heading::where('parent_id', $heading_id)->pluck('id');
		$headings[] = $heading_id;

		$properties = \App\Property::whereHas('headings', function ($q) use ($headings) {
			$q->whereIn('id', $headings);
		})->with('values')->get();
	}

	public function generateTree() {
		$headings = Heading::where('headings.active', true)
			->leftJoin('heading_aliases', function ($join) {
				$join->on('heading_aliases.heading_id', '=', 'headings.id');
			})
			->where('heading_aliases.language', \App::getLocale())
			->whereNull('heading_aliases.property_id')
			->orderBy('headings.parent_id')
			->orderBy('headings.sortindex')
			->get(['headings.*', 'heading_aliases.alias_local', 'heading_aliases.alias_international']);

		$tree = collect();

		foreach($headings as $heading) {
			if($heading->parent_id == null) {
				$tree->put($heading->id, $heading);
			} else {
				if(!$tree->has($heading->parent_id)) { continue; }
				if(!$tree[$heading->parent_id]->_childrens) {
					$tree[$heading->parent_id]->_childrens = collect();
				}
				
				$tree[$heading->parent_id]->_childrens->put($heading->id, $heading);
			}
		}

		return $tree;
	}
}