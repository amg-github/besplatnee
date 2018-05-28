<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashboardController extends AdminController
{

    public $filter = '';

    public $models = [
        'adverts' => \App\Http\Controllers\Admin\AdvertController::class,
        'users' => \App\Http\Controllers\Admin\UserController::class,
        'banners' => \App\Http\Controllers\Admin\BannerController::class,
        'blacklist' => \App\Http\Controllers\Admin\BlackListController::class,
        'megaadverts' => \App\Http\Controllers\Admin\MegaAdvertController::class,
        'search' => \App\Http\Controllers\Admin\AdvertSearchController::class,
        'advertbills' => \App\Http\Controllers\Admin\AdvertBillsController::class,
        'bannerbills' => \App\Http\Controllers\Admin\BannerBillsController::class,
        'groups' => \App\Http\Controllers\Admin\GroupController::class,
        'headings' => \App\Http\Controllers\Admin\HeadingController::class,
        'geoobjects' => \App\Http\Controllers\Admin\GeoObjectsController::class,
        'advertsearchqueries' => \App\Http\Controllers\Admin\AdvertSearchQueriesController::class,
        'settings' => \App\Http\Controllers\Admin\SettingController::class,
    ];

    public function menu() {
    	return [];
    }

    public function index(Request $request)
    {
        $this->header('Управление');

        return view('admin.dashboard', [  
            'version' => $this->version,
        ]);
    }

    public function action(Request $request, $model, $action, $id) {
        if(isset($this->models[$model])) {
            $controller = app()->make($this->models[$model]);
            return app()->call([$controller, $action], [$model, $id]);
        } else {
            abort(404);
        }
    }

    public function list(Request $request, $model) {
        return $this->action($request, $model, 'list', 0);
    }

    public function edit(Request $request, $model, $id) {
        return $this->action($request, $model, 'edit', $id);
    }

    public function create(Request $request, $model) {
        return $this->action($request, $model, 'create', 0);
    }

    public function save(Request $request, $model, $id = 0) {
        return $this->action($request, $model, 'save', $id);
    }

    public function remove(Request $request, $model, $id = 0) {
        return $this->action($request, $model, 'remove', $id);
    }

    public function inline(Request $request, $model, $action, $id = 0) {
        return $this->action($request, $model, $action, $id);
    }

    public function advertList(Request $request) {
        $this->breadcrumbs('Объявления', route('admin.list', ['model' => 'adverts']));

        $this->filter = $request->input('filter', $request->session()->get('advert_filter', 'city'));
        $request->session()->put('advert_filter', $this->filter);

        $adverts = [];
        $heading_filter = $request->input('heading_id', $request->session()->get('advert_heading_filter', 0));
        $request->session()->put('advert_heading_filter', $heading_filter);

        if($heading_filter == '' || $heading_filter == 0) {
            switch($this->filter) {
                case 'cities': $adverts = \App\Advert::paginate(10); break;
                case 'city': $adverts = \App\Advert::whereHas('cities', function ($query) {
                    $query->where('id', \Config::get('area')->id);
                })->paginate(10); break;
                case 'new': $adverts = \App\Advert::where('approved', false)->paginate(10); break;
                case 'remove': $adverts = \App\Advert::onlyTrashed()->paginate(10); break;
                default: abort(404); break;
            }
        } else {
            $request->merge(['heading_id' => $heading_filter]);
            switch($this->filter) {
                case 'cities': $adverts = \App\Advert::where('heading_id', $heading_filter)->paginate(10); break;
                case 'city': $adverts = \App\Advert::where('heading_id', $heading_filter)->whereHas('cities', function ($query) {
                    $query->where('id', \Config::get('area')->id);
                })->paginate(10); break;
                case 'new': $adverts = \App\Advert::where('heading_id', $heading_filter)->where('approved', false)->paginate(10); break;
                case 'remove': $adverts = \App\Advert::where('heading_id', $heading_filter)->onlyTrashed()->paginate(10); break;
                default: abort(404); break;
            }
        }

        return view('admin.list.adverts', [
            'version' => $this->version,
            'adverts' => $adverts,
            'filter' => $this->filter,
            'heading_id' => $heading_filter,
        ]);
    }

    public function megaAdvertList(Request $request) {
        $this->breadcrumbs('Мега-объявления', route('admin.list', ['model' => 'megaadverts']));

        $this->filter = $request->input('filter', $request->session()->get('mega_filter', 'city'));
        $request->session()->put('mega_filter', $this->filter);

        $adverts = \App\Advert::with('megaAdvert')->whereHas('megaAdvert', function ($query) {
            $query->where('id', '<>', 0);
        });
        switch($this->filter) {
            case 'cities': $adverts = $adverts->paginate(10); break;
            case 'city': $adverts = $adverts->whereHas('cities', function ($query) {
                $query->where('id', \Config::get('area')->id);
            })->paginate(10); break;
            default: abort(404); break;
        }

        return view('admin.list.megaadverts', [
            'version' => $this->version,
            'adverts' => $adverts,
            'filter' => $this->filter,
        ]);
    }

    // public function headingEdit(Request $request, $id) {
    //     $this->breadcrumbs('Редактировать рубрику', route('admin.edit', ['model' => 'headings', 'id' => $id]));

    //     $heading = \App\Heading::find($id);
    //     if(!$heading) { abort(404); }

    //     if(\Gate::denies('edit', $heading)) { abort(403); }

    //     request()->merge($heading->toArray());
    //     request()->merge([
    //         'photos' => $heading->medias()->where('type', 'image')->distinct()->pluck('path'),
    //         'videos' => $heading->medias()->where('type', 'video')->distinct()->pluck('path'),
    //         'city_ids' => $heading->cities()->pluck('id'),
    //         'phone' => $heading->owner->phone,
    //         'fullname' => $heading->owner->fullname(),
    //         'email' => $heading->owner->email,
    //         'visible-phone' => !$heading->show_phone,
    //         'properties' => $heading->properties()->pluck('value', 'name'),
    //     ]);

    //     return view('admin.edit.heading', [
    //         'version' => $this->version,
    //         'heading' => $heading,
    //         'errors' => $this->form_errors,
    //     ]);
    // }

    public function advertEdit(Request $request, $id) {
        $this->breadcrumbs('Редактировать объявление', route('admin.edit', ['model' => 'adverts', 'id' => $id]));

        $advert = \App\Advert::find($id);
        if(!$advert) { abort(404); }

        if(\Gate::denies('edit', $advert)) { abort(403); }

        request()->merge($advert->toArray());
        request()->merge([
            'photos' => $advert->medias()->where('type', 'image')->distinct()->pluck('path'),
            'videos' => $advert->medias()->where('type', 'video')->distinct()->pluck('path'),
            'city_ids' => $advert->cities()->pluck('id'),
            'phone' => $advert->owner->phone,
            'fullname' => $advert->owner->fullname(),
            'email' => $advert->owner->email,
            'visible-phone' => !$advert->show_phone,
            'properties' => $advert->properties()->pluck('value', 'name'),
        ]);

        return view('admin.edit.advert', [
            'version' => $this->version,
            'advert' => $advert,
            'errors' => $this->form_errors,
        ]);
    }

    public function megaAdvertEdit(Request $request, $id) {
        $this->breadcrumbs('Редактировать мега-объявление', route('admin.edit', ['model' => 'megaadverts', 'id' => $id]));

        $advert = \App\Advert::find($id);
        if(!$advert) { abort(404); }

        if(\Gate::denies('edit', $advert)) { abort(403); }

        if(!$advert->megaAdvert) {
            $advert->megaAdvert()->save(new \App\MegaAdvert);
        }

        request()->merge($advert->megaAdvert()->first()->toArray());
        request()->merge($advert->toArray());
        request()->merge([
            'photos' => $advert->medias()->where('type', 'image')->distinct()->pluck('path'),
            'videos' => $advert->medias()->where('type', 'image')->distinct()->pluck('path'),
            'city_ids' => $advert->cities()->pluck('id'),
            'phone' => $advert->owner->phone,
            'fullname' => $advert->owner->fullname(),
            'email' => $advert->owner->email,
            'visible-phone' => !$advert->show_phone,
            'properties' => $advert->properties()->pluck('value', 'name'),
        ]);

        return view('admin.edit.megaadvert', [
            'version' => $this->version,
            'advert' => $advert,
            'errors' => $this->form_errors,
        ]);
    }

    public function advertCreate(Request $request) {
        $this->breadcrumbs('Создать объявление', route('admin.create', ['model' => 'adverts']));

        return view('admin.edit.advert', [
            'version' => $this->version,
            'advert' => new \App\Advert,
            'errors' => $this->form_errors,
        ]);
    }

    public function megaAdvertCreate(Request $request) {
        $this->breadcrumbs('Создать мега-объявление', route('admin.create', ['model' => 'megaadverts']));

        return view('admin.edit.megaadvert', [
            'version' => $this->version,
            'advert' => new \App\Advert,
            'errors' => $this->form_errors,
        ]);
    }

    public function megaAdvertSave(Request $request, $id) {
        $data = $request->all();

        if(isset($data['id']) && intval($data['id']) > 0) {
            $advert = \App\Advert::find($id);
            if(\Gate::denies('edit', $advert)) { abort(403); }

            unset($data['creator_id']);
        } else {
            $advert = new \App\Advert;
        }

        if(\Gate::denies('give', $advert)) {
            unset($data['owner_id']);
        }

        if(\Gate::denies('approved', $advert)) {
            unset($data['active']);
            unset($data['blocked']);
            unset($data['approved']);
        }

        if(\Gate::denies('remove', $advert)) {
            unset($data['status']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:80|min:5',
            'content' => 'required|max:300|min:5',
            'phone' => 'required|numeric|min:8|max:999999999999999',
            //'main_phrase' => 'required|max:100|min:5',
            'city_id' => 'required|in:' . \App\City::where('active', true)
                ->get()
                ->implode('id', ','),
            'heading_id' => 'required|in:' . \App\Heading::where('active', true)
                ->where('parent_id', '<>', 0)
                ->get()
                ->implode('id', ','),
            'price' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'extend_content' => 'max:300',
            'email' => 'required|email',
            'font_width' => 'required|numeric|min:12',
            'border_width' => 'required|numeric|min:1',
            'font_color' => 'required',
            'background_color' => 'required',
            'border_color' => 'required',
        ], [
            'required' => 'Это поле обязательно.',
            'max' => 'Длинна этого поля не должна превышать :max.',
            'min' => 'Длинна этого поля не должна быть больше :min.',
            'email' => 'Некорректный email',
            'phone' => 'Некорректный телефон',
            'price.regex' => 'Некорректная цена',
        ]);

        $this->form_errors = $validator->messages();

        if(!$validator->fails()) {

            if(isset($data['id']) && intval($data['id']) > 0) {
                $advert = \Besplatnee::adverts()->update($data);
            } else {
                $advert = \Besplatnee::adverts()->add($data);
                $id = $advert->id;
            }

            $advert->megaAdvert()->delete();
            $advert->megaAdvert()->save(new \App\MegaAdvert($data));

            return $this->megaAdvertEdit($request, $id);

        } else {
            if(isset($data['id']) && intval($data['id']) > 0) {
                return $this->megaAdvertEdit($request, $id);
            } else {
                return $this->megaAdvertCreate($request);
            }
        }
    }

    public function advertSave(Request $request, $id) {
        $data = $request->all();

        if(isset($data['id']) && intval($data['id']) > 0) {
            $advert = \App\Advert::find($id);
            if(\Gate::denies('edit', $advert)) { abort(403); }

            unset($data['creator_id']);
        } else {
            $advert = new \App\Advert;
        }

        if(\Gate::denies('give', $advert)) {
            unset($data['owner_id']);
        }

        if(\Gate::denies('approved', $advert)) {
            unset($data['active']);
            unset($data['blocked']);
            unset($data['approved']);
        }

        if(\Gate::denies('remove', $advert)) {
            unset($data['status']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:80|min:5',
            'content' => 'required|max:1000|min:5',
            'phone' => 'required|numeric|min:8|max:999999999999999',
            'main_phrase' => 'required|max:100|min:5',
            'city_ids.*' => 'required|in:' . \App\City::where('active', true)
                ->get()
                ->implode('id', ','),
            'heading_id' => 'required|in:' . \App\Heading::where('active', true)
                ->where('parent_id', '<>', 0)
                ->get()
                ->implode('id', ','),
            'price' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'extend_content' => 'max:1000',
            'fullname' => 'max:80',
        ], [
            'required' => 'Это поле обязательно.',
            'max' => 'Длинна этого поля не должна превышать :max.',
            'min' => 'Длинна этого поля не должна быть больше :min.',
            'email' => 'Некорректный email',
            'phone' => 'Некорректный телефон',
            'price.regex' => 'Некорректная цена',
        ]);

        $this->form_errors = $validator->messages();

        if(!$validator->fails()) {

            if(isset($data['id']) && intval($data['id']) > 0) {
                \Besplatnee::adverts()->update($data);
            } else {
                $advert = \Besplatnee::adverts()->add($data);
                $id = $advert->id;
            }

            return $this->advertEdit($request, $id);

        } else {
            if(isset($data['id']) && intval($data['id']) > 0) {
                return $this->advertEdit($request, $id);
            } else {
                return $this->advertCreate($request);
            }
        }
    }

    public function userList(Request $request) {
        $this->breadcrumbs('Пользователи', route('admin.list', ['model' => 'users']));

        $this->filter = $request->input('filter', $request->session()->get('user_filter', 'admins'));
        $request->session()->put('user_filter', $this->filter);

        $users = [];
        switch($this->filter) {
            case 'all': $users = \App\User::paginate(10); break;
            case 'admins': $users = \App\User::whereHas('groups', function ($query) {
                $query->whereNotIn('id', [1, 3]);
            })->paginate(10); break;
            default: abort(404); break;
        }

        return view('admin.list.users', [
            'version' => $this->version,
            'users' => $users,
            'filter' => $this->filter,
        ]);
    }

    public function userEdit(Request $request, $id) {
        $this->breadcrumbs('Редактировать пользователя', route('admin.edit', ['model' => 'users', 'id' => $id]));

        $user = \App\User::find($id);
        if(!$user) { abort(404); }

        if(\Gate::denies('edit', $user)) { abort(403); }

        request()->merge($user->toArray());
        request()->merge([
            'verify' => $user->phone()->first()->verify,
            'group_id' => $user->groups()->first()->id,
        ]);

        $groups = [];
        foreach(\App\Group::all() as $group) {
            $groups[] = [
                'title' => $group->name,
                'value' => $group->id,
            ];
        }

        return view('admin.edit.user', [
            'version' => $this->version,
            'user' => $user,
            'groups' => $groups,
            'errors' => $this->form_errors,
        ]);
    }

    public function userCreate(Request $request) {
        $this->breadcrumbs('Создание пользователя', route('admin.create', ['model' => 'users']));

        $groups = [];
        foreach(\App\Group::all() as $group) {
            $groups[] = [
                'title' => $group->name,
                'value' => $group->id,
            ];
        }

        return view('admin.create.user', [
            'version' => $this->version,
            'user' => new \App\User,
            'groups' => $groups,
            'errors' => $this->form_errors,
        ]);
    }

    public function userSave(Request $request, $id) {
        $data = $request->all();
        $isNew = !isset($data['id']) || intval($data['id']) <= 0;

        if(!$isNew) {
            $user = \App\User::find($id);
            if(\Gate::denies('edit', $user)) { abort(403); }

            unset($data['creator_id']);

        } else {
            $user = new \App\User;

            if(\Gate::denies('create', $user)) { abort(403); }
        }

        if(!$request->has('new-password') && !$isNew) {
            unset($data['password']);
        }

        $matches = [
            'phone' => 'required|numeric|min:8|max:999999999999999',
            'verify' => 'required|in:0,1',
            'group_id' => 'required|in:' . \App\Group::all()->implode('id', ','),
        ];

        if($isNew) {
            $matches['password'] = 'required';
            $matches['phone'] .= '|unique:users,phone';
        }

        $validator = Validator::make($request->all(), $matches, [
            'required' => 'Это поле обязательно.',
            'max' => 'Длинна этого поля не должна превышать :max.',
            'min' => 'Длинна этого поля не должна быть больше :min.',
            'email' => 'Некорректный email',
            'phone' => 'Некорректный телефон',
            'price.regex' => 'Некорректная цена',
        ]);

        $this->form_errors = $validator->messages();

        if(!$validator->fails()) {

            if(!$isNew) {
                \Besplatnee::users()->update($data);
            } else {
                $user = \Besplatnee::users()->add($data);

                $id = $user->id;
            }

            if(isset($data['group_id'])) {
                $user->groups()->sync([$data['group_id']]);
            }

            return $this->userEdit($request, $id);

        } else {
            if(!$isNew) {
                return $this->userEdit($request, $id);
            } else {
                return $this->userCreate($request);
            }
        }
    }

    public function bannerList(Request $request) {
        $this->breadcrumbs('Размещенные баннеры', route('admin.list', ['model' => 'banners']));

        $this->filter = $request->input('filter', $request->session()->get('banner_filter', 'all'));
        $request->session()->put('banner_filter', $this->filter);

        $banners = [];
        switch($this->filter) {
            case 'all': $banners = \App\Banner::paginate(10); break;
            default: abort(404); break;
        }

        return view('admin.list.banners', [
            'version' => $this->version,
            'banners' => $banners,
            'filter' => $this->filter,
        ]);
    }

    public function bannerEdit(Request $request, $id) {
        $this->breadcrumbs('Редактировать баннер', route('admin.edit', ['model' => 'banners', 'id' => $id]));

        $banner = \App\Banner::find($id);
        if(!$banner) { abort(404); }

        if(\Gate::denies('edit', $banner)) { abort(403); }

        request()->merge($banner->toArray());

        $positions = [
            ['title' => 'В шапке', 'value' => 'header'],
            ['title' => 'В футере', 'value' => 'footer'],
            ['title' => 'В объявлениях', 'value' => 'advert'],
            ['title' => 'Слева', 'value' => 'left'],
            ['title' => 'Справа', 'value' => 'right'],
            ['title' => 'Вверху', 'value' => 'top'],
        ];

        return view('admin.edit.banner', [
            'version' => $this->version,
            'banner' => $banner,
            'positions' => $positions,
            'errors' => $this->form_errors,
        ]);
    }

    public function bannerCreate(Request $request) {
        $this->breadcrumbs('Создать баннер', route('admin.create', ['model' => 'banners']));

        $positions = [
            ['title' => 'В шапке', 'value' => 'header'],
            ['title' => 'В футере', 'value' => 'footer'],
            ['title' => 'В объявлениях', 'value' => 'advert'],
            ['title' => 'Слева', 'value' => 'left'],
            ['title' => 'Справа', 'value' => 'right'],
            ['title' => 'Вверху', 'value' => 'top'],
        ];

        return view('admin.edit.banner', [
            'version' => $this->version,
            'positions' => $positions,
            'banner' => new \App\Banner,
            'errors' => $this->form_errors,
        ]);
    }

    public function bannerSave(Request $request, $id) {
        $data = $request->all();

        if(isset($data['id']) && intval($data['id']) > 0) {
        
            $banner = \App\Banner::find($id);
            if(\Gate::denies('edit', $banner)) { abort(403); }

            unset($data['creator_id']);
        } else {
            $banner = new \App\Banner;
        }

        $validator = Validator::make($request->all(), [
            'hover_text' => 'required',
            'url' => 'required',
            'position' => 'required|in:top,header,footer,left,right,advert',
            'image' => 'required',
        ], [
            'required' => 'Это поле обязательно.',
        ]);

        $this->form_errors = $validator->messages();

        if(!$validator->fails()) {

            if(isset($data['id']) && intval($data['id']) > 0) {
                \Besplatnee::banners()->update($data);
            } else {
                $banner = \Besplatnee::banners()->add($data);
                $id = $banner->id;
            }

            return $this->bannerEdit($request, $id);

        } else {
            return $this->bannerCreate($request);
        }

        
    }

    public function bannerRemove(Request $request, $id) {
        $banner = \App\Banner::find($id);
        if(!$banner) { abort(404); }

        if(\Gate::denies('remove', $banner)) {
            abort(403);
        }

        $banner->delete();

        return redirect()->route('admin.list', ['model' => 'banners']);
    }

    public function userRemove(Request $request, $id) {
        $user = \App\User::find($id);
        if(!$user) { abort(404); }

        if(\Gate::denies('remove', $user)) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('admin.list', ['model' => 'users']);
    }

    public function advertRemove(Request $request, $id) {
        $advert = \App\Advert::find($id);
        if(!$advert) { abort(404); }

        if(\Gate::denies('remove', $advert)) {
            abort(403);
        }

        $advert->delete();

        return redirect()->route('admin.list', ['model' => 'adverts']);
    }
}
