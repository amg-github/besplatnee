<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Gate;

class Phone extends Model
{
    protected $fillable = [
        'user_id', 
        'verify',
        'blocked', 
    ];

    protected $hidden = [
    	'id', 
    	'verify_code',
    ];

    protected $casts = [
        'verify' => 'boolean',
        'blocked' => 'boolean',
    ];

    public function generateVerifyCode() {
        $code = hexdec(uniqid());

        return substr($code, strlen($code) - 6, 6);
    }

    public function sendingVerifyCode() {
        $this->verify_code = $this->generateVerifyCode();
        $this->save();

    	\Besplatnee::sendVerifyCode($this->user->phone, $this->verify_code);
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function verifycation($code) {
        if($code != '' && $this->verify_code == $code) {
            $this->verify_code = '';
            $this->verify = true;
            $this->save();
            return true;
        }

        return false;
    }
}
