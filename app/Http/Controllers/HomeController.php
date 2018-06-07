<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\Besplatnee;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->addBreadCrumb('Главная страница', '/');

        $this->setMetaTitle('Ещё БЕСПЛАТНЕЕ - интернет газета бесплатных объявлений и рекламы');
        $this->setPageTitle('Ещё БЕСПЛАТНЕЕ - интернет газета бесплатных объявлений и рекламы');
        $this->setMetaDescription('Ещё БЕСПЛАТНЕЕ - интернет газета бесплатных объявлений и рекламы');
        $this->setMetaKeywords('Ещё БЕСПЛАТНЕЕ - интернет газета бесплатных объявлений и рекламы');

        return view('location.main', []);
    }

    public function defineLocation(Request $request) {
        $this->title('Ещё БЕСПЛАТНЕЕ - интернет газета бесплатных объявлений и рекламы');
        return view('location.main', [

        ]);
    }

    public function searchQueries(Request $request) {
        $queries = 

        \App\AdvertSearchQuery::
            where('active', '1')
            ->where('approved', '1')
            ->whereHas('citiesNew', function($q) use ($request) {
                $q->where('city_id', \Config::get('area')->id);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(50);

        return view('queries', [
            'queries'   => $queries,
            'version'   => 'b',
        ]);
    }

    public function geoobjects_update(Request $request) {
        $cities = \App\AdvertSearchQueryCity::all();

        foreach ($cities as $city) {
            $g = \App\GeoObject::where('type', 'city')->where('oldid', $city->city_id)->first();
            if ($g) {
                $city->fill([
                    'city_id'   =>  $g->id,
                ]);

                $city->save();
            }
        }


    }
}
