<?php

	include( "../session.php" );
	// establish connection to MySQL database or output error message.
	$link = mysql_connect ("1142.org", "org1142", "ultima");
	if (!mysql_select_db("org1142", $link)) echo mysql_errno().": ".mysql_error()."<BR>";
	
	$link1 = mysql_connect ("1142.org", "org1142", "ultima");
	if (!mysql_select_db("org1142", $link1)) echo mysql_errno().": ".mysql_error()."<BR>";

	$UList = FALSE;
	if(Empty($UserName)){
		$Title = " Please select a user";
		echo  "No user specified, please close window";
		$UList = TRUE;
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<?PhP
 echo "<TITLE>$UserName</TITLE>";
?>
<META NAME="Author" CONTENT="Clayton Hannah">
</HEAD>
<BODY MARGINWIDTH=0 MARGINHEIGHT=0>
<FONT FACE=VERDANA SIZE=3>

<?PhP 
	if($UList){
		echo  "No user specified, please close window";
	}
	else{
		echo "Daily Use Information for [$UserName]<BR>";
		$Query = " SELECT HOUR(dt_DatePOsted) AS LABELLER, COUNT(Comment.i_UID)/(TO_DAYS(now())-TO_DAYS(dt_DateJoined)) AS AVER, COUNT(Comment.i_UID) AS COUNTER, Comment.i_UID FROM Comment, Users WHERE
		Comment.i_UID = Users.i_UID and Users.vc_Username = '".$UserName."' GROUP BY Comment.i_UID, LABELLER ORDER BY LABELLER";
		
		$Q2 = " SELECT HOUR(dt_DatePOsted) AS LABELLER, COUNT(Comment.i_UID) AS RECENT FROM Comment, Users WHERE dt_DatePosted > SUBDATE(now(), INTERVAL 24 HOUR) AND 
		Comment.i_UID = Users.i_UID and Users.vc_Username = '".$UserName."' GROUP BY Comment.i_UID, LABELLER ORDER BY LABELLER";
		
		$QLabel = "<BI>Daily Posting</B>";
		$Peak = 0;
		
		$UInfId = mysql_query ($Query, $link);
		
		echo "<DIV STYLE=\"border: 1px black solid; Width=10%\">";
		echo "<TABLE CELLPADDING=0 CELLSPACING=1 BORDER=0><TR><TD COLSPAN=25 ALIGN=CENTER>".$QLabel."</TD>
		<TD ROWSPAN=2 VALIGN=MIDDLE><TABLE WIDTH=100><TR><TD><SMALL><B>Legend<B></SMALL></TD></TR><TR><TD><FONT SIZE=1><IMG SRC='bar1.gif' BORDER=1 WIDTH=6 HEIGHT=6> Average</FONT></TD></TR><TR><TD><FONT SIZE=1><IMG SRC='bar2.gif' BORDER=1 WIDTH=6 HEIGHT=6> Last 24 Hours</FONT></TD></TR></TABLE></TD>
		</TR><TR VALIGN=BOTTOM>";
		
		while ($UInfRes = mysql_fetch_object($UInfId)){
			$Q2 = " SELECT COUNT(Comment.i_UID) AS RECENT FROM Comment, Users WHERE dt_DatePosted > SUBDATE(now(), INTERVAL 24 HOUR) AND 
				HOUR(dt_DatePosted)= ".$UInfRes->LABELLER." AND Comment.i_UID = Users.i_UID and Users.vc_Username = '".$UserName."' ";//GROUP BY Comment.i_UID, LABELLER ORDER BY LABELLER";		
			$UInfId2 = mysql_query ($Q2, $link1);
			$UInfRes2 = mysql_fetch_object($UInfId2);
			//echo $UInfRes->LABELLER.": ".$UInfRes->COUNTER."<BR>";
			$Ht = $UInfRes->AVER*15;
			$Ht2 = $UInfRes2->RECENT*15;
			
			if($UInfRes->AVER > $Peak) $Peak = $UInfRes->AVER;
			if($UInfRes2->RECENT > $Peak) $Peak = $UInfRes2->RECENT;
			
			echo "<TD ALIGN=CENTER><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0><TR><TD ALIGN=CENTER><IMG SRC='bar1.gif' BORDER=1 WIDTH=6 HEIGHT=".$Ht." ALT=\"Avg: ".$UInfRes->AVER." Posts in Hour ".$UInfRes->LABELLER."\"><IMG SRC='bar2.gif' BORDER=1 WIDTH=6 HEIGHT=".$Ht2." ALT=\"Today: ".$UInfRes2->RECENT." Posts in Hour ".$UInfRes->LABELLER."\"></TD></TR><TR><TD ALIGN=CENTER><FONT SIZE=2>".$UInfRes->LABELLER."</FONT></TD></TR></TABLE></TD>";
		}
		echo "<TD VALIGN=TOP STYLE=\"border-left: 1px black solid;\"><FONT SIZE=1>".$Peak."</FONT></TD>";
		echo "</TR></TABLE>";
		echo "</DIV>";
	}
?>
</CENTER>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysql_close($link);
	mysql_close($link1);
?>