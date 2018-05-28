<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\AreaName;

class SiteMapController extends BaseController
{

    public function __construct () 
    {

    }
    	
	public function index(Request $request) {
		$cityAliases = \DB::table('cities')
			->leftJoin('area_names', 'cities.name_key', '=', 'area_names.key')
			->where('area_names.language', 'en')
			->select('area_names.nominative_international')
			->get()
			->pluck('nominative_international');

		dd($cityAliases);

		return '';
	}
}
