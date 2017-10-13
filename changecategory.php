<?php
// changecategory.php
//  This file is brand new for the change category feature...
//  I used post.php as a base for the db connection and coding style...
//    -dave 2001-06-04
//

	// actually, I don't know if you're still using this convention or not...
	// it's not in the posted post.php, but it is in main.php, but you've
	// changed the cookies since then.  ack!

	include('session.php');
	include("include.php");


	// get all the relevant info about the comment in question
	$CommentQuery  = "SELECT c.i_CommentId, c.t_Comment, ";
	$CommentQuery .= "u.i_UID, t.vc_Name ";
	$CommentQuery .= "FROM Comment c, Users u, Category t ";
	$CommentQuery .= "WHERE u.i_UID = c.i_UID ";
	$CommentQuery .= "AND t.i_CategoryId = c.i_CategoryId ";
	$CommentQuery .= "AND c.i_CommentId = ".$_REQUEST['id'];
	$CommentResultId = mysqli_query ($link, $CommentQuery);

	$CommentResult = mysqli_fetch_object($CommentResultId);

	// check to see that session user id is the same as the comment user id
	// (needs to be changed if admin users can also change categories)
	// if it doesn't match, no error, just send back to main.php
	if($CommentResult->i_UID != $_SESSION['sessionUserId']) {
		Header("Location: main.php");
		exit;
	}



	// if we're this far, the user is cool....
	// two modes, one to interactively change,
	// one to update, this is interactive mode
	if(empty($_REQUEST['hdnAction'])) {

		// Category list to build select box with
		// DUPLICATE FUNCTIONALITY
		$CategoryListQuery = "SELECT i_CategoryId, vc_Name FROM Category ORDER BY vc_Name";
		$CategoryListResultId = mysqli_query ($link, $CategoryListQuery);

		// output the page
?><html>
<head><title>Change Post Category</title></head>
<body>
Change the category of this post:
<blockquote><? echo $CommentResult->t_Comment; ?></blockquote>

<form name="frmChangeCategory" action="changecategory.php" method="POST">
<input type=hidden name="hdnAction" value="change">
<input type=hidden name="id" value="<? echo $_REQUEST['id']; ?>">

New category:
<select name="newCategoryId">
<?php
	while( $CategoryListResult = mysqli_fetch_object($CategoryListResultId))
	{
		echo "<OPTION VALUE=\"$CategoryListResult->i_CategoryId\"";
		if( $CategoryListResult->i_CategoryId == $CommentResult->i_CategoryId )
		{
			echo " SELECTED";
		}
		echo ">";
		echo $CategoryListResult->vc_Name;
		echo "</OPTION>";
	}
?>
</select>

<input type=submit name="btnSubmitChangeCategory" value="Change">
</form>
<a href="http://www.1142.org/main.php">1142 Main</a>

</body>
</html>
<?php
	} else if($_REQUEST['hdnAction'] == "change") {
		// so go ahead and change it!
		$UpdateCategoryQuery  = "UPDATE Comment SET i_CategoryId=";
		$UpdateCategoryQuery .= $_REQUEST['newCategoryId'];
		$UpdateCategoryQuery .= " WHERE i_CommentId = ".$_REQUEST['id'];
		$UpdateCategoryResultId = mysqli_query ($link, $UpdateCategoryQuery);

		// then update last-viewed comment property (?)
		// (I don't know if this is how you want to do it or not...
		// I copied it from main.php with a couple of changes)
		$UpdateLastCommentQuery  = "UPDATE Users ";
		$UpdateLastCommentQuery .= "SET i_CommentId = ".$_REQUEST['id'];
		$UpdateLastCommentQuery .= "WHERE i_UID = ".$_SESSION['sessionUserId'];
		$UpdateLastCommentResultId = mysqli_query ($link, $UpdateLastCommentQuery);

		// anyway, then redirect to main!
		header("Location: main.php");
		exit;
	}

// I guess that's it.
//  -dave
?>
