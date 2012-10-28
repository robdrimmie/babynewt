<?php
	//include( "session.php" );

	// establish connection to MySQL database or output error message.
	$link = mysql_connect ("1142.org", "org1142", "ultima");
	if (!mysql_select_db("org1142", $link)) echo mysql_errno().": ".mysql_error()."<BR>";	
	//$link = mysql_connect ("localhost", "1142test");
	//if (!mysql_select_db("1142test", $link)) echo mysql_errno().": ".mysql_error()."<BR>";

	// Build the Query to get the post per hour
	$CommentsQuery = " select HOUR(dt_datePosted) as HOUR, count(dt_DatePosted) as NUMBER";
	$CommentsQuery .= " from Comment";
	$CommentsQuery .= " group by HOUR(dt_datePosted) order by HOUR(dt_datePosted) ";
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>1142.org Graphs</TITLE>
<META NAME="Author" CONTENT="Clayton Hannah">
<BODY MARGINWIDTH=0 MARGINHEIGHT=0>
<FONT FACE=VERDANA SIZE=4>
<CENTER>
<?PhP 
	echo "Post by Hour Breakdown for 1142.org<BR><BR><BR>";
	// Get comments
	$CommentsResultId = mysql_query ($CommentsQuery, $link);
	// output comments
	echo "<TABLE><TR VALIGN=BOTTOM>";
	while($CommentsResult = mysql_fetch_object($CommentsResultId) )
	{
		echo "<TD ALIGN=CENTER><FONT SIZE=1>".$CommentsResult->NUMBER."<BR><IMG SRC='bar.gif' BORDER=0 WIDTH=30 HEIGHT=".$CommentsResult->NUMBER."><BR>".$CommentsResult->HOUR."</FONT></TD>";
	}
	echo "</TR></TABLE>";
?>
</CENTER>
</FONT>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysql_close($link);
?>