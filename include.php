<?php
require_once( 'Database.php' );

// Tagline Parameters
$taglinePrefix = "baby newt:";

// Site parameters
$siteTitle = "Baby Newt";
$shortSite = "babynewt";
$sitePath = "http://localhost";
$redirectSite = "http://www.google.com";

$welcomeMsg = "Hello, and welcome to $siteTitle.";

$db = new Database();
$link = $db->getConnection();
?>
