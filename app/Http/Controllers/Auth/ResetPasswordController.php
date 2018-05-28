<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');

        $this->initialize($besplatnee);

        $this->breadcrumbs('Восстановление пароля', '');
        $this->header('Восстановление пароля');

    }

    protected function isTokenCorrect($token) { 
        if (!is_null($reset = DB::table('password_resets')->where('token', $token)->first())) {
            return true;
        }
    }

    protected function isByEmail($token) { 
        $reset_data = DB::table('password_resets')->where('token', $token)->first();

        if (!is_null($reset_data->email)) {
            return $reset_data->email;
        } else {
            return false;
        }
    }

    public function showResetForm(Request $request, $token = null)  
    {

        if ($this->isTokenCorrect($token)) {

            if ($email = $this->isByEmail($token)) {

                return view('auth.passwords.reset')->with(
                    ['token' => $token, 'email' => $email]
                );

            } else {

                return view('auth.passwords.reset')->with(
                    ['token' => $token]
                );
            }

        } else {
            return redirect('password/reset');
        }
    }


    public function reset(Request $request)
    {

        $this->validate($request, $this->rules($this->isByEmail($request->token)), $this->validationErrorMessages());

        if ($request->password != $request->password_confirmation)
            return redirect()->back()
                    ->withErrors(['password' => 'Пароли не совпадают']);

        $reset_data = DB::table('password_resets')->where('token', $request->token)->first();

        if ($this->isByEmail($request->token)) {

            $user = \App\User::where('email', $reset_data->email)->first();

            $this->resetPassword($user, $request->password);

        } else {

            $user = \App\User::where('phone', $reset_data->phone)->first();

            $this->resetPassword($user, $request->password);
            $this->setEmail($user, $request->email);

        }

        DB::table('password_resets')->where('token', $request->token)->delete();

        return redirect('office');


    }

    public function rules($reset_email = true) {
        if ($reset_email)
            return [
                'token' => 'required',
                'password' => 'required|min:6'
            ];
        
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];
    }

    protected function setEmail($user, $email) {
        $user->email = $email;

        $user->save();
    }


}
