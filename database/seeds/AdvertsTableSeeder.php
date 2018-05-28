<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AdvertsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Model::reguard();
        $offset = 100;
        $limit = 10;
        $max = $offset + $limit;
        while($adverts = $this->getAdverts($offset)) {
            if($offset >= $max) {
                break;
            }

        	foreach($adverts as $adv) {
                if(!isset($adv['city'])) { continue; }

                $a = \DB::table('adverts')->whereNull('created_at')->orderBy('id')->first();
                \DB::table('adverts')->where('id', $a->id)->update([
                    'created_at' => $adv['created_at'],
                    'fakeupdated_at' => $adv['created_at'],
                ]);

                /*$adv['phone'] = str_replace('+7', '8', $adv['phone']);
                $adv['phone'] = str_replace(array ('-', ' '), '', $adv['phone']);

        		$user = \App\User::where('phone', $adv['phone'])->first();
        		if(!$user) {

                    $email = !empty($adv['user']['email']) ? $adv['user']['email'] : '';
                    $password = !empty($adv['user']['pass']) ? $adv['user']['pass'] : uniqid();
                    $migrated = !empty($adv['user']['pass']);
                    $fullname = [];
                    if(!empty($adv['user']['name'])) { $fullname[] = $adv['user']['name']; }
                    if(!empty($adv['user']['surname'])) { $fullname[] = $adv['user']['surname']; }
                    if(!empty($adv['user']['patronymic'])) { $fullname[] = $adv['user']['patronymic']; }

        			$user = \Besplatnee::users()->add([
        				'phone' => $adv['phone'],
        				'email' => $email,
        				'password' => $password,
                        'fullname' => implode(' ', $fullname),
                        'migrated' => $migrated,
        			]);
        		}

                $city = \App\AreaName::where('language', 'ru')->where('nominative_local', $adv['city'])->first();

                $photos = [];
                foreach($adv['photos'] as $photo) {
                    if(!empty($photo)) {
                        $photos[] = 'storage/uploads/' . $photo;
                    }
                }

                if($city) {
            		$adv['owner_id'] = $user->id;
                    $adv['city_ids'] = [str_replace('city_', '', $city->key)];
        	        Besplatnee::adverts()->add([
                        'heading_id' => $adv['headings'][0],
                        'name' => !empty($adv['name']) ? $adv['name'] : implode(' ', array_slice(explode(' ', $adv['content']), 0, 4)),
                        'content' => $adv['content'],
                        'extend_content' => $adv['extend_content'],
                        'main_phrase' => $adv['main_phrase'],
                        'city_ids' => $adv['city_ids'],
                        'price' => intval($adv['price']),
                        'photos' => $photos,
                        'videos' => $adv['videos'],
                        'show_phone' => $adv['hide_phone'] == 0,
                        'latitude' => isset($adv['coords'][0]) ? floatval($adv['coords'][0]) : 0.0,
                        'longitude' => isset($adv['coords'][1]) ? floatval($adv['coords'][1]) : 0.0,
                        'dubplicate_in_all_cities' => $adv['all_cities'],
                        'owner_id' => $user->id,
                        'created_at' => $adv['created_at'],
                        'site_url' => $adv['url'],
                        'active' => true,
                        'approved' => true,
                    ]);
                }*/
    	    }

            $offset++;
        }

	    Model::unguard();
    }

    public function getAdverts($offset = 0) {
        $path = dirname(__FILE__) . '/data/data-' . $offset . '.php';
    	return file_exists($path) ? require($path) : null;
    }
}
