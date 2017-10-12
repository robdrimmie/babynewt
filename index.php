<?php
    include("session.php");
    include("include.php");

    include( "login.php" );

    if ( !isset($_SESSION['sessionUserId']) ) {
        $_SESSION['sessionUserId'] = -1;
    }
    else {
        $sessionUserId = $_SESSION['sessionUserId'];
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?php echo $siteTitle ?></title>
</head>
<body style="text-align:justify;margin-left:20px;margin-top:20px;">
<div style="width:300px;">
<?php
    echo $welcomeMsg;

    // if user is not logged in, show message and login inputs
    if ( $_SESSION['sessionUserId'] == -1 ) {
        OutputLoginForm( "index.php" );
    }
    else {
        echo "You are already logged in.  <a href=\"main.php\">Go to the main site</a>";
    }
    ?>
</div>
</body>
</html>
<?php
    // close connection to MySQL Database
    mysqli_close($link);
?>
