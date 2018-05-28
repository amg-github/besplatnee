<?php 

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingController extends AdminController {
	public function groups () {
		return [];
	}

	public function columns () {
		return [];
	}

	public function filters () {
		return [];
	}

	public function list(Request $request, $model) {

		if ($request->isMethod('post') && $request->has('settings')) {
			$settings = $request->settings;

			foreach ($settings as $id => $setting) {
				$this->save($request, $model, $id);
			}
		}

		$settings = \App\Setting::get();

		if(!$settings) { abort(404); }

        $request->merge($settings->toArray());

        $this->version = 'a';

		return view('admin.edit.settings', [
            'version' => $this->version,
            'settings' => $settings,
            'model' => $model,
            'fields' => $this->fields,
            'errors' => $this->form_errors,
            'complete_messages' => $this->complete_messages,
        ]);
	}

	public function edit(Request $request, $model, $id) {
		return $this->list($request, $model);
	}

	public function create(Request $request, $model) {
		return $this->list($request, $model);
	}

	public function save(Request $request, $model, $id) {
		$setting = \App\Setting::find($id);

		if (!$setting) { abort(404); }

		$data = $request->settings[$id];

		$setting->fill($data);
		$setting->save();
	}

	public function remove(Request $request, $model, $id) {

	}
}