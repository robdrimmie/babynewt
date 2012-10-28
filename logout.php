<?php
	setcookie( "cookieUsername", FALSE, mktime(0,0,0,12,1,2015));
	setcookie( "cookiePassword", FALSE, mktime(0,0,0,12,1,1999));
	$_SESSION[ 'sessionUserId' ] = -1;
	header( "Location: index.php?btnExpireSession=1");
?>