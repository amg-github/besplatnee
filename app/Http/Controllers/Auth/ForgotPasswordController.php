<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller,
    Illuminate\Http\Request,
    Illuminate\Foundation\Auth\SendsPasswordResetEmails,
    Illuminate\Support\Facades\Password,
    App\Facades\Besplatnee,
    Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    private $expire_time = 300;

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Facades\Besplatnee $besplatnee)
    {
        $this->middleware('guest');

        $this->initialize($besplatnee);

        $this->breadcrumbs('Восстановление пароля', route('password.request'));
        $this->header('Восстановление пароля');
    }

    public function showLinkRequestForm(Request $request)
    {
        return view('auth.passwords.email');
    }

    public function resetRedirect($token)
    {
        $link = 'password/reset/'.$token;
        return redirect($link);
    }

    public function isExpiredToken($field_name, $value)
    {
        $created_at = DB::table('password_resets')->where($field_name, $value)->value('created_at');
        
        if (!is_null($created_at)) {

            $diff = $this->expire_time - (time() - strtotime($created_at));

            if ($diff > 0)
                return redirect()->back()
                    ->withErrors(['phone' => 'Превышен лимит запросов на восстановление пароля, ожидайте '. $diff .' секунд']);
        }

        return false;
    }

    public function sendResetLinkEmail(Request $request)
    {
        $phone = $request->input('phone');

        $user = \App\User::where('phone', $phone)->first();

        if($user) {
            if(!empty($user->email)) {

                $expired = $this->isExpiredToken('email', $user->email);

                if (!$expired)
                    $this->sendResetMail($user);
                else 
                    return $expired;

                return redirect('login');

            } else {

                $user_reset = DB::table('password_resets')->where('phone', $phone)->first();

                if (!is_null($user_reset)) {
                    $code_input = $request->input('verify_code');

                    if (!empty(trim($code_input))) {

                        $token = sha1($code_input.':'.$user->id.$_ENV['APP_TOKEN_SECRET']);

                        if ($user_reset->token == $token) {
                            //точка входа при корректном коде


                            return $this->resetRedirect($token);


                            //конец
                        } else {
                            return redirect('password/reset')
                                ->withErrors([
                                    'need_verify' => 'Неправильный проверочный код',
                                ])
                                ->withInput([
                                    'phone' => $request->input('phone'),
                                    'password' => $request->input('password'),
                                ]);
                        }
                    } 

                }

                $expired = $this->isExpiredToken('phone', $user->phone);

                if (!$expired)
                    $this->sendResetPhone($user);
                else 
                    return $expired;

                return redirect('password/reset')
                    ->withErrors([
                        'need_verify' => 'Введите проверочный код',
                    ])
                    ->withInput([
                        'phone' => $request->input('phone'),
                        'password' => $request->input('password'),
                    ]);


            }
        } else {

            return redirect()->back()
                    ->withErrors(['phone' => 'Пользователь не найден']);
        }
    }

    protected function sendResetPhone($user) {
        $code = rand(1000, 9999);

        $phone = $user->phone;

        Besplatnee::sendVerifyCode($phone, $code);

        $token = sha1($code.':'.$user->id.$_ENV['APP_TOKEN_SECRET']);

        if (!is_null($reset = DB::table('password_resets')->where('phone', $phone)->first())) {
            $response = DB::table('password_resets')
                        ->where('phone', $phone)
                        ->update(['token' => $token, 'created_at' => date('Y-m-d H:i:s')]);
        } else {
            $response = DB::table('password_resets')
                        ->insert(['phone' => $phone, 'token' => $token, 'created_at' => date('Y-m-d H:i:s')]
                    );
        }

        return $response;
    }

    protected function sendResetMail($user) {
        $email = $user->email;

        $code = rand(1000, 9999);

        $token = sha1($code.':'.$user->id.$_ENV['APP_TOKEN_SECRET']);

        $link = url('password/reset').'/'.$token;

        mail($email, 'Восстановление пароля', 'Ссылка для восстановления пароля: ' . $link . ' Газета Ещё БЕСПЛАТНЕЕ');


        if (!is_null($reset = DB::table('password_resets')->where('email', $email)->first())) {
            $response = DB::table('password_resets')
                        ->where('email', $email)
                        ->update([  
                                    'token' => $token, 
                                    'created_at' => date('Y-m-d H:i:s')
                                ]
                        );
        } else {
            $response = DB::table('password_resets')
                        ->insert([
                                    'email' => $email, 
                                    'token' => $token, 
                                    'created_at' => date('Y-m-d H:i:s')
                                ]
                        );
        }

        return $response;
    }
}
