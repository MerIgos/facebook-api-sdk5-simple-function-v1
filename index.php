<?php 

	// SOME CHECKING
	// TRY TO REMOVE THIS (1)
	if (!session_id()) {
	    session_start();
	}

	// INCLUDE THE AUTOLOADER
	require_once __DIR__ . '/vendor/autoload.php';

	function FacebookClient($app_id, $app_secret, $default_graph_version, $redirect){
	
		// INITIALIZING
		$fb = new Facebook\Facebook([
			'app_id' => $app_id,
			'app_secret' => $app_secret,
			'default_graph_version' => $default_graph_version,
		]);

		$helper = $fb->getRedirectLoginHelper();

		// TRY TO REMOVE THIS (2)
		$_SESSION['FBRLH_state']= (isset($_GET['state'])) ? $_GET['state'] : '';

		try {

			$access_token = $helper->getAccessToken();

		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			
			// CUSTOMIZE THE ERROR HERE WHATEVER YOU LIKE
			//echo 'Graph returned an error : ' . $e->getMessage();
			return 'GRAPH';

		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			
			// CUSTOMIZE THE ERROR HERE WHATEVER YOU LIKE
			//echo 'Facebook SDK returned an error : ' . $e->getMessage();
			return 'SDK';

		}

		if(!isset($access_token)){
			
			// PERMISSION
			
			$permission = ['email']; // OPTIONAL
			
			$loginUrl = $helper->getLoginUrl($redirect, $permission);
			
			// YOU CAN CUSTOMIZE IT IF YOU GONNA USE THIS OR IF YOU FOUND THIS USEFUL
			return '<a href="'.$loginUrl.'">Login with Facebook</a>';

		} else {

			$fb->setDefaultAccessToken($access_token);
			
			$respose = $fb->get('/me?fields=id,name,last_name,email,picture,gender', $access_token->getValue());
			
			$usernode = $respose->getGraphUser();

			// CREATING THE ARRAY OF DATA
			$userDetails = array(
				
				'name'		 =>		$usernode->getName(),
				
				'id' 		 =>		$usernode->getId(),
				
				'email'		 =>		$usernode->getEmail(),
				
				'gender'	 =>		$usernode->getGender(),
				
				'dp_path'	 =>		'https://graph.facebook.com/'.$usernode->getId().'/picture?width=300'
				
			);

			// RETURN IT
			return $userDetails;

		}

	}

	// INSTATIATE THE FUNCTION
	$fbClientFunction = FacebookClient(APP_ID, APP_SECRET, VERSION, REDIRECT_URI);

	// CHECK FOR THE RETURN DATA OF THE FUNCTION
	if(is_array($fbClientFunction)){

		// DO WHATEVER YOU WANT TO THE RETURN VALUES
		// INSERT TO THE DATABASE IN SOME CASES
		var_dump($fbClientFunction);

	} else {

		// EITHER AN ERROR CODE OR THE LINK TO LOGIN WITH FACEBOOK WILL BE RETURNED
		echo $fbClientFunction;

	}

?>
