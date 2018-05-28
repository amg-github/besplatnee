<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdvertBillsController extends AdminController {

	public function groups() {
		return [
			[
				'name' => 'all',
				'title' => __('admin.bills.all'),
				'active' => true,
				'wheres' => [],
			],
			[
				'name' => 'free',
				'title' => __('admin.bills.paid'),
				'active' => false,
				'wheres' => [
					'pickup-statuses' => [3],
				],
			],
			[
				'name' => 'paid',
				'title' => __('admin.bills.free'),
				'active' => false,
				'wheres' => [
					'pickup-statuses' => [0],
				],
			],
			[
				'name' => 'unpaid',
				'title' => __('admin.bills.unpaid'),
				'active' => false,
				'wheres' => [
					'pickup-statuses' => [2,4],
				],
			],
		];
	}

	public function filters() {
		return [];
	}

	public function columns() {
		return [
			[
				'name' => 'status',
				'title' => __('admin.adverts.status'),
				'width' => '5%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($advert) {
							switch($advert->status) { 
								case 0: $iconName = 'grey'; break;
								case 3: $iconName = 'green'; break;
								case 2: $iconName = 'yellow'; break;
								case 4: 
								case 5: 
										$iconName = 'green'; 
									break;
							}
							return !empty($iconName) 
								? \Besplatnee::forms()->icons()->image(
									asset('img/statuses/' . $iconName . '.png'),
									__('adverts.status.' . $advert->status),
									[
										'style' => 'width:16px;margin:0 auto;',
									]
								) 
								: '';
						}
					],
				],
			],
			[
				'name' => 'actions',
				'title' => __('admin.adverts.actions'),
				'width' => '5%',
				'content' => [
					[
						'widget' => 'action',
						'action' => 'edit',
						'icon' => \Besplatnee::forms()->icons()->image(asset('img/post-params/5.png')),
					],
					[
						'widget' => 'action',
						'action' => 'remove',
						'icon' => \Besplatnee::forms()->icons()->image(asset('img/post-params/3.png')),
					],
				],
			],
			// [
			// 	'name' => 'cities',
			// 	'title' => __('admin.adverts.cities'),
			// 	'width' => '10%',
			// 	'content' => [
			// 		[
			// 			'widget' => 'relation',
			// 			'relation' => 'cities',
			// 			'view' => function ($city) {
			// 				return $city->getName();
			// 			},
			// 			'separator' => ',&nbsp;',
			// 		],
			// 	],
			// ],
			[
				'name' => 'user',
				'title' => __('admin.adverts.content'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($advert) {
							$ad = isset($advert->advert->owner) ? $advert->advert->owner->phone : '';

							return $ad;
						},
						'separator' => ',&nbsp;',
					],
				],
			],
			[
				'name' => 'content',
				'title' => __('admin.adverts.content'),
				'width' => '20%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($advert) {
							$ad = isset($advert->advert->content) ? $advert->advert->content : '';

							return $ad;
						},
					],
				],
			],
			[
				'name' => 'pickup-type',
				'title' => __('admin.adverts.pickup-type'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($advert) {
							$pickupTemplate = $advert->template()->first();
							return $pickupTemplate ? __($pickupTemplate->name) : __('Без выделения');
						}
					],
				],
			],
			[
				'name' => 'price',
				'title' => __('admin.adverts.price'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($advert) {
							return number_format($advert->price, 2, ',', ' ') . '&nbsp;Р';

						}
					],
				],
			],
			[
				'name' => 'pickup-period',
				'title' => __('admin.adverts.pickup-period'),
				'width' => '20%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($advert) {
							return 'c ' . $advert->updated_at . '<br>по ' . $advert->deleted_at;
						}
					],
				],
			],
			[
				'name' => 'created',
				'title' => __('admin.adverts.created'),
				'width' => '10%',
				'content' => [
					[
						'widget' => 'field',
						'field' => 'created_at',
						'type' => 'date',
					],
				],
			],
			[
				'name' => 'select',
				'title' => '
					<input id="advert-items-select-all" type="checkbox" onclick="advertItems_selectAll()">
					<script type="text/javascript">
						function advertItems_selectAll () {
							var state = $(\'#advert-items-select-all\').prop(\'checked\');
							$(\'.admin-model-list-item [name="ids[]"]\').prop(\'checked\', state);
						}
					</script>',
				'width' => '3%',
				'content' => [
					[
						'widget' => 'html',
						'content' => function ($advert) {
							return '<input type="checkbox" name="ids[]" value="' . $advert->id . '">';
						}
					],
				],
			],
		];
	}

	public function actions() {
		return [
			[
				'action' => 'removeAll',
				'title' => __('admin.adverts.remove-all'),
			],
		];
	}

	public function list(Request $request, $model) {
		parent::list($request, $model);

   //      $adverts = \Besplatnee::adverts()->getList(array_merge([
   //      	'limit' => 50,
			// 'paginate' => true,
			// 'actived' => null,
			// 'blocked' => null,
			// 'deleted' => false,
			// 'approved' => null,
			// 'headings' => [],
			// 'cities' => [],
			// 'sort' => ['id' => 'ASC'],
			// 'level' => 999,
			// 'filters' => [],
			// 'pickuped' => true,
   //      ], $this->getGroup()['wheres'], $this->getFilterWheres()));

		$adverts = \App\AdvertBill::paginate(10);

        return view('admin.list.items', [
            'version' => $this->version,
            'items' => $adverts,
            'filters' => $this->filters,
            'groups' => $this->groups,
            'model' => $model,
            'columns' => $this->columns,
            'actions' => $this->actions,
        ]);
	}

	public function edit(Request $request, $model, $id) {
		parent::edit($request, $model, $id);

		$bill = \App\AdvertBill::find($id);

		$temp = '';

		if ($bill->advert->template != null) {
			$temp = $bill->advert->template;
		}

		if(!$bill) { abort(404); }

		$request->merge($bill->toArray());

		return view('admin.edit.bill', [
            'version' => $this->version,
            'item' => $bill,
            'model' => $model,
            'temp'	=> $temp,
            'fields' => $this->fields,
            'errors' => $this->form_errors,
            'complete_messages'	=> $this->complete_messages,
        ]);

	}

	public function save(Request $request, $model, $id) {

		$data = $request->all();

		if (isset($data['id']) && $data['id'] != "") {
			$bill = \App\AdvertBill::find($data['id']);
		} else {
			$bill = new \App\AdvertBill;
		}

		if(!$bill) { abort(404); }

		$validator = Validator::make($data, [
			'advert_template_id'	=> 'nullable|exists_or_in:advert_templates,id,0',
			'status'				=> 'in:0,1,2,3,4,5,6',
			'deleted_at' 			=> 'nullable|date_format:"Y-m-d H:i:s"',
			'price' 				=> 'nullable',
		]);

		if($validator->fails()) {
			$this->form_errors = $validator->messages();

			$this->complete_messages['danger'][] = 'Возникли ошибки при сохранении.';
            return $this->edit($request, $model, $id);
		}

		if (isset($data['period']) && $data['period'] != 0 && $data['period'] != "") {
			$data['deleted_at'] = \Carbon\Carbon::parse($bill->deleted_at)->addWeeks($data['period']);
		}

		$bill->fill($data);
		$bill->save();

		$this->complete_messages['success'][] = 'Счет успешно сохранен!';

		return $this->edit($request, $model, $id);

	}

	public function remove(Request $request, $model, $id) {
		parent::edit($request, $model, $id);

		$bill = \App\AdvertBill::find($id);
        if(!$bill) { abort(404); }

        if(\Gate::denies('remove', $bill)) { abort(403); }

        $bill->delete();

        return redirect()->route('admin.list', ['model' => $model]);
	}

	public function removeAll(Request $request, $model, $id) {
		$ids = $request->input('ids', []);

		if(count($ids) > 0) {
			$bills = \App\AdvertBill::whereIn('id', $ids)->get();
	        if(!$bills) { abort(404); }

	        foreach($bills as $bill) {
	        	if(\Gate::denies('remove', $bills)) { 
	        		$bill->delete();
	        	}
	        }
	    }

        return redirect()->route('admin.list', ['model' => $model]);
	}

}