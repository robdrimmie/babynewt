<?php
	include( "../session.php" );
	include( "../include.php" );
	// Default to my userID for the testing
	if( !session_is_registered("sessionUserId") ){
		$sessionUserId = -1;
		session_register("sessionUserId");
	}
	// if user is not logged in, show message and login inputs
	if( $sessionUserId == -1 ){
		header ("Location: index.php"); 
	}
?>
<html>
<head>
<title>Upload to 1142</title>
<!--<link rel=stylesheet type=text/css href="../default.css">-->
</head>

<body>
<h1>Upload to 1142</h1>

<form action="upload-file.php" method=POST enctype="multipart/form-data">
<b>Upload a file</b><br>
Choose a file: <input type=file name=upload><br />
pick a name for this style: <input type="text" name="stylename" value="">
<input type="hidden" name="path" size=40 value="/"><br>
<input type="hidden" value="y" name="overwrite">

<p>
<input type=submit value="Upload this file!">
</form>

<p>
<!--
<form action="create-dir.php" method=POST>
<b>Create a directory</b><br>
Directory name (from www.kidlit.org): <input name=dirname size=40><br>
<p>
<input type=submit value="Create this Directory!">
</form>
-->
<p>
<!--
<form action="delete-file.php" method=POST>
<b>Delete a File</b><br>
(Be careful, this won't ask you for confirmation!)<br>
File name (from www.kidlit.org): <input name=filename size=40><br>
<p>
<input type=submit value="Delete this File!">
</form>
-->
</font>
</body>
</html>