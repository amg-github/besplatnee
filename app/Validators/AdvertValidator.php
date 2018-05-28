<?php 

namespace App\Validators;

use App\Library\Repositories\Validator;

class AdvertValidator extends Validator {

	public function rules() {
		$rules = [
			'name' => 'required|max:' . config('adverts.name.size', 40),
            'content' => 'required|max:' . config('adverts.content.size', 300),
            'main_phrase' => 'max:' . config('adverts.main_phrase.size', 50),
            'city_ids.*' => 'required_if:duplicate_in_all_cities,0|exists:cities,id',
            'region_ids.*' => 'nullable|exists:regions,id',
            'country_ids.*' => 'nullable|exists:countries,id',
            'heading_id' => 'required|exists:headings,id',
            'price' => 'nullable|price',
            'currency_id' => 'nullable|exists:currencies,id',
            'extend_content' => 'nullable|max:' . config('adverts.extend_content.size', 10000),
            'show_phone' => 'required|in:0,1',
            'contacts' => 'nullable|max:' . config('adverts.contacts.size', 2000),
            'address' => 'nullable',
            'send_to_print' => 'nullable|in:0,1',
            'site_url' => 'nullable|extended_url',
            'status' => 'nullable|exists:bill_statuses,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
		];

		return [
			Validator::CREATE_RULES => $rules,
			Validator::UPDATE_RULES => $rules,
		];
	}

	public function messages() {
		return [

		];
	}

}