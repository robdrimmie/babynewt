<?php
        include( "../include.php" );
// establish connection to MySQL database or output error message.
	$link = mysqli_connect ($dbHost, $dbUser, $dbPassword);
	if (!mysqli_select_db($dbName)) echo mysqli_errno().": ".mysqli_error()."<BR>";
/*
//	$link1 = mysqli_connect ("1142.org", "org1142", "ultima");
	if (!mysqli_select_db("org1142", $link1)) echo mysqli_errno().": ".mysqli_error()."<BR>";
*/
	class UserListItem{
	  var $UID;     // UserID number (private)
	  var $UName;   // Username
	  var $Email;   // Email Address
	  var $URL;     // Site URL
	  var $LCmtR;   // Last Comment Read
	  var $LCmtP;   // Last Comment Posted
	  var $DJ;      // Date joined
	  var $DLV;			// Date of last visit
	  var $SSheet;	// Style Sheet Name
	  var $TPosts;  // Total posts posted

	  function UserListItem(){
	  	$UID = 0;
	  	$UName = "Unknown";
	  	$Email = "Unknown";
	  	$URL = "Unknown";
	  	$LCmtR = 0;
	  	$LCmtP = "0";
	  	$DJ = "Unknown";
	  	$DLV = "Unknown";
	  	$SSheet = "Uknown";
	  	$TPosts = 0;
	  }
	}

//	$Query = "SELECT vc_Username AS UName, vc_Email AS Email, vc_URL AS URL, i_CommentID AS LCmt,
//	DATE_FORMAT(dt_DateJoined, \"%e.%c.%Y\") AS DJ, StyleSheet.vc_Name AS SSheet FROM Users, UserStyleSheet, StyleSheet
//	WHERE StyleSheet.i_StyleSheetId = UserStyleSheet.i_StyleSheetId
//	AND UserStyleSheet.i_UID = Users.i_UID
//	 ORDER BY UName";

	 	$Query = "SELECT i_UID, vc_Username AS UName, vc_Email AS Email, b_PublicEmail as PubEm, vc_URL AS URL, Users.i_CommentID AS LCmtR,
			DATE_FORMAT(dt_DateJoined, \"%e.%c.%Y\") AS DJ, DATE_FORMAT(dt_LastVisit, \"%e %M, %Y %r\") AS DLV, \"0\" AS LCmtP
			FROM Users";

		if($Order == "LastVisit") $Query .= " ORDER BY dt_LastVisit DESC";
		if($Order == "LastView")  $Query .= " ORDER BY LCmtR DESC";
		if($Order == "Join")      $Query .= " ORDER BY dt_DateJoined ASC";
		if($Order == "URL")      $Query .= " ORDER BY URL ASC";
		if($Order == "Name")      $Query .= " ORDER BY UName ASC";


		$UserList = array();

		$UInfId = mysqli_query ($link, $Query);
		while ($UInfRes = mysqli_fetch_object($UInfId)){
			$temp = new UserListItem;
			$temp->UID = $UInfRes->i_UID;
	  	$temp->UName = $UInfRes->UName;
		if($UInfRes->PubEm == 1)
		  	$temp->Email = $UInfRes->Email;
		else
			$temp->Email = "your_guess@is_as_good_as.mine";

	  	$temp->URL = $UInfRes->URL;
	  	$temp->LCmtR = $UInfRes->LCmtR;
	  	$temp->LCmtP = $UInfRes->LCmtP;
	  	$temp->DJ = $UInfRes->DJ;
	  	$temp->DLV = $UInfRes->DLV;
	  	$temp->SSheet = "Unknown";

	  	$UserList[$UInfRes->i_UID] = $temp;
		}
/*
		while(list ($key, $val) = each($UserList)){
			$Query = "SELECT MAX(i_CommentId) AS LCP, COUNT(i_CommentId) AS CNTR from Comment where i_UID = $key";
			$UInfId = mysqli_query ($link, $Query);
			$UInfRes = mysqli_fetch_object($UInfId);
			$val->LCmtP = $UInfRes->LCP;
			$val->TPosts = $UInfRes->CNTR;
			$UserList[$key] = $val;
		}
*/
		reset($UserList);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
	<TITLE> User Information (Sample Page) </TITLE>
	<META NAME="Author" CONTENT="Clayton Hannah">
	<META NAME="Keywords" CONTENT="Statistics, 1142">
	<META NAME="Description" CONTENT="A statistical listing of users on 1142">
	<STYLE>
	body {
  	background-color: #cde;
		font-family: Verdana;
		font-size:   14px;
	}
	p.Title {
		font-family: Verdana;
		font-size:   24px;
	}
	TD.HdrRow{
    background-color: #2F4F4F ;
    color: #FFFFFF;
		font-family:      Verdana;
		font-size:        11px;
		/* border:           1px #003 solid; */
		text-align:       center;
	}
	TD.RowA{
		background-color: #8FBC8F;
		color:            #000000;
		font-family:      Verdana;
		font-size:        9px;
		/* border:           1px #003 solid; */
		text-align:       center;
	}
	TD.RowB{
		background-color: #6D9A6D;
		color:            #000000;
		font-family:      Verdana;
		font-size:        9px;
		/* border:           1px #003 solid; */
		text-align:       center;
	}
	</STYLE>
</HEAD>

<BODY>
	<CENTER>
	<P CLASS=Title>User Roster for 1142.org</P>
	<TABLE BORDER=0 CELLSPACING=0 WIDTH=95%>
		<TR>
			<TD CLASS='HdrRow'><A HREF="weirdcrap.php?Order=Name">User Name</A></TD>
			<TD CLASS='HdrRow'>Email Address</TD>
			<TD CLASS='HdrRow'><A HREF="weirdcrap.php?Order=URL">Home Page</A></TD>
			<TD CLASS='HdrRow'>Last Posted</TD>
			<TD CLASS='HdrRow'><A HREF="weirdcrap.php?Order=LastView">Last Post Read</A></TD>
			<TD CLASS='HdrRow'>Stylesheet in Use</TD>
			<TD CLASS='HdrRow'><A HREF="weirdcrap.php?Order=Join">Member Since</A></TD>
			<TD CLASS='HdrRow'><A HREF="weirdcrap.php?Order=LastVisit">Last Visit</A></TD>
			<TD CLASS='HdrRow'>Number of Posts</TD>
		</TR>
		<?Php

			//<TD CLASS='HdrRow'>Number of Taglines</TD>
			//<TD CLASS='HdrRow'>Most Posts in 1 Day</TD>
			//<TD CLASS='HdrRow'>Most Posts in 1 Hour</TD>
			$EvenCnt = TRUE;
			$UCounter = 0;
			while(list ($key, $val) = each($UserList)){
			  $UCounter++;
				if($EvenCnt){
					echo "<TR>\n<TD CLASS='RowA'>";
					echo "<a href=\"UserInfo.php?UserName=$val->UName\">$val->UName</a>";
					echo "</TD>\n<TD CLASS='RowA'>";
					$temp = str_replace("@", " <B>at</B> ", $val->Email);
					$temp = str_replace(".", " <B>dot</B> ", $temp);
					echo " $temp</TD>\n<TD CLASS='RowA'><A HREF=\"$val->URL\">";
					 if(strpos($val->URL, "ttp://") == 1) echo substr($val->URL, 7);
					 else echo $val->URL;
					 echo "</A></TD>\n
					 <TD CLASS='RowA'><A HREF=\"http://www.1142.org/main.php?ViewPost=$val->LCmtP\">$val->LCmtP</A></TD>\n
					 <TD CLASS='RowA'><A HREF=\"http://www.1142.org/main.php?ViewPost=$val->LCmtR\">$val->LCmtR</A></TD>\n";
					 echo "<TD CLASS='RowA'>";
					 if(strlen($val->SSheet) < 15) echo $val->SSheet;
			     else echo substr($val->SSheet, 0, 12)."...";
			     //echo "</TD>\n<TD CLASS='RowA'>100</TD>\n<TD CLASS='RowA'>10</TD>\n
					 echo "<TD CLASS='RowA'>$val->DJ</TD>\n";
					 echo "<TD CLASS='RowA'>$val->DLV</TD>\n";
					 echo "<TD CLASS='RowA'>$val->TPosts</TD></TR>\n";
					 //<TD CLASS='RowA'>111</TD>\n
					 $EvenCnt = FALSE;
				}
				else{
					echo "<TR>\n<TD CLASS='RowB'>";
					echo "<a href=\"UserInfo.php?UserName=$val->UName\">$val->UName</a>";
					echo "</TD>\n<TD CLASS='RowB'>";
					$temp = str_replace("@", " <B>at</B> ", $val->Email);
					$temp = str_replace(".", " <B>dot</B> ", $temp);
					echo " $temp</TD>\n<TD CLASS='RowB'><A HREF=\"$val->URL\">";
			      if(strpos($val->URL, "ttp://") == 1) echo substr($val->URL, 7);
					  else echo $val->URL;
			      echo "</A></TD>\n\n<TD CLASS='RowB'><A HREF=\"http://www.1142.org/main.php?ViewPost=$val->LCmtP\">$val->LCmtP</A></TD>\n
			      <TD CLASS='RowB'><A HREF=\"http://www.1142.org/main.php?ViewPost=$val->LCmtR\">$val->LCmtR</A></TD>\n";
			     // <TD CLASS='RowB'>1957</TD>\n<TD CLASS='RowB'>111</TD>\n
			     echo " <TD CLASS='RowB'>";
			      if(strlen($val->SSheet) < 15) echo $val->SSheet;
			      else echo substr($val->SSheet, 0, 12)."...";
			     // echo "</TD>\n<TD CLASS='RowB'>100</TD>\n<TD CLASS='RowB'>10</TD>\n
			      echo "<TD CLASS='RowB'>$val->DJ</TD>\n\n";
			      echo "<TD CLASS='RowB'>$val->DLV</TD>\n\n";
			      echo "<TD CLASS='RowB'>$val->TPosts</TD></TR>\n";
					$EvenCnt = TRUE;
				}
			}
		?>
	</TABLE>
</CENTER>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysqli_close($link);
?>
