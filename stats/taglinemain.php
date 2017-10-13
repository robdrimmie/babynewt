<?php
	include( "../session.php" );

	// establish connection to MySQL database or output error message.
	$link = mysqli_connect ("1142.org", "org1142", "ultima");
	if (!mysqli_select_db("org1142")) echo mysqli_errno().": ".mysqli_error()."<BR>";

//	include( "login.php" );

	if( !session_is_registered("sessionUserId") ){
		$sessionUserId = -1;
		session_register("sessionUserId");
	}

	// if user is not logged in, show message and login inputs
//	if( $sessionUserId == -1 ){
//		header ("Location: index.php");
//	}

	// Default page to show 50 Taglines
	if (Empty( $txtPageSize )) $txtPageSize = 50;

	// Start at the first comment if there's none already set in the database.
	if( Empty($hdnCurrentRecord))		$hdnCurrentRecord = 0;


	// Start at the post before the clicked link.
	if (!Empty( $ViewPost )) $hdnCurrentRecord = ($ViewPost - 1);

	// increment or decrement by page size.
	if (!Empty( $btnNextPage )) $hdnCurrentRecord += $txtPageSize;
	if (!Empty( $btnPrevPage )) $hdnCurrentRecord -= $txtPageSize;

	// If the user clicked a comment button update last viewed to the
	if (!Empty( $btnUpdateMyLastComment )){
		if( $btnUpdateMyLastComment > 1 ) $btnUpdateMyLastComment -= 1;
		$hdnCurrentRecord = $btnUpdateMyLastComment;
	}

	echo "<BR><BR><BR>Start At: ".$hdnCurrentRecord."<BR>";

	// Build the Query to get the comments.
	$CommentsQuery = " SELECT Comment.i_CommentId, Comment.t_Comment, Comment.dt_DatePosted, Users.i_UID,
	 Users.vc_Username, Users.vc_UserId, Category.vc_Name, Category.vc_CSSName
	 FROM Comment, Users, Category
	 WHERE Users.i_UID = Comment.i_UID
	 AND Category.i_CategoryId = Comment.i_CategoryId
	 AND t_Comment LIKE '1142:%'";
	if(!Empty($Hour)){
		$CommentsQuery .= " AND HOUR(dt_DatePosted) = ".$Hour;
	}


	// LIMIT startrecord (1-based, results begin with the following record), # of records to traverse.
	$CommentsQuery .= " LIMIT $hdnCurrentRecord, $txtPageSize";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>1142.org - </TITLE>
<META NAME="Author" CONTENT="Robert M. Drimmie">
<META NAME="Author" CONTENT="Clayton Hannah">
<?PHP
	$UserPreferencesQuery = "SELECT i_PreferenceId, vc_PreferenceValue";
	$UserPreferencesQuery .= " FROM UserPreferences WHERE UserPreferences.i_UID = $sessionUserId ORDER BY i_PreferenceId ASC";
	$UserPreferencesId = mysqli_query ($link, $UserPreferencesQuery);
	$UserPreferencesResult = mysqli_fetch_object($UserPreferencesId);

	echo "<STYLE>";
	echo "DIV.logo1142{ position: absolute; top: 2px; left: 10px; font-size: 42px; font-weight: bold; color: #CCCCCC;}";
	echo "DIV.tagline1142{ position: absolute; top: 21px; left: 70px; font-size: 16px; font-weight: bold; color: black;}";
	echo "DIV.content1142{ padding-top: 30px; }";


	$UserStyleQuery = "SELECT t_StyleSheet";
	$UserStyleQuery .= " FROM UserStyleSheet, DBStyleSheet ";
	$UserStyleQuery .= " WHERE DBStyleSheet.i_StyleSheetId = UserStyleSheet.i_StyleSheetId";
	$UserStyleQuery .= " AND UserStyleSheet.i_UID = $sessionUserId";

	$UserStyleQueryId = mysqli_query ($link, $UserStyleQuery);
	$UserStyleQueryResult = mysqli_fetch_object($UserStyleQueryId);
	echo $UserStyleQueryResult->t_StyleSheet;

	echo "</STYLE>";
?>
</HEAD>
<a name="top">&nbsp;</a>
<BODY MARGINWIDTH=0 MARGINHEIGHT=0>
<div class="content1142">
<?PhP
	// Page Navigation Tools
	echo "<div class=\"NavigationTools\">";
	if(!Empty($Hour)) echo "<form name=\"frmFilter\" action=\"taglinemain.php?Hour=".$Hour."\" method=\"post\">";
	else	echo "<form name=\"frmFilter\" action=\"taglinemain.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"hdnCurrentRecord\" value=\"$hdnCurrentRecord\">";

	// only output the previous button if there are previous records.
	if( $hdnCurrentRecord != 0 ) echo "<input type=\"submit\" name=\"btnPrevPage\" class=\"PrevPage\" value=\"Previous Page\">";
	echo "&nbsp;";
	echo "<input type=\"submit\" name=\"btnNextPage\" class=\"NextPage\" value=\"Next Page\">";
	echo " | <a href=\"#bottom\">bottom of page</A>";
	echo "</form>";
	echo "</div>";

	// Get comments
	$CommentsResultId = mysqli_query ($link, $CommentsQuery);

	$iCommentCount = 0;

	// output comments
	while( 	$CommentsResult = mysqli_fetch_object($CommentsResultId) )
	{
		$iCommentCount += 1;

		echo "<!-- BEGIN COMMENT $CommentsResult->i_CommentId -->";
		if(!Empty($Hour)) echo "<form name=\"frmUpdateLastComment$CommentsResult->i_CommentId\" action=\"taglinemain.php?Hour=$Hour\" method=\"post\">";
		else	echo "<form name=\"frmUpdateLastComment$CommentsResult->i_CommentId\" action=\"taglinemain.php\" method=\"post\">";
		echo "<a name=\"$CommentsResult->i_CommentId\">&nbsp;</a>";

		echo "<div class=\"".$CommentsResult->vc_CSSName."COMMENT\">";
		echo "<div class=\"".$CommentsResult->vc_CSSName."COMMENTHEADER\"></DIV>";
		echo "<div class=\"".$CommentsResult->vc_CSSName."COMMENTBODY\">";
		echo $CommentsResult->t_Comment;
		echo "</div>";
		echo "<div class=\"".$CommentsResult->vc_CSSName."COMMENTFOOTER\">";
		echo "Comment ";
		$blah = $iCommentCount+$hdnCurrentRecord;
		echo "<input type=\"hidden\" name=\"btnUpdateMyLastComment\" value=\"$blah\">";
		echo "<input class=\"".$CommentsResult->vc_CSSName."LASTCMTBTN\" type=\"submit\" name=\"btnCommentID\" value=\"$CommentsResult->i_CommentId\">";
		echo " posted by $CommentsResult->vc_Username (";
		echo $CommentsResult->vc_UserId;
		echo ") to ";
		if($CommentsResult->i_UID == $sessionUserId) {
			echo "<a href=\"changecategory.php?id=$CommentsResult->i_CommentId\" ";
			echo "class=\"" .$CommentsResult->vc_CSSName. "CHANGECATEGORYLINK\">";
			echo $CommentsResult->vc_Name. "</a>";
		} else {
			echo $CommentsResult->vc_Name;
		}
		echo " on $CommentsResult->dt_DatePosted";
		echo "&nbsp;<a class=\"".$CommentsResult->vc_CSSName."COMMENTPERMALINK\" href=\"http://www.1142.org/main.php?ViewPost=$CommentsResult->i_CommentId\">link!</a>";

		echo "<input type=\"hidden\" name=\"hdnMyLastCommentId\" value=\"$CommentsResult->i_CommentId\">";
		echo "</div></div></form>";
		echo "\n<!-- END COMMENT $CommentsResult->i_CommentId -->";
		echo "\n\n\n";

	}
	echo "<a name=\"bottom\">&nbsp;</a>";
	echo "<a href=\"#top\">top of page</a>";
	if( $sessionUserId != -1 ){
		echo " | <a href=\"editprofile.php\">change user data</a>";
		echo " | <a href=\"editstyles.php\">change your stylesheets</a>";
		echo " | <a href=\"stats\">User stats</a>";
		echo " | <a href=\"code\">Code base</a>";
		echo " | <a href=\"theList.txt\">Rob's To Do List</a>";
		echo " | <a href=\"main.php\">reload the page</a>";
		echo " | <a href=\"index.php?btnExpireSession=1\">logout</a>";

	}

	// Page Navigation Tools
	// DUPLICATED CODE rmd todo:  modularise this
	echo "<div class=\"NavigationTools\">";
	if(!Empty($Hour)) echo "<form name=\"frmFilter\" action=\"taglinemain.php?Hour=".$Hour."\" method=\"post\">";
	else	echo "<form name=\"frmFilter\" action=\"taglinemain.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"hdnCurrentRecord\" value=\"$hdnCurrentRecord\">";

	// only output the previous button if there are previous records.
	if( $hdnCurrentRecord != 0 ) echo "<input type=\"submit\" name=\"btnPrevPage\" class=\"PrevPage\" value=\"Previous Page\">";
	echo "&nbsp;";
	// figure out how to know if you're on the last page to not show this.
	echo "<input type=\"submit\" name=\"btnNextPage\" class=\"NextPage\" value=\"Next Page\">";

	echo "&nbsp;&nbsp;";

	echo "Show <input type=\"text\" name=\"txtPageSize\" class=\"PageSize\" value=\"$txtPageSize\" size=\"5\"> posts per page&nbsp;";
	echo "Sort posts ";
	echo "<SELECT name=\"SortCommentsDirection\" class=\"SortCommentsDirection\">";
		echo "<OPTION VALUE=\"ASC\"";
		if ( $SortCommentsDirection == "ASC" ) echo " SELECTED";
		echo ">Ascending</OPTION>";
		echo "<OPTION VALUE=\"DESC\"";
		if ( $SortCommentsDirection == "DESC" ) echo " SELECTED";
		echo ">Descending</OPTION>";
	echo "</SELECT></form></div>";

	echo "</DIV>";

?>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysqli_close($link);
?>
