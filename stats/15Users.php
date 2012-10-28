<?php
	// establish connection to MySQL database or output error message.
	$link = mysql_connect ("localhost", "drimmie_org1142", "ultima");
	if (!mysql_select_db("drimmie_org1142", $link)) echo mysql_errno().": ".mysql_error()."<BR>";

	// Build the Query to get the comments.
	$CommentsQuery = " SELECT REPLACE(Comment.t_Comment, '<BR><BR>','<BR>') AS cmt, Comment.dt_DatePosted, Users.i_UID,";	
	$CommentsQuery .= " Users.vc_Username";
	$CommentsQuery .= " FROM Comment, Users";
	$CommentsQuery .= " WHERE Users.i_UID = Comment.i_UID AND now()-Comment.dt_DatePosted < 900 ";
	$CommentsQuery .= " ORDER BY Comment.i_CommentId DESC";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>15 Minutes of Fame</TITLE>
<META NAME="Author" CONTENT="Clayton Hannah">
<META HTTP-EQUIV=Refresh CONTENT="900; 
URL=http://www.1142.net/stats/15Users.php"> 
<STYLE>
BODY {
  /*font-family: Century Gothic;*/
  font-family: century gothic;
  font-size:   12px;
}
DIV.wazoo{
 /* overflow-y:      hidden;*/
 font-family: century gothic;
}
DIV.Comment{
  text-overflow: ellipsis;
  overflow-y:      hidden;
  font-family: century gothic;
  font-size:       12px;
  width:				 330px;
  border-top:     1px solid black;
}
DIV.dtime{
font-family: century gothic;
	font-size:          xx-small;
	color:              blue;
	text-align:         right;
  width:				 330px;	
}
DIV.onlineU{
font-family: century gothic;
   padding:   0px 5px 10px 5px;
   width:     300px;
   font-size:   12px;
}
DIV.postedU{
font-family: century gothic;
   padding:   0px 5px 5px 5px;
   width:     300px;
   font-size:   12px;
}
DIV.Hdr{
font-family: century gothic;
  background-color: black;
  font-size:				14px;
  text-align:       center;
  color:            white;
  font-weight:      bold;
}
</STYLE>
</HEAD>
<BODY MARGINWIDTH=0 MARGINHEIGHT=0>

<DIV CLASS=onlineu>
<DIV CLASS=Hdr>15 Minutes Users</DIV>
<?Php
	// Build the Query to get the Users
	$CommentsQuery = " SELECT Users.vc_Username FROM Users WHERE DATE_ADD(dt_LastVisit, INTERVAL 15 MINUTE) > now() ORDER BY Users.dt_LastVisit DESC";
//(TIME_TO_SEC(now()) - TIME_TO_SEC(dt_LastVisit)) < 900 AND  ORDER BY Users.dt_LastVisit DESC";

	// Get comments
	$CommentsResultId = mysql_query ($CommentsQuery, $link);
	$iCommentCount = 0;
  // output comments
	  $CommentsResult = mysql_fetch_object($CommentsResultId);
	do{
		$iCommentCount += 1;
		echo "$CommentsResult->vc_Username | \n";
	}while($CommentsResult = mysql_fetch_object($CommentsResultId));
	echo $CommentsResult->tim;
	echo "\n";	
?>
</DIV><BR>
<DIV CLASS=postedu>
<DIV CLASS=Hdr>15 Minutes Posters</DIV>
<?Php
	// Build the Query to get the Users
	$CommentsQuery = " SELECT DISTINCT(Users.vc_Username) FROM Users, Comment";
  $CommentsQuery .= " WHERE now()-Comment.dt_DatePosted < 900 AND Users.i_UID = Comment.i_UID";	
	$CommentsQuery .= " ORDER BY Users.dt_LastVisit DESC";

	// Get comments
	$CommentsResultId = mysql_query ($CommentsQuery, $link);
	$iCommentCount = 0;
  // output comments
	while( 	$CommentsResult = mysql_fetch_object($CommentsResultId) )
	{
		$iCommentCount += 1;
		echo "$CommentsResult->vc_Username | \n";
	}
	echo $CommentsResult->tim;
	echo "\n";	
?>
</DIV>
<A HREF=15Users.php>reload</A>
</DIV>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysql_close($link);
?>
