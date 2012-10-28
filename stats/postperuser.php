<?php
	include( "../session.php" );

	// establish connection to MySQL database or output error message.
	$link = mysql_connect ("1142.org", "org1142", "ultima");
	if (!mysql_select_db("org1142", $link)) echo mysql_errno().": ".mysql_error()."<BR>";

	// Build the Query to get the post per hour
	$CommentsQuery = " select vc_UserName as UNAME, count(Comment.i_UID) as NUMBER";
	$CommentsQuery .= " from Users, Comment where Comment.i_UID = Users.i_UID";
	$CommentsQuery .= " group by Comment.i_UID order by NUMBER desc";

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
	echo "Post by User Breakdown for 1142.org<BR><BR><BR>";
	// Get comments
	$CommentsResultId = mysql_query ($CommentsQuery, $link);
	// output comments
	echo "<TABLE CELLPADDING=0 CELLSPACING=1>";//<TR VALIGN=BOTTOM>";
	if (!Empty($Zoom)) $Ratio = $Zoom;
	else $Ratio = 0.1;
	if($Ratio <= 0) $Ratio = 0.1;
	$counter = 0;
	$total = 0;
	while($CommentsResult = mysql_fetch_object($CommentsResultId) )
	{
		$counter = $counter + 1;
		$GraphHt = $CommentsResult->NUMBER*$Ratio;
		$total = $total  + 	$CommentsResult->NUMBER;
		echo "<TR><TD>".$CommentsResult->UNAME."</TD><TD VALIGN=MIDDLE ALIGN=RIGHT><FONT SIZE=2>".$CommentsResult->NUMBER."</FONT></TD><TD><IMG SRC='bar.gif' BORDER=0 HEIGHT=20 WIDTH=".$GraphHt."></TD></TR>";
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
	echo "<CENTER><P><FONT SIZE=2>The current zoom ratio is ".$Ratio."</P></FONT>";
?>
<FONT SIZE=1><P>You can change the zoom ratio by adding it to the query sting in the URL. For
Example The default Zoom Ratio is 0.1, this could also be acchieved by appending ?Zoom=0.1
to the end, so it would look like postperuser.php?Zoom=0.1 . You can zoom in more by having a number
greater than one, zoom out by using a number between 1 and zero. Zero and under is caught and changed to
the default of 0.1.</P>
</FONT>
</CENTER>
</FONT>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysql_close($link);
?>