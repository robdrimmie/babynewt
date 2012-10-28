<?php
	include( "../session.php" );

	// establish connection to MySQL database or output error message.
	$link = mysql_connect ("1142.org", "org1142", "ultima");
	if (!mysql_select_db("org1142", $link)) echo mysql_errno().": ".mysql_error()."<BR>";

	// Build the Query to get the post per hour
	$CommentsQuery = " select vc_UserName as UNAME";
	$CommentsQuery .= " from Users";

//	$CommentsQuery = " select vc_UserName as UNAME, count(Comment.i_UID) as NUMBER";
//	$CommentsQuery .= " from Users JOIN Comment ON (Comment.i_UID = Users.i_UID)";
//	$CommentsQuery .= " group by Comment.i_UID order by NUMBER desc";

	$LUQuery = " SELECT count(*) as TOTAL from Comment where i_UID = $sessionUserId";
	$TotalsQuery = " select count(*) as TOTAL from Comment";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>1142.org Graphs (Posts by User)</TITLE>
<META NAME="Author" CONTENT="Clayton Hannah">
<BODY MARGINWIDTH=0 MARGINHEIGHT=0>
<FONT FACE=VERDANA SIZE=4>

<?PhP 
	echo "User List for 1142.org<BR><BR><BR>";
	// Get comments
	$CommentsResultId = mysql_query ($CommentsQuery, $link);
	// output comments
	echo "<TABLE CELLPADDING=0 CELLSPACING=1>";//<TR VALIGN=BOTTOM>";
	while($CommentsResult = mysql_fetch_object($CommentsResultId) )
	{
		echo "<TR><TD>".$CommentsResult->UNAME."</TD></TR>";
	}
	echo "</TR></TABLE>";
	$TotalsResId = mysql_query ($TotalsQuery, $link);
	while($TotalsResult = mysql_fetch_object($TotalsResId) )
	{
		echo "<P><FONT SIZE=2>The top 10 posters total ".$total." posts out of ".$TotalsResult->TOTAL.".</FONT><BR>";
	}

	$LUResId = mysql_query ($LUQuery, $link);
	while($LUResult = mysql_fetch_object($LUResId) )
	{
		echo "<FONT SIZE=2>You have [".$LUResult->TOTAL."] posts.</FONT></P>";
	}
?>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysql_close($link);
?>