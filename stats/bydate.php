<?php

//	include( "../session.php" );
	include("../include.php");
	// establish connection to MySQL database or output error message.
	$link = mysql_connect ($dbHost, $dbUser, $dbPassword);
	if (!mysql_select_db($dbName, $link)) echo mysql_errno().": ".mysql_error()."<BR>";
	
	$Title = " Custom Stats";
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
    // converted to while loop oct 16 2002
    $intMinCommentArchive = 1;
    $intMaxCommentArchive = 29;
    $strCurrentArchive = "";

    $intFirstCommentId = 0;
    $intLastCommentId = -1;
    $strCommentRange = "";
$LookFor = $_POST[ "LookFor" ];
	$Total = 0;
	echo $Title."<BR><BR>";
	if(!Empty($LookFor)){
	    echo $chkOne;

	    echo "Results for <B>$LookFor</B>";
	    echo "<FONT SIZE=1> ";

	    for( $intCurrentCA = $intMinCommentArchive; $intCurrentCA <= $intMaxcommentArchive; $intCurrentCA++ )
	    {

		$strCurrentArchive = "CommentArchive$intCurrentCA";

		$Query = " SELECT COUNT(t_Comment) AS COUNTER FROM 
$strCurrentArchive WHERE dt_DatePosted > '$LookFor'";
		$Q2 = " SELECT i_CommentId AS LABELLER FROM 
$strCurrentArchive WHERE dt_DatePosted > '$LookFor' ORDER BY 
i_CommentId ASC";
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);

		$intFirstCommentId = $intLastCommentId + 1;
		$intLastCommentId = $intFirstCommentId + 49999;

		$strCommentRange = "$intFirstCommentId - $intLastCommentId";

		if(!Empty($UInfRes->COUNTER)){ echo "<HR>$UInfRes->COUNTER Results in $strCommentRange <BR>"; $Total += $UInfRes->COUNTER;}
		else echo "<HR>0 Results in $strCommentRange<BR>";
		$UInfId2 = mysql_query ($Q2, $link);
		while($UInfRes = mysql_fetch_object($UInfId2)){
			echo "<A HREF=\"http://www.1142.net/main.php?ViewPost=$UInfRes->LABELLER\">p$UInfRes->LABELLER</A> | ";
		}
	    }

// search comment table
		$strCurrentArchive = "Comment";

		$Query = " SELECT COUNT(t_Comment) AS COUNTER FROM 
$strCurrentArchive WHERE dt_DatePosted >= '$LookFor' LIMIT 1";
		$Q2 = " SELECT i_CommentId AS LABELLER FROM 
$strCurrentArchive WHERE dt_DatePosted >= '$LookFor' ORDER BY 
i_CommentId ASC LIMIT 1";
	
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);

		$intFirstCommentId = $intLastCommentId + 1;
		$intLastCommentId = $intFirstCommentId + 49999;

		$strCommentRange = "$intFirstCommentId - $intLastCommentId";

		if(!Empty($UInfRes->COUNTER)){ echo "<HR>$UInfRes->COUNTER Results in $strCommentRange <BR>"; $Total += $UInfRes->COUNTER;}
		else echo "<HR>0 Results in $strCommentRange<BR>";
		$UInfId2 = mysql_query ($Q2, $link);
		while($UInfRes = mysql_fetch_object($UInfId2)){
			echo "<A HREF=\"http://www.1142.net/main.php?ViewPost=$UInfRes->LABELLER\">p$UInfRes->LABELLER</A> | ";
		}
	}
?>
</FONT><BR>
<?Php
	echo "$Total Total Results Found";
?>
<BR>
<BR>
Try again? (hyphens required)

	<FORM ACTION="bydate.php" method="post">
		<INPUT NAME="LookFor" TYPE="Text" value="yyyy-mm-dd">
                <br />
		<INPUT TYPE="Submit">
	</FORM>
</CENTER><BR><BR><BR>
<FONT SIZE=1><CENTER><A HREF="http://www.1142.org">Main</A> | <A HREF="Statistics.php">Re-Select</A> | <A HREF="UserInfo.php">Users</A> | <A HREF="oddball.php">Random Statistics</A></CENTER></FONT>
</FONT>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysql_close($link);
?>
