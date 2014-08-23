<?php
$idKey = "590293077747881";
define("APP_ID", $idKey);
define("API_KEY", $idKey);
define("SECRET", "c8d37448f6053582fa825433acbb3614");

session_start();
/*Add CORRECT URL NOTTTTTTTTTTTTEEEEEE ie. 'Facebook/FacebookRedirectLoginHelper.php'*/
require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'Facebook/HttpClients/FacebookCurl.php' );
require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );
require_once( 'Facebook/Entities/AccessToken.php' );
require_once( 'Facebook/Entities/SignedRequest.php' );
require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookOtherException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/GraphUser.php');
require_once( 'Facebook/GraphSessionInfo.php' );

/*LEAVE THIS UNCHANGED DESPITE RELATIVE URL*/
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;
?>

<!DOCTYPE html>
<html>
  <head>
    <title>FB Login Page</title>
    <style>
      .block_login {
        width: 188px;
        margin: 50px auto; /*centering*/
      }
      .block_logout {
        width: 9.3125em;
      }

      .btn-fb-button {
        width: 100%;
        height: 30px;
        border: 1px solid rgba(0, 0, 0, 0.3);
        border-radius: 4px;
        background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4gPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PGxpbmVhckdyYWRpZW50IGlkPSJncmFkIiBncmFkaWVudFVuaXRzPSJvYmplY3RCb3VuZGluZ0JveCIgeDE9IjAuNSIgeTE9IjAuMCIgeDI9IjAuNSIgeTI9IjEuMCI+PHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzQ4NmJiNSIvPjxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iIzMzNGU4NyIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
        background-size: 100%;
        background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #486bb5), color-stop(100%, #334e87));
        background-image: -moz-linear-gradient(top, #486bb5 0%, #334e87 100%);
        background-image: -webkit-linear-gradient(top, #486bb5 0%, #334e87 100%);
        background-image: linear-gradient(to bottom, #486bb5 0%, #334e87 100%);
        box-shadow: inset 0 1px 3px rgba(255, 255, 255, 0.2);
        cursor: pointer;
      }
      .btn-fb-button:hover {
        background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4gPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PGxpbmVhckdyYWRpZW50IGlkPSJncmFkIiBncmFkaWVudFVuaXRzPSJvYmplY3RCb3VuZGluZ0JveCIgeDE9IjAuNSIgeTE9IjAuMCIgeDI9IjAuNSIgeTI9IjEuMCI+PHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzQ4NmJiNSIvPjxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iIzIzM2I2YyIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
        background-size: 100%;
        background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #486bb5), color-stop(100%, #233b6c));
        background-image: -moz-linear-gradient(top, #486bb5 0%, #233b6c 100%);
        background-image: -webkit-linear-gradient(top, #486bb5 0%, #233b6c 100%);
        background-image: linear-gradient(to bottom, #486bb5 0%, #233b6c 100%);
        border: 1px solid white;
      }
      .btn-fb-button .icon {
        background: url(http://s.cdpn.io/6035/fb_login_sprite.png) no-repeat;
        width: 11px;
        height: 22px;
        display: inline-block;
        float: left;
        margin: 3px 10px;
      }
      .btn-fb-button .title {
        font-family: "Lucida Grande", Tahoma, sans-serif;
        font-weight: 600;
        font-size: 0.8125em;
        color: #fff;
        line-height: 30px;
        float: left;
        padding: 0 10px;
        text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.5);
        box-shadow: -1px 0 0 rgba(255, 255, 255, 0.1);
        border-left: 1px solid rgba(0, 0, 0, 0.3);
      }
    </style>
    <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
    <script>
      $(document).ready(function{
        
      });
    </script>
  </head>
  <body>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : <?php echo API_KEY?>,
          cookie     : true, 
          xfbml      : true,
          version    : 'v2.0'
        });
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
    </script>
  
  <?php
    function getGraphInfo($session, $method, $request){

    }
    // Initialize application by Application ID and Secret
    FacebookSession::setDefaultApplication(API_KEY, SECRET);
     
    // Login Healper with reditect URI
    $helper = new FacebookRedirectLoginHelper( 'http://localhost/TreeBox/facebook/fbtest.php' );

    // see if a existing session exists
    if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
      // create new session from saved access_token
      $session = new FacebookSession( $_SESSION['fb_token'] );
      
      // validate the access_token to make sure it's still valid
      try {
        if ( !$session->validate() ) {
          $session = null;
        }
      } catch ( Exception $e ) {
        // catch any exceptions
        $session = null;
      }
    }  
     
    if ( !isset( $session ) || $session === null ) {
      // no session exists
      try {
        $session = $helper->getSessionFromRedirect();
      } catch( FacebookRequestException $ex ) {
        // When Facebook returns an error
        // handle this better in production code
        print_r( $ex );
      } catch( Exception $ex ) {
        // When validation fails or other local issues
        // handle this better in production code
        print_r( $ex );
      }
    }
     
    // see if we have a session
    if ( isset( $session ) ) {
      // save the session
      $_SESSION['fb_token'] = $session->getToken();
      // create a session using saved token or the new one we generated at login
      $session = new FacebookSession( $session->getToken() );
      
      // graph api request for user data
      $request = new FacebookRequest( $session, 'GET', '/me' );
      $response = $request->execute();
      // get response
      $graphObject = $response->getGraphObject()->asArray();
      // print profile data
      echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';
      echo '<pre>' . $session->getToken() . '</pre>';

      $user = $response->getGraphObject(GraphUser::className());
      $userId = $user->getId();
      echo $userId;

      // graph api request for user friends
      $request1 = new FacebookRequest( $session, 'GET', '/me/friends' );
      $response1 = $request1->execute();
      // get response
      $graphObject1 = $response1->getGraphObject()->asArray();
      // print friendslist data
      echo '<pre>' . print_r( $graphObject1, 1 ) . '</pre>';



      // graph api request for user friends
      $request = new FacebookRequest(
        $session,
        'GET',
        '/me/picture',
        array (
          'redirect' => false,
          'height' => '200',
          'type' => 'normal',
          'width' => '200',
        )
      );
      $response = $request->execute();
      $graphObject = $response->getGraphObject()->asArray();
      echo "<img src = 'https://graph.facebook.com/{$userId}/picture?type=small' />";
      echo "<img src = 'https://graph.facebook.com/{$userId}/picture?type=square' />";
      echo "<img src = 'https://graph.facebook.com/{$userId}/picture?type=square&height=28&width=28' />";

      //echo "<img src='{$graphObject["url"]}'/>";
      echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';
      
      // print logout url using session and redirect_uri (logout.php page should destroy the session)
      echo '<div class="block_logout">
              <div class="btn-fb-button">
                <a href="' . $helper->getLogoutUrl($session, 'http://localhost/TreeBox/facebook/logout.php')  
                  . '"><span class="title">Logout of Facebook</span></a>
              </div>
            </div>';
    } else {
      // show login url
      echo '<div class="block_login">
              <div class="btn-fb-button">
                <a href="' . $helper->getLoginUrl( array( 'user_friends' )) //permissions!
                  . '"><span class="icon"></span>
                  <span class="title">Login with Facebook</span></a>
              </div>
            </div>';
    }
  ?>
  </body>
</html>

