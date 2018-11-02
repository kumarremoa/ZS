<?php
  session_start();
  
  // Facebook app settings
  $app_id         = '1643239039229935';
  $app_secret     = 'fd12311e4cdf1286441a1f737f592121';
  $callback       = 'http://localhost:8888/DEV/freelancer/sreelaj_john/product%20feed/signin/test2/';
  $logout_page    = 'http://localhost:8888/DEV/freelancer/sreelaj_john/product%20feed/signin/test2/signout.php';
  $permissions    = array('public_profile', /*'user_birthday'*/);
  $active_session = false;

  define( 'ROOT', dirname( __FILE__ ) . '/' );
  require_once( ROOT . 'facebook-sdk/src/Facebook/autoload.php' );

  $fb = new Facebook\Facebook([
    'app_id'                  => $app_id,
    'app_secret'              => $app_secret,
    'default_graph_version'   => 'v2.9',
  ]);

  $helper = $fb->getRedirectLoginHelper();
  if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
  }

/* ====================================================================== *
      CHECK OR GET TOKEN
 * ====================================================================== */

  if( isset( $_SESSION ) && isset( $_SESSION['facebook_access_token'] ) ){// Is there a session saved already?

    $accessToken = $_SESSION['facebook_access_token'];

    try {
      $fb->get('/me', $accessToken); 
    } catch(Exception $e) {
      $accessToken = null;
      echo "<script>location.href='../signout.php';</script>";
      exit(); 
    }  

  }else{// There's no session saved 

    try {
      $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      // When Graph returns an error
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      // When validation fails or other local issues
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }

  }    

/* ====================================================================== *
      RESPONSE 
 * ====================================================================== */      

if(isset($accessToken)){// This is a valid fb session

  $_SESSION['facebook_access_token']    = (string) $accessToken;
  $response                             = $fb->get('/me?fields=id,first_name,last_name,gender,birthday', $accessToken); 
  $user                                 = $response->getGraphNode()->asArray();
  $active_session                       = true;

}else{// it isn't a valid fb session :(
  
  $loginUrl         = $helper->getLoginUrl($callback, $permissions); // Get login URL
  $active_session   = false;

}
