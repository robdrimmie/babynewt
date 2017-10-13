<?php
	include( "../session.php" );
  // Prior to 84000
	$GraphTitle = "Where is the title?";
	include("../include.php");
	// establish connection to MySQL database or output error message.
	$link = mysqli_connect ($dbHost, $dbUser, $dbPassword);
	if (!mysqli_select_db($dbName)) echo mysqli_errno().": ".mysqli_error()."<BR>";
//	$link = mysqli_connect ("localhost");
//	if (!mysqli_select_db("1142test")) echo mysqli_errno().": ".mysqli_error()."<BR>";
	if(!Empty($Type)){
		if($Type=="HATTag"){
			$GraphTitle = "Number of Taglines by Hour, All Time";
			$CommentsQuery = " SELECT HOUR(dt_datePosted) AS LABELLER, COUNT(dt_datePosted) AS COUNTER
								FROM Comment
								WHERE t_Comment LIKE '%1142:%'
								GROUP BY LABELLER ORDER BY LABELLER";
		}
		else if ($Type=="HATPost"){
			$GraphTitle = "Number of Posts by Hour, All Time";
			$CommentsQuery = " SELECT HOUR(dt_datePosted) AS LABELLER, COUNT(dt_datePosted) AS COUNTER";
			$CommentsQuery .= " FROM Comment";
			$CommentsQuery .= " GROUP BY LABELLER	ORDER BY LABELLER";
		}
		else if ($Type=="MYPost"){
			$GraphTitle = "Number of Posts by Month, Last 12 Months";
			$CommentsQuery = " SELECT MONTHNAME(dt_datePosted) AS LABELLER, COUNT(dt_datePosted) AS COUNTER";
			$CommentsQuery .= " FROM Comment WHERE dt_datePosted > SUBDATE(NOW(), INTERVAL 12 MONTH)";
			$CommentsQuery .= " GROUP BY LABELLER ORDER BY MONTH(dt_datePosted)";
		}
		else if ($Type=="WYPost"){
			$GraphTitle = "Number of Posts by Week, Last 52 Weeks";
			$CommentsQuery = " SELECT WEEK(dt_datePosted) AS LABELLER, COUNT(dt_datePosted) AS COUNTER";
			$CommentsQuery .= " FROM Comment WHERE dt_datePosted > SUBDATE(NOW(), INTERVAL 365 MONTH)";
			$CommentsQuery .= " GROUP BY LABELLER ORDER BY LABELLER";
		}
		else if ($Type=="DMPost"){
			$GraphTitle = "Number of Posts by Day of Month, Selected Month";
			$CommentsQuery = " SELECT DAYOFMONTH(dt_datePosted) AS LABELLER, COUNT(dt_datePosted) AS COUNTER";
			$CommentsQuery .= " FROM Comment WHERE MONTH(dt_datePosted) = ".$SelMon." AND YEAR(dt_datePosted) = ".$SelYear;
			$CommentsQuery .= " GROUP BY LABELLER	ORDER BY LABELLER";
		}
		else if ($Type=="HDPost"){
			$GraphTitle = "Number of Posts by Hour of Day, Selected Day";
			$CommentsQuery = " SELECT HOUR(dt_datePosted) AS LABELLER, COUNT(dt_datePosted) AS COUNTER";
			$CommentsQuery .= " FROM Comment WHERE DAYOFMONTH(dt_DatePosted) = ".$SelDay." AND MONTH(dt_datePosted) = ".$SelMon." AND YEAR(dt_datePosted) = ".$SelYear;
			$CommentsQuery .= " GROUP BY LABELLER ORDER BY LABELLER";
		}
		else if ($Type=="UserTag"){
			$GraphTitle = "Number of Taglines by User, All Time";
			$CommentsQuery = " SELECT vc_Username AS LABELLER, COUNT(t_Comment) AS COUNTER";
			$CommentsQuery .= " FROM Comment, Users WHERE Comment.i_UID = Users.i_UID AND t_Comment LIKE '%1142:%'";
			$CommentsQuery .= " GROUP BY LABELLER ORDER BY COUNTER DESC";
		}
		else if ($Type=="UserPost"){
			$GraphTitle = "Number of Posts by User, All Time";
			$CommentsQuery = " SELECT vc_Username AS LABELLER, COUNT(t_Comment) AS COUNTER";
			$CommentsQuery .= " FROM Comment, Users WHERE Comment.i_UID = Users.i_UID";
			$CommentsQuery .= " GROUP BY LABELLER	ORDER BY COUNTER DESC";
		}
		else if ($Type=="CatPost"){
			$GraphTitle = "Number of Posts by Category, All Time";
			$CommentsQuery = " SELECT vc_Name AS LABELLER, COUNT(t_Comment) AS COUNTER";
			$CommentsQuery .= " FROM Comment, Category WHERE Category.i_CategoryId = Comment.i_CategoryId";
			$CommentsQuery .= " GROUP BY LABELLER	ORDER BY COUNTER DESC";
		}
		else if ($Type=="UserCatPost"){
			$GraphTitle = "Number of Category Posts by User, Selected Category";
			$CommentsQuery = " SELECT vc_Username AS LABELLER, COUNT(t_Comment) AS COUNTER";
			$CommentsQuery .= " FROM Comment, Users	WHERE Comment.i_UID = Users.i_UID AND i_CategoryId = ".$SelCat;
			$CommentsQuery .= " GROUP BY LABELLER	ORDER BY COUNTER DESC";
		}
		else if ($Type=="UserCentury"){
			$GraphTitle = "Number of Century Posts by User, All Time";
			$CommentsQuery = " SELECT vc_Username AS LABELLER, COUNT(t_Comment) AS COUNTER";
			$CommentsQuery .= " FROM Comment, Users WHERE Comment.i_UID = Users.i_UID AND Comment.i_CommentId % 100 = 0";
			$CommentsQuery .= " GROUP BY LABELLER	ORDER BY COUNTER DESC";
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>1142.org Statistics</TITLE>
<META NAME="Author" CONTENT="Clayton Hannah">
<BODY MARGINWIDTH=0 MARGINHEIGHT=0>
<FONT FACE=VERDANA SIZE=3>

<?PhP
	$ShowGraph = TRUE;
	if(!Empty($Type)){
		// Check for querystring requirements
		if($Type=="DMPost"){
			// Check for month, year
			if(Empty($SelMon) || Empty($SelYear)){
				$ShowGraph = FALSE;
				echo "<form name=\"frmHDPost\" action=\"Statistics.php?Type=DMPost\" method=\"post\">";
				echo "<input type=\"hidden\" name=\"hdnUID\" value=\"$sessionUserId\">";
				echo "<select name=\"SelMon\" class=\"selectMon\">";
				echo "<OPTION VALUE=1>January</OPTION>";
				echo "<OPTION VALUE=2>February</OPTION>";
				echo "<OPTION VALUE=3>March</OPTION>";
				echo "<OPTION VALUE=4>April</OPTION>";
				echo "<OPTION VALUE=5>May</OPTION>";
				echo "<OPTION VALUE=6>June</OPTION>";
				echo "<OPTION VALUE=7>July</OPTION>";
				echo "<OPTION VALUE=8>August</OPTION>";
				echo "<OPTION VALUE=9>September</OPTION>";
				echo "<OPTION VALUE=10>October</OPTION>";
				echo "<OPTION VALUE=11>November</OPTION>";
				echo "<OPTION VALUE=12>December</OPTION>";
				echo "</select>";
				echo "<select name=\"SelYear\" class=\"selectYear\">";
				echo "<OPTION VALUE=2001>2001</OPTION>";
				echo "</select>";
				echo "<input type=\"submit\" name=\"btnSubmitCCatSel\" value=\"Submit Date Selection\">";
				// Show a list of months form, with list of years, 2001 until present in dropdown box
			}
		}
		if($Type=="HDPost"){
			// Check for Day, Month, Year
			if(Empty($SelDay) || Empty($SelMon) || Empty($SelYear)){
				$ShowGraph = FALSE;
				$i = 1;
				echo "<form name=\"frmHDPost\" action=\"Statistics.php?Type=HDPost\" method=\"post\">";
				echo "<input type=\"hidden\" name=\"hdnUID\" value=\"$sessionUserId\">";
				echo "<select name=\"SelDay\" class=\"selectDay\">";
				while($i < 32){
					echo "<OPTION VALUE=".$i.">";
					echo $i;
					echo "</OPTION>";
					$i++;
				}
				echo "</select>";
				echo "<select name=\"SelMon\" class=\"selectMon\">";
				echo "<OPTION VALUE=1>January</OPTION>";
				echo "<OPTION VALUE=2>February</OPTION>";
				echo "<OPTION VALUE=3>March</OPTION>";
				echo "<OPTION VALUE=4>April</OPTION>";
				echo "<OPTION VALUE=5>May</OPTION>";
				echo "<OPTION VALUE=6>June</OPTION>";
				echo "<OPTION VALUE=7>July</OPTION>";
				echo "<OPTION VALUE=8>August</OPTION>";
				echo "<OPTION VALUE=9>September</OPTION>";
				echo "<OPTION VALUE=10>October</OPTION>";
				echo "<OPTION VALUE=11>November</OPTION>";
				echo "<OPTION VALUE=12>December</OPTION>";
				echo "</select>";
				echo "<select name=\"SelYear\" class=\"selectYear\">";
				echo "<OPTION VALUE=2001>2001</OPTION>";
				echo "</select>";
				echo "<input type=\"submit\" name=\"btnSubmitCCatSel\" value=\"Submit Date Selection\">";
			  // Show a 1 year calendar form, with list of years, 2001 until present in dropdown box
			}
		}
		if($Type=="UserCatPost"){
			// Check for category
			if(Empty($SelCat))
			{
				$ShowGraph = FALSE;
				// Show a list of categories to select from
				$CategoryListQuery = "SELECT i_CategoryId, vc_Name FROM Category ORDER BY vc_Name";
				$CategoryListResultId = mysqli_query ($link, $CategoryListQuery);

				// Inputs for a new comment
				echo "<form name=\"frmUserCatPost\" action=\"Statistics.php?Type=UserCatPost\" method=\"post\">";
				echo "<input type=\"hidden\" name=\"hdnUID\" value=\"$sessionUserId\">";

				echo '<input type="submit" name="btnSubmitCCatSel">';
			}
		}
	}
	else{
	?>
		The following graphs are available for viewing:
		<UL>
		<LI><A HREF="Statistics.php?Type=HATTag">Tagline by Hour, All Time</A>
		<LI><A HREF="Statistics.php?Type=HATPost">Post by Hour, All Time</A>
		<LI><A HREF="Statistics.php?Type=MYPost">Posts by Month, last 12 months</A>
		<LI><A HREF="Statistics.php?Type=WYPost">Post by Week, last 52 Weeks</A>
		<LI><A HREF="Statistics.php?Type=DMPost">Post by Day, Selected Month</A>
		<LI><A HREF="Statistics.php?Type=HDPost">Post by Hour, Selected Day</A>
		<LI><A HREF="Statistics.php?Type=UserTag">Taglines by User, All Time</A>
		<LI><A HREF="Statistics.php?Type=UserPost">Post by User, All Time</A>
		<LI><A HREF="Statistics.php?Type=CatPost">Post by Category, All Time</A>
		<LI><A HREF="Statistics.php?Type=UserCatPost">Post by User, Selected Category</A>
		<LI><A HREF="Statistics.php?Type=UserCentury">Century Posts by User, All Time</A>
		</UL>
	<?PhP
	}
?>
</CENTER><BR><BR><BR>
<FONT SIZE=1><A HREF="http://www.1142.org">Main</A> | <A HREF="Statistics.php">Re-Select</A></FONT>
<
