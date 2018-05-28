<?php
namespace App\Facades;

use App\User;
use App\Group;
use App\GroupUser;
use App\Settings;
use App\Phone;
use Illuminate\Support\Facades\Validator;

class UsersManager extends ModelsManager 
{
	public $model = User::class;

	public function validate($data, $isValidate = true) {
		$validator = Validator::make($data, [
            'phone' => 'required|phone',
            'password' => 'required|min:' . Settings::getOption('users.password.size.min', 6) . '|confirmed',
            'email' => 'nullable|email',
            'fullname' => 'nullable|max:' . Settings::getOption('users.fullname.size', 255),
            'firstname' => 'nullable|max:' . Settings::getOption('users.firstname.size', 255),
            'lastname' => 'nullable|max:' . Settings::getOption('users.lastname.size', 255),
            'patronymic' => 'nullable|max:' . Settings::getOption('users.patronymic.size', 255),
            'gender' => 'nullable|in:0,1,2',
        ]);

        return $isValidate ? ($validator->fails() ?? $validator->messages()) : $validator;
	}

	public function validateAnonymous($data, $isValidate = true) {
		$data['password'] = uniqid();
		$data['password_confirmation'] = $data['password'];

		return $this->validate($data, $isValidate);
	}

	public function validateRegistraton($data, $isValidate = true) {
		return $this->validate($data, $isValidate);
	}

	public function add($data) {
		$user = parent::add($data);

		$phone = new Phone;

		$phone->create([
			'user_id'	=>	$user->id,
			'verify'	=>	0,
			'blocked'	=>	0,
		]);

		$group = new GroupUser;

		$group->create([
			'user_id'	=>	$user->id,
			'group_id'	=>	1,
		]);

		return $user;
	}

	public function setData($user, $data) {
		$user->fill($data);

		if(isset($data['password'])) {
			$user->password = $this->generatePassword($data['password']);
		}

		if(isset($data['fullname'])) {
			$namechunks = explode(' ', $data['fullname']);
			$user->firstname = $namechunks[0] ?? '';
			$user->patronymic = $namechunks[1] ?? '';
			$user->lastname = $namechunks[2] ?? '';
		}

		try {
			$user->save();
			if(isset($data['verify'])) {
				$phone = $user->phone()->first();
				if($phone) {
					$phone->verify = $data['verify'];
					$phone->save();
				}
			}

		} catch (\Illuminate\Database\QueryException $e) {
			die($e->getMessage());
            return null;
        } catch (PDOException $e) {
			die($e->getMessage());
            return null;
        }   

		if(isset($data['city_ids']) && is_array($data['city_ids'])) {
			$user->cities()->sync($data['city_ids']);
		}

		if(isset($data['region_ids']) && is_array($data['region_ids'])) {
			$user->regions()->sync($data['region_ids']);
		}

		if(isset($data['country_ids']) && is_array($data['country_ids'])) {
			$user->countries()->sync($data['country_ids']);
		}

		return $user;
	}

	public function get($id, $removed = false) {
		if($removed && $this->hasSoftDelete()) {
			return User::withTrashed()->with('groups')->with('phone')->find($id);
		} else {
			return User::with('groups')->with('phone')->find($id);
		}
	}

	public function generatePassword($password) {
		return bcrypt($password);
	}

	public function addToGroups($id, $group_ids) {
		$user = User::find($id);
		$user->groups()->attach($group_ids);
	}

	public function setGroups($id, $group_ids) {
		$user = User::find($id);
		$user->groups()->sync($group_ids);
	}

	public function removeFromGroups($id, $group_ids) {
		$user = User::find($id);
		$user->groups()->detach($group_ids);
	}

	public function addToCities($id, $city_ids) {
		$user = User::find($id);
		$user->cities()->attach($city_ids);
	}

	public function setCities($id, $city_ids) {
		$user = User::find($id);
		$user->cities()->sync($city_ids);
	}

	public function removeFromCities($id, $city_ids) {
		$user = User::find($id);
		$user->cities()->detach($city_ids);
	}

	public function blockedPhone($phone) {
		if(empty($phone)) { return ; }

		$user = \App\User::where('phone', $phone)->first();

		if(!$user) {
			$user = new \App\User([
				'phone' => $phone,
			]);

			$user->password = bcrypt(md5(uniqid()));

			$user->save();
		}

		$user->blocked = true;
		$user->blocked_at = \Carbon\Carbon::now()->toDateTimeString();
		$user->save();
	}

	public function unBlocked($id) {
		$user = \App\User::find($id);

		if(!$user) { return ; }

		$user->blocked = false;
		$user->blocked_at = null;
		$user->save();
	}

	public function getList($properties) {
		$properties = array_merge([
			'active' => true,
			'blocked' => false,
			'groups' => [],
			'exclude' => [],
			'paginate' => false,
			'maxLimit' => 0,
			'limit' => 0,
			'offset' => 0,
			'return' => 'model', // model, array
			'sort' => ['updated_at' => 'DESC'],
			'search' => '',
		], $properties);

		if($properties['maxLimit'] > 0 && $properties['limit'] > $properties['maxLimit']) {
			$properties['limit'] = $properties['maxLimit'];
		}

		$users = User::with('groups');

		if($properties['active'] !== null) {
			$users->where('active', $properties['active']);
		}

		if($properties['blocked'] !== null) {
			$users->where('blocked', $properties['blocked']);
		}

		if(count($properties['groups']) > 0) {
			$users->whereHas('groups', function ($query) use ($properties) {
				$query->whereIn('id', $properties['groups']);
			});
		}

		if(count($properties['exclude']) > 0) {
			$users->whereNotIn('id', $properties['exclude']);
		}

		if(!empty($properties['search'])) {
			$phone = preg_replace('/[^0-9+]/', '', $properties['search']);
			$users->where('phone', 'like', '%' . $phone . '%');
		}

		if($properties['maxLimit'] > 0) {
			$users->take($properties['maxLimit']);
		}

		foreach($properties['sort'] as $sortby => $sortdir) {
			$users->orderBy($sortby, $sortdir);
		}

		if($properties['paginate']) {
			$users = $users->paginate($properties['limit']);
		} else {
			if($properties['limit'] > 0) {
				$users->take($properties['limit'])->skip($properties['offset']);
			}

			$users = $users->get();
		}

		switch($properties['return']) {
			case 'model': break;
			case 'array': 
					$users = $users->toArray();
				break;
			default: break;
		}

		return $users;
	}
}