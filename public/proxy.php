<?php 
define('PROXY_SECURE_KEY', '&E^yv87cw*r7cw8a&');

// reqeust functions

function make_url($url, $params = [], $type = 'full', $ssl = false) {
	if(mb_strpos($url, 'http://', 0, 'UTF-8') === 0) {
		$url = mb_substr($url, 7, null, 'UTF-8');
		$type = 'full';
		$ssl = false;
	}

	if(mb_strpos($url, 'https://', 0, 'UTF-8') === 0) {
		$url = mb_substr($url, 8, null, 'UTF-8');
		$type = 'full';
		$ssl = true;
	}

	list($url, $http_request) = array_pad(explode('?', $url), 2, '');

	$url = trim($url, '/');
	if(!empty($http_request)) {
		parse_str($http_request, $http_request);
		$params = array_merge(
			$http_request,
			$params
		);
	}

	switch($type) {
		case 'full':
				$url = ($ssl ? 'https://' : 'http://') . $url;
			break;
		case 'abs':
				$url = '/' . $url;
			break;
		case 'rel':
			break;
	}

	if(count($params)) {
		$url .= '?' . http_build_query($params);
	}

	return $url;
}

if(!function_exists('curl_file_create')) {
	function curl_file_create($filename, $mimetype = '', $postname = '') {
        return "@{$filename};filename="
            . ($postname ?: basename($filename))
            . ($mimetype ? ";type=$mimetype" : '');
    }
}

function request($url, $method = 'get', $get_params = [], $post_params = [], $files = []) {
	$url = make_url($url, $get_params, 'full');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);

	foreach($files as $name => $fileOrFileList) {
		if(is_array($fileOrFileList)) {
			foreach($fileOrFileList as $i => $file) {
				$post_params[$name . '[' . $i . ']'] = curl_file_create($file);
			}
		} else {
			$post_params[$name] = curl_file_create($file);
		}
	}

	switch(strtolower($method)) {
		case 'get':
				curl_setopt($ch, CURLOPT_POST, false);
			break;
		case 'post':
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
			break;
		case 'delete':

			break;
		case 'put':

			break;
	}

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_VERBOSE, true);

	$result = curl_exec($ch);

	curl_close($ch);

	return $result;
}

function request_get($url, $params = []) {
	return request($url, 'get', $params);
}

function request_post($url, $params = [], $files = []) {
	return request($url, 'post', [], $params, $files);
}

$key = isset($_POST['key']) ? $_POST['key'] : '';
$url = isset($_POST['url']) ? $_POST['url'] : '';
$method = isset($_POST['method']) ? $_POST['method'] : 'get';
$params = isset($_POST['params']) ? $_POST['params'] : array ();

if($key === PROXY_SECURE_KEY) {
	echo request($url, $method, $params);
}