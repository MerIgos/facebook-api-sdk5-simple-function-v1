<?php 

	if (!session_id()) {
	    session_start();
	}

	require_once __DIR__ . '/vendor/autoload.php';

	/* $fb = new Facebook\Facebook([
		'app_id' => '793012970839594',
		'app_secret' => '8d02494d193c810c919bf90e8e3dab2a',
		'default_graph_version' => 'v2.5',
	]);

	$redirect = 'http://localhost/learnings/FB-API-v1/';

	$helper = $fb->getRedirectLoginHelper();

	$_SESSION['FBRLH_state']= (isset($_GET['state'])) ? $_GET['state'] : '';

	try {

		$access_token = $helper->getAccessToken();

	} catch (Facebook\Exceptions\FacebookResponseException $e) {
		
		echo 'Graph returned an error : ' . $e->getMessage();
		exit();

	} catch (Facebook\Exceptions\FacebookSDKException $e) {
		
		echo 'Facebook SDK returned an error : ' . $e->getMessage();
		exit();

	}

	if(isset($_GET['logout'])){
		session_destroy();
	}

	if(!isset($access_token)){

		$permission = ['email'];
		$loginUrl = $helper->getLoginUrl($redirect, $permission);
		echo '<a href="'.$loginUrl.'">Login with Facebook</a>';

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

		foreach ($userDetails as $userKey => $userValue) {
			echo $userKey . ' : ' . $userValue . '<br>';
		}

		echo '<a href="'.$redirect.'">Logout</a>';

	} */

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

	//var_dump(FacebookClient('793012970839594','8d02494d193c810c919bf90e8e3dab2a','v2.5','http://localhost/learnings/FB-API-v1/'));

	$fbClientFunction = FacebookClient('793012970839594','8d02494d193c810c919bf90e8e3dab2a','v2.5','http://localhost/learnings/FB-API-v1/');

	if(is_array($fbClientFunction)){

		var_dump($fbClientFunction);

	} else {

		echo $fbClientFunction;

	}

?>
