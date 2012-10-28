<?php

	include( "../session.php" );
	include("../include.php");
	// establish connection to MySQL database or output error message.
	$link = mysql_connect ($dbHost, $dbUser, $dbPassword);
	if (!mysql_select_db($dbName, $link)) echo mysql_errno().": ".mysql_error()."<BR>";
	
	$Title = " Oddball Stats";
	$Query = "SELECT vc_Username AS LABEL FROM Users ORDER BY LABEL";
	$Query = " COUNT(t_Comment) AS COUNTER FROM Comment WHERE t_Comment LIKE '1142:%";
	$QLabel = " Number of Taglines:";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>1142.org Oddball Stats</TITLE>
<META NAME="Author" CONTENT="Clayton Hannah">

</HEAD>
<BODY MARGINWIDTH=0 MARGINHEIGHT=0>
<FONT FACE=VERDANA SIZE=3>

<?PhP 
	echo $Title."<BR><BR>";
		echo "<TABLE BORDER=1>";
		$Query = " SELECT COUNT(Comment.i_UID) AS COUNTER FROM Comment";
		$QLabel = "Total Posts: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>".$UInfRes->COUNTER."</TD></TR>";
		else
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>0</TD></TR>";
		
		$Query = " SELECT COUNT(t_Comment) AS COUNTER FROM Comment WHERE t_Comment like '%1142:%'";
		$QLabel = "Number of Taglines: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>".$UInfRes->COUNTER."</TD></TR>";
		else
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>0</TD></TR>";
		
		$Query = " SELECT COUNT(t_Comment) AS COUNTER FROM Comment WHERE t_Comment like '%Zippity Bop%'";
		$QLabel = "Number of Zippity Bops: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>".$UInfRes->COUNTER."</TD></TR>";
		else
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>0</TD></TR>";
		
		$Query = " SELECT COUNT(t_Comment) AS COUNTER FROM Comment WHERE t_Comment like '%Zippity Bot%'";
		$QLabel = "Number of Zippity Bot: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>".$UInfRes->COUNTER."</TD></TR>";
		else
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>0</TD></TR>";
		
		$Query = " SELECT COUNT(t_Comment) AS COUNTER FROM Comment WHERE t_Comment like '%Zippity Bot%' AND i_CommentId % 100 = 0";
		$QLabel = "Number Zippity Bot Centuries: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>".$UInfRes->COUNTER."</TD></TR>";
		else
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>0</TD></TR>";
			
		$Query = " SELECT COUNT(t_Comment) AS COUNTER FROM Comment WHERE t_Comment like '%Zippity%'";
		$QLabel = "Number of Zippity Posts: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>".$UInfRes->COUNTER."</TD></TR>";
		else
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>0</TD></TR>";
			
		$Query = " SELECT COUNT(t_Comment) AS COUNTER FROM Comment, Users WHERE (t_Comment like 'Zippity BOT&trade;!' 
		OR t_Comment like 'Zippity BOT!' OR t_Comment like 'Zippity BOT&trade;' OR t_Comment like 'Zippity BOT') AND
		Comment.i_UID = Users.i_UID and Users.vc_Username = 'daveadams'";
		$QLabel = "Number of daveadams Zippity Bot: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>".$UInfRes->COUNTER."</TD></TR>";
		else
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>0</TD></TR>";
			
			
		$Query = " SELECT COUNT(t_Comment) AS COUNTER FROM Comment WHERE t_Comment like '%snarf%' AND i_CommentId % 100 = 0";
		$QLabel = "Number snarf Centuries: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>".$UInfRes->COUNTER."</TD></TR>";
		else
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>0</TD></TR>";
			
		$Query = " SELECT COUNT(t_Comment) AS COUNTER FROM Comment WHERE t_Comment like '%snarf%'";
		$QLabel = "Number of snarf posts: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>".$UInfRes->COUNTER."</TD></TR>";
		else
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>0</TD></TR>";
		
		$Query = " SELECT COUNT(t_Comment) AS COUNTER FROM Comment WHERE t_Comment like '%11:42%'";
		$QLabel = "Number of 11:42 A/P.M. AAAAHHH!!!: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>".$UInfRes->COUNTER."</TD></TR>";
		else
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>0</TD></TR>";
			
		$Query = " SELECT COUNT(vc_Username) AS COUNTER FROM Users";
		$QLabel = "Number of Users: ";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);
		if(!Empty($UInfRes->COUNTER))
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>".$UInfRes->COUNTER."</TD></TR>";
		else
			echo "<TR><TD>".$QLabel."</TD><TD ALIGN=RIGHT>0</TD></TR>";
			
		echo "</TABLE>"

?>
</CENTER><BR><BR><BR>
<FONT SIZE=1><CENTER><A HREF="http://www.1142.org">Main</A> | <A HREF="Statistics.php">Re-Select</A> | <A HREF="UserInfo.php">Users</A> | <A HREF="oddball.php">Random Statistics</A></CENTER></FONT>
</FONT>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysql_close($link);
?>