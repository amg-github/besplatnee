<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Advert;

class OfficeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->header('Личный кабинет');

        $this->breadcrumbs('Личный кабинет', route('office.dashboard'));
        $this->version = 'c';

        return view('office.dashboard', [  
            'version' => $this->version,
        ]);
    }

    public function adverts(Request $request)
    {
        $this->header('Мои объявления');

        $this->breadcrumbs('Личный кабинет', route('office.dashboard'));
        $this->breadcrumbs('Мои объявления', route('office.adverts'));
        $this->version = 'c';

        $adverts = \App\Advert::where('owner_id', \Auth::user()->id)->where('active', 1)->orderBy('created_at', 'desc')->paginate(10);

        return view('office.adverts', [  
            'version' => $this->version,
            'adverts' => $adverts,
            'city' => \Config::get('area'),
        ]);
    }

    public function megaAdverts(Request $request)
    {
        $this->header('Мои мега-объявления');

        $this->breadcrumbs('Личный кабинет', route('office.dashboard'));
        $this->breadcrumbs('Мои мега-объявления', route('office.adverts'));
        $this->version = 'c';

        $adverts = \App\Advert::where('owner_id', \Auth::user()->id)->where('type', 3)->where('active', 1)->paginate(10);

        return view('office.adverts', [
            'version' => $this->version,
            'adverts' => $adverts,
            'city' => \Config::get('area'),
        ]);
    }

    public function sites(Request $request)
    {
        $this->header('Личный кабинет');

        $this->breadcrumbs('Личный кабинет', route('office.dashboard'));
        $this->breadcrumbs('Мои сайты', route('office.sites'));
        $this->version = 'b';

        $sites = \App\Site::where('owner_id', \Auth::id())->where('type', 0)->get();

        return view('office.sites', [  
            'version' => $this->version,
            'sites' => $sites,
        ]);
    }

    public function shops(Request $request)
    {
        $this->header('Личный кабинет');

        $this->breadcrumbs('Личный кабинет', route('office.dashboard'));
        $this->breadcrumbs('Мои сайты', route('office.sites'));
        $this->version = 'b';

        $sites = \App\Site::where('owner_id', \Auth::id())->where('type', 1)->get();

        return view('office.shops', [  
            'version' => $this->version,
            'sites' => $sites,
        ]);
    }

    public function favorites(Request $request)
    {
        $this->header('Личный кабинет');

        $this->breadcrumbs('Личный кабинет', route('office.dashboard'));
        $this->breadcrumbs('Избранное', route('office.favorites'));
        $this->version = 'c';

        $adverts = \Auth::user()->customAdverts()->wherePivot('favorite', true)->get();

        return view('office.adverts', [  
            'version' => $this->version,
            'adverts' => $adverts,
            'city' => \Config::get('area'),
        ]);
    }
}
