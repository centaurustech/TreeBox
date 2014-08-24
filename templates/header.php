<!-- include mainJS that will be displayed on every page 
	(assuming header.php is included on every page too)-->            
<script src="js/myscripts/mainJS.js"></script> 
<script src="js/jquery.navbar.js"></script> <!--Navbar plugin for header(custom)-->

<!--font for the navbar links-->
<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
<link type="text/css" rel='stylesheet' href='css/mystyles/mainStyle.css' /><!--header style stuff-->

<div id="header">
    <div id="logo">
		    <!--
		  Below we include the Login Button social plugin. This button uses
		  the JavaScript SDK to present a graphical Login button that triggers
		  the FB.login() function when clicked.
		-->
		<div id="status"></div>
	</div>
	<div id='header_bar'>
		<div id="navbar_div">
    	<?php //the session should be started on each individual page at the top
    	$idKey = "590293077747881";
		define("APP_ID", $idKey);
		define("API_KEY", $idKey);
		define("SECRET", "c8d37448f6053582fa825433acbb3614");

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
		    	if($_SERVER['PHP_SELF'] != '/TreeBox/index.php'){
		    		/* Redirect browser to home page if not logged in and not on homepage*/
		    		header("Location: http://localhost/TreeBox/index.php"); 
					exit();
		    	}
			    try {
			    	$session = $helper->getSessionFromRedirect();
			    } catch( FacebookRequestException $ex ) {
			        // When Facebook returns an error
			        // handle this better in production code
			        print_r( "HELLO WORLD" . $ex );
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

		      	/********----------- graph api request for user data------------------***/
		    	$request = new FacebookRequest( $session, 'GET', '/me' );
		    	$response = $request->execute();

		      	$user = $response->getGraphObject(GraphUser::className());
		      	$userId = $user->getId(); //used below and on add_project.php (when submitting the project)

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
		      			<li><a href="index.php">Home</a></li><li><a href="add_project.php">Add a Project</a></li><li><a href="#">My Projects</a></li>
		      		</ul></div> <!--************end of navbar div****************--->';
		      	// print logout url using session and redirect_uri (logout.php page should destroy the session)
	      		echo '<div id="fb_user">
	      				<div id="user_profile" class="fb_user_button">
		      				<img src = "https://graph.facebook.com/'. $userId . '/picture?type=square&height=15&width=15" id="fb_propic"/>
		      				<span class="fb_title">' . $user->getFirstName() . '</span>
		      			</div>
			      		<div id="block_logout" class="fb_user_button">
		      				<a href="' . $helper->getLogoutUrl($session, 'http://localhost/TreeBox/facebook/logout.php')  
		      				. '"><span class="fb_title">Logout</span></a>
			      		</div>
		      		</div>';
		  	} else { //session does not exist
		      	// show login url
		  		echo '<div class="block_login">
		  				<div class="btn-fb-button">
		  					<a href="' . $helper->getLoginUrl( array( 'email', 'user_friends' )) 
		  						. '"><span class="icon"></span>
		  						<span class="title">Login with Facebook</span></a>
		  				</div>
		  			</div>
		  			</div> <!--************end of navbar div****************--->';
		  	}// end if(isset(session))
		?>
    </div>
</div>