<?php
// path for cookies
$cookie_path = "/";

// timeout value for the cookie
$cookie_timeout = 7200; //2 hours in seconds

// timeout value for the garbage collector
//   we add 300 seconds, just in case the user's computer clock
//   was synchronized meanwhile; 600 secs (10 minutes) should be
//   enough - just to ensure there is session data until the
//   cookie expires
$garbage_timeout = $cookie_timeout + 600; // in seconds

// set the PHP session id (PHPSESSID) cookie to a custom value
session_set_cookie_params($cookie_timeout, $cookie_path);

// set the garbage collector - who will clean the session files -
//   to our custom timeout
ini_set('session.gc_maxlifetime', $garbage_timeout);


	session_start();
/*
	if( $_SESSION['sessionLastVisit'] < (time()- 8100000) ) // 810000 = 15 minutes
	{
//		session_destroy();
	}else
	{
		$_SESSION['sessionLastVisit'] = time();
	}
//	if( !Empty( $btnExpireSession ) ) session_destroy();
*/
?>
