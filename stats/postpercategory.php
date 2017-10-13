<?php
	include( "../session.php" );

	// establish connection to MySQL database or output error message.
	$link = mysqli_connect ("1142.org", "org1142", "ultima");
	if (!mysqli_select_db("org1142")) echo mysqli_errno().": ".mysqli_error()."<BR>";

	// Build the Query to get the post per hour
	$CommentsQuery = " SELECT Category.i_CategoryId,
							Category.vc_Name as Category,
							count(Comment.i_CategoryId) as NUMBER
						FROM Comment, Category
						WHERE Comment.i_CategoryId = Category.i_CategoryId
						GROUP BY i_CategoryId order by i_CategoryId ";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>1142.org Graphs (Category Posts Per Day)</TITLE>
<META NAME="Author" CONTENT="Clayton Hannah">
<BODY MARGINWIDTH=0 MARGINHEIGHT=0>
<?PhP
	// Get comments
	$CommentsResultId = mysqli_query ($link, $CommentsQuery);
	// $iCommentCount = 0;
	// output comments
	echo "<TABLE><TR VALIGN=BOTTOM>";
	while($CommentsResult = mysqli_fetch_object($CommentsResultId) )
	{
		echo "<TD ALIGN=CENTER><FONT SIZE=1>".$CommentsResult->NUMBER."<BR><IMG SRC='bar.gif' BORDER=0 WIDTH=30 HEIGHT=".$CommentsResult->NUMBER."><BR>".$CommentsResult->Category."</FONT></TD>";
	}
	echo "</TR></TABLE><BR>";
?>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysqli_close($link);
?>
