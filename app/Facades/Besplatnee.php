<?php 
namespace App\Facades;

use Illuminate\Support\Facades\Facade,
	Illuminate\Support\Facades\DB;

class Besplatnee extends Facade 
{
	private static $users = null;
	private static $adverts = null;
	private static $cities = null;
	private static $headings = null;
	private static $organizations = null;
	private static $banners = null;
	private static $sites = null;
	private static $forms = null;

	protected static function getFacadeAccessor() { return 'besplatnee'; }

	/* Users */
	public static function users() {
		if(self::$users == null) {
			self::$users = new UsersManager;
		}

		return self::$users;
	}

	/* Adverts */
	public static function adverts() {
		if(self::$adverts == null) {
			self::$adverts = new AdvertsManager;
		}

		return self::$adverts;
	}

	/* Headings */
	public static function headings() {
		if(self::$headings == null) {
			self::$headings = new HeadingsManager;
		}

		return self::$headings;
	}

	/* Cities */
	public static function cities() {
		if(self::$cities == null) {
			self::$cities = new CitiesManager;
		}

		return self::$cities;
	}

	/* Organizations */
	public static function organizations() {
		if(self::$organizations == null) {
			self::$organizations = new OrganizationsManager;
		}

		return self::$organizations;
	}

	/* Banners */
	public static function banners() {
		if(self::$banners == null) {
			self::$banners = new BannersManager;
		}

		return self::$banners;
	}

	/* Sites */
	public static function sites() {
		if(self::$sites == null) {
			self::$sites = new SiteManager;
		}

		return self::$sites;
	}

	public static function forms() {
		if(self::$forms == null) {
			self::$forms = new FormManager;
		}

		return self::$forms;
	}

	public static function sendSms($phone, $text) {
		$queryParams = [
			'send' => $text,
			'to' => $phone,
			'from' => env('SMSPILOT_API_SENDER'),
			'apikey' => env('SMSPILOT_API_KEY'),
			'format' => 'json',
		];

		$httpClient = new \GuzzleHttp\Client();
		$response = $httpClient->request('GET', env('SMSPILOT_API_URL'), ['query' => $queryParams]);
		
		if($response->getStatusCode()) {
			$responseString = $response->getBody();

			return @json_decode($responseString, true);
		} else {
			return null;
		}
	}

	public static function sendVerifyCode($phone, $code) {
		return Besplatnee::sendSms($phone, 'Ваш код доступа: ' . $code . ' Газета Ещё БЕСПЛАТНЕЕ');
	}

	public static function updateLanguageFiles(array $phrases, $namespace = 'site') {
		foreach($phrases as $lang => $_phrases) {
			$translations = \Lang::getLoader()->load($lang, $namespace);

			if($translations && is_array($translations)) {
				foreach($_phrases as $key => $value) {
					$translations[$key] = $value;
				}

				$file_path = base_path() . '/resources/lang/' . $lang . '/' . str_replace('.', '/', $namespace) . ".php";
				\File::put($file_path, "<?php\n\nreturn " . var_export($translations, true) . ";\n?>");
			}
		}
	}


	public static function getFromLangFile($lang, $namespace) {
		$namespace = explode('.', $namespace);

		$file_path = base_path() . '/resources/lang/' . $lang . '/' . $namespace[0] . ".php";

		$array = include $file_path;

		$name = (isset($array[$namespace[1]]) ? $array[$namespace[1]] : '');

		return $name;
	}
	
}