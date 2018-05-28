<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Group;
use App\Phone;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Facades\Besplatnee $besplatnee)
    {
        $this->middleware('guest');

        $this->initialize($besplatnee);

        $this->breadcrumbs('Регистрация', route('register'));

        $this->header('Регистрация');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fullname' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|numeric|min:8|unique:users',
        ],[
            'required' => 'Это поле обязательно.',
            'max' => 'Длинна этого поля не должна превышать :max.',
            'min' => 'Длинна этого поля не должна быть больше :min.',
            'email' => 'Некорректный email',
            'phone' => 'Некорректный телефон',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = \Besplatnee::users()->add($data);

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        \Auth::logout();
    }
}
