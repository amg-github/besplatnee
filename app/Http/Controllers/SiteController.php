<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Site;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller
{
	public function site($alias)
	{
		$site = \App\Site::where('type', 'site')->where('allias', $alias)->first();

		if(!$site) {
			abort(404);
		}

		return view('site.view', [
			'site' => $site,
		]);
	}

	public function shop($alias)
	{
		
	}

	public function page($alias, $id)
	{

	}

	public function product($alias, $id) 
	{
		
	}

	public function create(Request $request) {
		if(\Gate::denies('create', new Site)) {
			abort(404);
		}

		$this->version = 'b';
        $this->header('Создание сайта');
        
    	return view('site.create', [
            'version' => $this->version,
            'city' => \Config::get('area'),
            'errors' => $this->form_errors,
        ]);
	}

	public function save(Request $request) {
		if(\Gate::denies('create', new Site)) {
			abort(404);
		}

		$validator = Validator::make($request->all(), [
            'name' => 'required|max:80|min:5',
            'description' => 'required|max:300|min:5',
            'type' => 'required|in:0,1',
        ], [
            'required' => 'Это поле обязательно.',
            'max' => 'Длинна этого поля не должна превышать :max.',
            'min' => 'Длинна этого поля не должна быть больше :min.',
        ]);

        $this->form_errors = $validator->messages();

        if($validator->fails()) {
            return $this->create($request);
        }

		$site = $this->besplatnee->sites()->add($request->all());

		return redirect()->route($site->type == 0 ? 'partner' : 'magazin', ['allias' => $site->allias]);
	}
}