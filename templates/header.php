<?php //the session for a page should be started on each individual page file at the top
$idKey = "590293077747881";
define("APP_ID", $idKey);
define("API_KEY", $idKey);
define("SECRET", "c8d37448f6053582fa825433acbb3614");
?>

<!-- include mainJS that will be displayed on every page 
	(assuming header.php is included on every page too)-->            
<script src="js/myscripts/mainJS.js"></script> 
<script src="js/jquery.navbar.js"></script> <!--Navbar plugin for header(custom)-->
<!--Initialize FaceBook JS sdk-->
<script>
  	window.fbAsyncInit = function() {
    	FB.init({
	      	appId      : <?php echo $idKey; ?>,
	      	xfbml      : true, // parse xfbml for social plugins
	      	status     : true, // check login status
	      	cookie     : true, // enable cookies to allow the server to access the session
	      	version    : 'v2.0'
	    });

	    FB.getLoginStatus(function(response) { //just for debugging in console
		  	if (response.status === 'connected') {
		    	console.log('Logged in.');
		  	}
		  	else {
		    	//the php will handle this 
		  	}
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

<!--header style stuff (same for every page)-->
<link type="text/css" rel='stylesheet' href='css/mystyles/mainStyle.css' />

<div id='header_bar'>
	<?php 
	/*Add CORRECT URL NOTTTTTTTTTTTTEEEEEE ie. 'Facebook/FacebookRedirectLoginHelper.php'*/
	require_once( 'facebook/Facebook/HttpClients/FacebookHttpable.php' );
	require_once( 'facebook/Facebook/HttpClients/FacebookCurl.php' );
	require_once( 'facebook/Facebook/HttpClients/FacebookCurlHttpClient.php' );
	require_once( 'facebook/Facebook/Entities/AccessToken.php' );
	require_once( 'facebook/Facebook/Entities/SignedRequest.php' );
	require_once( 'facebook/Facebook/FacebookSession.php' );
	require_once( 'facebook/Facebook/FacebookRedirectLoginHelper.php' );
	require_once( 'facebook/Facebook/FacebookRequest.php' );
	require_once( 'facebook/Facebook/FacebookResponse.php' );
	require_once( 'facebook/Facebook/FacebookSDKException.php' );
	require_once( 'facebook/Facebook/FacebookRequestException.php' );
	require_once( 'facebook/Facebook/FacebookOtherException.php' );
	require_once( 'facebook/Facebook/FacebookAuthorizationException.php' );
	require_once( 'facebook/Facebook/GraphObject.php' );
	require_once( 'facebook/Facebook/GraphUser.php');
	require_once( 'facebook/Facebook/GraphSessionInfo.php' );

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

		$allowedPagesWithoutLogin = array( /*###########################this needs to be updated############*/
			"/TreeBox/index.php",
			"/TreeBox/view_project.php"
		);

	    // Initialize application by Application ID and Secret
	    FacebookSession::setDefaultApplication(API_KEY, SECRET);
	     
	    // Login Healper with redirect URI
	    $helper = new FacebookRedirectLoginHelper( 'http://localhost/TreeBox/index.php' ); 

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

	    	//redirect if not logged in and viewing an "illegal page" (see array $allowedPagesWithoutLogin above for "legal pages")
	    	$redirect = true;
		  	for($i = 0; $i < count($allowedPagesWithoutLogin); $i++){
		    	if($_SERVER['PHP_SELF'] == $allowedPagesWithoutLogin[$i]){
		    		$redirect = false;
		    	}
		    }
		    if($redirect){
			    /* Redirect browser to home page if not logged in and not on homepage*/
	    		header("Location: http://localhost/TreeBox/index.php"); /*###########################this needs to be updated############*/
				exit();
			}

		    try {
		    	$session = $helper->getSessionFromRedirect();
		    } catch( FacebookRequestException $ex ) {
		        // When Facebook returns an error
		        // handle this better in production code
		        ?><script>
		        	console.log(<?php print_r( "HELLO WORLD" . $ex );?>); /*###########################this needs to be updated############*/
		        </script>
		<?php
		    } catch( Exception $ex ) {
		        // When validation fails or other local issues
		        // handle this better in production code
		        print_r( $ex ); /*###########################this needs to be updated#############################*/
		    }
	    }
	     
	    // see if we have a session
	    if ( isset( $session ) ) {
	      	// save the session
	    	$_SESSION['fb_token'] = $session->getToken();
	      	// create a session using saved token or the new one we generated at login
	    	$session = new FacebookSession( $session->getToken() );

	      	/********----------- graph api request for user data------------------***/
	    	$request = new FacebookRequest( $session, 'GET', '/me' );
	    	$response = $request->execute();

	      	$user = $response->getGraphObject(GraphUser::className());
	      	$userId = $user->getId(); //used below and on add_project.php (when submitting the project)

	      	//adds user to the "users" table in the treebox database if they dont already exist
	      	$query = "SELECT user_id FROM users
		        	WHERE user_id = {$userId} LIMIT 1"; 
	        if ($result = @mysql_query($query, $dbc)) { //successful query
	        	if (mysql_num_rows($result) == 0){ //user does not exist on server
	        		//create user 
	        		$userFirstName = $user->getFirstName();
	        		$userLastName = $user->getLastName();

			        $query = "INSERT INTO users(user_id, first_name, last_name) 
			            VALUES('$userId', '$userFirstName', '$userLastName')";
			        executeQuery($query);
	        	}
	        } else { //Query didn't run
		        print '<p style="border: red; color: red;">Error, something occurred which prevented the query from executing. ' 
		        	. mysql_error($dbc) . '</p>';
		    }

	      	
	      	/********-------------graph api request for user friends -----------------*/
	      	/*$requestFriends = new FacebookRequest( $session, 'GET', '/me/friends' );
	      	$responseFriends = $requestFriends->execute();
	     	// get response
	      	$graphObjectFriends = $responseFriends->getGraphObject();//->asArray();
	      	// print friendslist data
	      	echo '<pre>' . print_r( $graphObjectFriends, 1 ) . '</pre>';*/

	      	/*********------------graph api request for user pro pic--------------------*/
	      
	      	echo '<ul id="navbar_menu">
	      			<!--put all on one line so that there is no spacing between horizontal list items-->
	      			<li><a href="index.php"><img src="images/home_icon.png" class="navbar_icon"/><span class="navbar_link">Home</span></a></li><li><a href="add_project.php"><img src="images/add_icon.png" class="navbar_icon"/><span class="navbar_link">Add a Project</span></a></li><li><a href="my_projects.php"><img src="images/my_projects_icon.png" class="navbar_icon"/><span class="navbar_link">My Projects</span></a></li>
	      		</ul>';
	      	// print logout url using session and redirect_uri (logout.php page should destroy the session)
      		echo '<div id="fb_user">
      				<div id="user_profile" class="fb_user_button">
	      				<a href="my_projects.php" class="fb_fxn_link"><img src = "https://graph.facebook.com/'. $userId . '/picture?type=square&height=15&width=15" id="fb_propic"/>
	      				<span class="fb_title">' . $user->getFirstName() . '</span></a>
	      			</div>
		      		<div id="block_logout" class="fb_user_button">
	      				<a href="' . $helper->getLogoutUrl($session, 'http://localhost/TreeBox/facebook/logout.php')  /*###########################this needs to be updated############*/
	      				. '" class="fb_fxn_link"><span class="fb_title">Logout</span></a>
		      		</div>
	      		</div>'; /*---------------the propic is linked to my_projects.php for now-----------------*/
	  	} else { //session does not exist
	      	// show login url
	  		echo '<div class="block_login">
	  				<div class="btn-fb-button">
	  					<a href="' . $helper->getLoginUrl( array( 'email', 'user_friends' )) 
	  						. '"><span class="icon"></span>
	  						<span class="title">Login with Facebook</span></a>
	  				</div>
	  			</div>';
	  	}// end if(isset(session))
	?>
</div> <!--end header_bar div-->