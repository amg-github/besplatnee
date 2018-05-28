<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Application;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/office';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Facades\Besplatnee $besplatnee)
    {
        $this->middleware('guest')->except('logout');

        $this->initialize($besplatnee);

        $this->breadcrumbs('Вход в систему', route('login'));
        $this->header('Вход в систему');
    }

    public function username() 
    {
        return 'phone';
    }

    protected function authenticated(Request $request, $user)
    {
        // отсеиваем неактивных юзеров и заблокированные номера
        if(!$this->isActive($user)) {
            Auth::logout();
            return redirect('login')
                ->withErrors([
                    'user_inactive' => 'Извините, аккаунт заблокирован',
                ]);
        }

        if(!$user->phone()->first()->verify) {
            if($user->phone()->first()->verify_code == '') {
                $user->phone()->first()->sendingVerifyCode();
                Auth::logout();
                return redirect('login')
                    ->withErrors([
                        'need_verify' => 'Ваш номер телефона не проверен',
                    ])
                    ->withInput([
                        'phone' => $request->input('phone'),
                        'password' => $request->input('password'),
                    ]);
            } else {
                if(!$user->phone()->first()->verifycation($request->input('verify_code'))) {
                    Auth::logout();
                    return redirect('login')
                        ->withErrors([
                            'need_verify' => 'Неверный код подтверждения',
                        ])
                        ->withInput([
                            'phone' => $request->input('phone'),
                            'password' => $request->input('password'),
                        ]);
                }
            }
        }
        
        try {
            $application = Application::where('key', $request->input('__applicationKey'))->first();
        } catch (\Throwable $e) {
            return ;
        }

        if($application) {

            $pivotData = ['authorization_code' => $code = sha1($application->id.':'.$user->id.$_ENV['APP_TOKEN_SECRET'])];

            if ($application->users->contains($user)) {
                $application->users()->updateExistingPivot($user->id, $pivotData);
            } else {
                $application->users()->attach($user->id, $pivotData);
            }

            $this->redirectTo .= '#' . $code;
        }
    }

    protected function isActive($user) 
    {
        $userActive = $user->active && !$user->blocked;
        $phoneActive = !$user->phone()->first()->blocked;

        return $userActive && $phoneActive;
    }
}
