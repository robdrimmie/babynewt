<?Php
// Database parameters
$dbHost = "[database hostname]";
$dbUser = "[database username]";
$dbPassword = "[database password]";
$dbName = "[database name]";

// Tagline Parameters
$taglinePrefix = "baby newt:";

// Site parameters
$siteTitle = "Baby Newt";
$shortSite = "babynewt";
$sitePath = "http://localhost";
$redirectSite = "http://www.google.com";

$welcomeMsg = "Hello, and welcome to $siteTitle.";

function sql_quote( $value ) {
  // see http://www.askbee.net/articles/php/SQL_Injection/sql_injection.html
  if( get_magic_quotes_gpc() )
  {
    $value = stripslashes( $value );
  }
  //check if this function exists
  if( function_exists( "mysql_real_escape_string" ) )
  {
    $value = mysql_real_escape_string( $value );
  }
  //for PHP version < 4.3.0 use addslashes
  else
  {
    $value = addslashes( $value );
  }
  return $value;
}
?>
