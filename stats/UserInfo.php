<?php

	include( "../session.php" );
	include("../include.php");
	// establish connection to MySQL database or output error message.
	$link = mysql_connect ($dbHost, $dbUser, $dbPassword);
	if (!mysql_select_db($dbName, $link)) echo mysql_errno().": ".mysql_error()."<BR>";
	
	$link1 = mysql_connect ($dbHost, $dbUser, $dbPassword);
	if (!mysql_select_db($dbName, $link)) echo mysql_errno().": ".mysql_error()."<BR>";

	$UList = FALSE;

  $UserName = $_REQUEST[ "UserName" ];
	if(Empty($UserName)){
		$Title = " Please select a user";
		$Query = "SELECT vc_Username AS LABEL FROM Users ORDER BY LABEL";
		$UList = TRUE;
	}
	else{
		$Title = " Super Secret Private Information for ".$UserName;
		$Query = " COUNT(t_Comment) AS COUNTER FROM Comment WHERE t_Comment LIKE '1142:%";
		$QLabel = " Number of Taglines:";
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>1142.org User Information</TITLE>
<META NAME="Author" CONTENT="Clayton Hannah">
<SCRIPT>
function openpopup(boo){
  var popurl="UseGraph.php?UserName="+boo
  winpops=window.open(popurl,"","width=550,height=210,resizable, ")
}
</SCRIPT>
</HEAD>
<BODY MARGINWIDTH=0 MARGINHEIGHT=0>
<FONT FACE=VERDANA SIZE=3>

<?PhP 
	echo $Title."<BR><BR>";
	if($UList){
		$UListId = mysql_query ($Query, $link);
		while($UListRes = mysql_fetch_object($UListId) ){
			echo "<A HREF=\"UserInfo.php?UserName=".$UListRes->LABEL."\">".$UListRes->LABEL."</A><BR>";
		}
	}
	else{
		$Query = " SELECT COUNT(Comment.i_UID) AS COUNTER, Comment.i_UID FROM Comment, Users WHERE t_Comment LIKE '1142:%' AND 
		Comment.i_UID = Users.i_UID and Users.vc_Username = '".$UserName."' GROUP BY Comment.i_UID";
		$QLabel = "Number of Taglines: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo $QLabel.$UInfRes->COUNTER."<BR>";
		else
			echo $QLabel."0<BR>";
		
		$Query = " SELECT COUNT(Comment.i_UID) AS COUNTER, Comment.i_UID FROM Comment, Users WHERE Comment.i_CommentId % 100 = 0 AND 
		Comment.i_UID = Users.i_UID and Users.vc_Username = '".$UserName."' GROUP BY Comment.i_UID";
		$QLabel = "Number of Century Posts: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo $QLabel.$UInfRes->COUNTER."<BR>";
		else
			echo $QLabel."0<BR>";
		
		$Query = " SELECT COUNT(Comment.i_UID) AS COUNTER, Comment.i_UID FROM Comment, Users WHERE Comment.i_CommentId % 1000 = 0 AND 
		Comment.i_UID = Users.i_UID and Users.vc_Username = '".$UserName."' GROUP BY Comment.i_UID";
		$QLabel = "Number of Millenium Posts: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo $QLabel.$UInfRes->COUNTER."<BR>";
		else
			echo $QLabel."0<BR>";
		
		$Query = " SELECT COUNT(Comment.i_UID) AS COUNTER, Comment.i_UID FROM Comment, Users WHERE  
		Comment.i_UID = Users.i_UID and Users.vc_Username = '".$UserName."' GROUP BY Comment.i_UID";
		$QLabel = "Number of Total Posts: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo $QLabel.$UInfRes->COUNTER."<BR>";
		else
			echo $QLabel."0<BR>";
		
		$Query = " SELECT COUNT(Comment.i_UID) AS COUNTER, Comment.i_UID FROM Comment, Users WHERE  t_Comment LIKE '%!%' AND
		Comment.i_UID = Users.i_UID and Users.vc_Username = '".$UserName."' GROUP BY Comment.i_UID";
		$QLabel = "Number of '!' Posts: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo $QLabel.$UInfRes->COUNTER."<BR><BR>";
		else
			echo $QLabel."0<BR><BR>";

		// Date Joined
		$Query = " SELECT dt_DateJoined, dt_LastVisit, vc_URL FROM Users WHERE Users.vc_Username = '$UserName'";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->dt_DateJoined))
			echo "Date Joined: $UInfRes->dt_DateJoined<BR>";
		else
			echo "Date Joined: Never<BR>";
		if(!Empty($UInfRes->dt_LastVisit))
			echo "Last Visit: $UInfRes->dt_LastVisit<BR>";
		else
			echo "Last Visit: Never<BR>";
		$Query = " SELECT vc_Email, vc_URL FROM Users WHERE Users.vc_Username = '$UserName' AND b_PublicEmail";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->vc_Email)){
			$temp = str_replace("@", " <B>at</B> ", $UInfRes->vc_Email);
			$temp = str_replace(".", " <B>dot</B> ", $temp);
			echo "Email: $temp<BR>";
		}
		else
			echo "Email: NotAvailable <B>at</B> buggeroff <B>dot</B> com <BR>";

		if(!Empty($UInfRes->vc_URL))
			echo "Site: <A HREF=\"$UInfRes->vc_URL\">$UInfRes->vc_URL</A><BR>";
		else
			echo "Site: None<BR>";

		// First Post
		$Query = " SELECT MIN(Comment.i_CommentId) AS SM, MAX(Comment.i_CommentId) AS LG FROM Comment, Users WHERE Comment.i_UID = Users.i_UID and Users.vc_Username = '$UserName' GROUP BY Comment.i_UID";

		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->SM))
			echo "First Post: <A HREF=\"http://www.1142.org/main.php?ViewPost=$UInfRes->SM\">$UInfRes->SM</A><BR>";
		else
			echo "First Post: Never<BR>";

		if(!Empty($UInfRes->LG))
			echo "Last Post: <A HREF=\"http://www.1142.org/main.php?ViewPost=$UInfRes->LG\">$UInfRes->LG</A><BR>";
		else
			echo "Last Post: Never<BR>";

		echo "<a href=\"javascript:openpopup('$UserName')\">Click here to view daily usage</a>";

	}

?>
</CENTER><BR><BR><BR>
<FONT SIZE=1><CENTER><A HREF="http://www.1142.org">Main</A> | <A HREF="Statistics.php">Re-Select</A> | <A HREF="UserInfo.php">Users</A> | <A HREF="oddball.php">Random Statistics</A></CENTER></FONT>
</FONT>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysql_close($link);
	mysql_close($link1);
?>