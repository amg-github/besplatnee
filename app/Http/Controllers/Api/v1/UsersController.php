<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Phone;
use Gate;

class UsersController extends Controller
{
    public function login(Request $request) {
    	if(Auth::check()) {
    		return response(['t' => '1']);
    	} else {
    		return redirect('/login?application=' . $request->input('__authenticatedApp'));
    	}
    }

    public function get(Request $request) {
    	if($request->has('ids')) {
    		$ids = $request->input('ids');
    		if(!is_array($ids)) {
    			$ids = [$ids];
    		}
        } else {
            $ids = [Auth::user()->id];
        }

    	$users = User::whereIn('id', $ids)->with('city')->with('phones')->with('groups')->get();
        $usersArray = [];

        foreach($users as $user) {
            $userArray = $user->toArray();
            if(Gate::denies('get_ext', $user)) {
                unset($userArray['phones']);
                unset($userArray['groups']);
                unset($userArray['email']);
            }
            $usersArray[] = $userArray;
        }
        

    	return response([
    		'success' => true,
    		'users' => $usersArray,
    	]);
    }

    public function update(Request $request) {
        if($request->has('id')) {
            $id = $request->input('id');
        } else {
            $id = Auth::user()->id;
        }

        try {
            $user = User::findOrFail($id);
            if(Gate::denies('update', $user)) {
                return response([
                    'success' => false,
                    'message' => 'access_denied',
                ], 403);
            } else {
                // update $user
                $information = [];
                $user->name = $request->input('name', $user->name);
                //$user->email = $request->input('email', $user->email);
                if($request->has('password')) {
                    $user->password = bcrypt($request->input('password'));
                }
                
                $user->name = $request->input('name', $user->name);
                $user->photo = $request->input('photo', $user->photo);
                $user->gender = $request->input('gender', $user->gender);
                $user->city_id = $request->input('city_id', $user->city_id);

                try {
                   
                    $information['user'] = [
                        'success' => $user->save(),
                        'data' => $user->toArray(),
                    ];

                    // save pivots
                    if($request->has('phones')) {
                        $phones = [];
                        foreach($request->input('phones') as $phone) { 
                            try {
                                $phone = Phone::where('phone', $phone['phone'])->firstOrFail();
                                if($phone->user_id == $user->id) {
                                    $phones[] = $phone;
                                } else {
                                    $information['phone'] = [
                                        'success' => false,
                                        'data' => $phone,
                                        'message' => 'phone_exists',
                                    ];
                                }
                            } catch(\Throwable $e) {
                                $phones[] = new Phone($phone);
                            }
                        }

                        $user->phones()->saveMany($phones);
                    }

                    if($request->has('groups')) {
                        $user->groups()->create($request->input('groups'));
                    }

                    return response([
                        'success' => true,
                        'information' => $information,
                    ]);
                } catch(\Throwable $e) {
                    return response([
                        'success' => false,
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        } catch(\Throwable $e) {
            return response([
                'success' => false,
                'message' => 'user_not_found',
            ], 404);
        }
    }
}
