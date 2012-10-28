<?
function display_error($errstr, $url) {
?>

<html>
<head>
<title>Error!</title>
<link rel=stylesheet type=text/css href="../default.css">
</head>

<body>
<h1>ERROR!</h1>

<p><?=$errstr ?>&nbsp;Thanks.
</p>

<p><a href="<?=$url ?>">Go back</a></p>

</body>
</html>

<?
}

$returnurl = "../uploadStyle.php";

$upload = $_REQUEST[ "upload" ];
$stylename = $_REQUEST[ "stylename" ];

echo $_REQUEST[ "upload" ];

if(!move_uploaded_file($_FILES[ "upload" ]['tmp_name'], "$stylename.css"))  {
	display_error("The file was not uploaded for some unknown reason.",$returnurl);
	exit;
}
// success!
echo "Your file has been uploaded successfully, and is located at \"styles\$stylename.css\", you can reference it from a stylesheet by creating a stylesheet containing the code:
<br /><code>@import url(http://www.1142.org/styles/$stylename.css);</code>
<br />(use the .net until I get the domains sorted out, sorry)
<br />Icky process, I know, but it works, dammit.<br /><br />";

echo "<a href=\"../editstyles.php\">Manage your stylesheets</a><br />";
echo "<a href=\"../main.php\">Return to 1142</a><br />";
?>