<?php 

	if (!session_id()) {
	    session_start();
	}

	require_once __DIR__ . '/vendor/autoload.php';

	function FacebookClient($app_id, $app_secret, $default_graph_version, $redirect){

		$fb = new Facebook\Facebook([
			'app_id' => $app_id,
			'app_secret' => $app_secret,
			'default_graph_version' => $default_graph_version,
		]);

		$helper = $fb->getRedirectLoginHelper();

		$_SESSION['FBRLH_state']= (isset($_GET['state'])) ? $_GET['state'] : '';

		try {

			$access_token = $helper->getAccessToken();

		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			
			//echo 'Graph returned an error : ' . $e->getMessage();
			return 'GRAPH';

		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			
			//echo 'Facebook SDK returned an error : ' . $e->getMessage();
			return 'SDK';

		}

		if(!isset($access_token)){

			$permission = ['email'];
			$loginUrl = $helper->getLoginUrl($redirect, $permission);
			return '<a href="'.$loginUrl.'">Login with Facebook</a>';

		} else {

			$fb->setDefaultAccessToken($access_token);
			$respose = $fb->get('/me?fields=id,name,last_name,email,picture,gender', $access_token->getValue());
			$usernode = $respose->getGraphUser();

			$userDetails = array(
				'name'		 =>		$usernode->getName(),
				'id' 		 =>		$usernode->getId(),
				'email'		 =>		$usernode->getEmail(),
				'gender'	 =>		$usernode->getGender(),
				'dp_path'	 =>		'https://graph.facebook.com/'.$usernode->getId().'/picture?width=300'
			);

			return $userDetails;

		}

	}

	$fbClientFunction = FacebookClient(APP_ID, APP_SECRET, VERSION, REDIRECT_URI);

	if(is_array($fbClientFunction)){

		var_dump($fbClientFunction);

	} else {

		echo $fbClientFunction;

	}

?>
