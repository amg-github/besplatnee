<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use App\Library\Http\MetaTags;
use App\Library\Http\BreadCrumbs;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, MetaTags, BreadCrumbs;

    protected $besplatnee;

    protected $version = 'b';
    protected $form_errors = [];
    protected $complete_messages = ['success' => [], 'danger' => [], 'warning' => [], 'info' => []];

    public function __construct (\App\Facades\Besplatnee $besplatnee) 
    {
    	$this->initialize($besplatnee);

        $validator = Validator::make([], []);
        $this->form_errors = $validator->messages();

        $version = \Session::get('adverts_view_mode', 'b');
        \Session::put('adverts_view_mode', request()->input('view-mode', $version));
        $version = \Session::get('adverts_view_mode', 'b');
        $this->version = $version;

        request()->merge(['view-mode' => $this->version]);
    }

    public function initialize(\App\Facades\Besplatnee $besplatnee) {
    	$this->besplatnee = $besplatnee;

        if(\Session::has('complete_messages')) {
            $this->complete_messages = \Session::get('complete_messages');
        }
    }

    public function getCountryOfSearch() {
        $country_id = \Config::get('area')->country->id;

        if(request()->has('search_country_id')) {
            $country_id = request()->input('search_country_id');
        }

        return $country_id;
    }

    public function getRegionOfSearch() {
        $region_id = \Config::get('area')->region->id;

        if(request()->has('search_region_id')) {
            $region_id = request()->input('search_region_id');
        }

        return $region_id;
    }

    public function getCityOfSearch() {
        $city_id = \Config::get('area')->id;

        if(request()->has('search_area_id')) {
            $city_id = request()->input('search_area_id');
        }

        return $city_id;
    }
}
