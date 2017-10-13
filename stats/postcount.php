<?php
// created 18 january 2006, based on 1142.org/stats/custom.php -- rmd.

// update 24 January 2005, added functionality to search by username and/or specific comment archive.  (Rob)

  $SelectedCategory = $_REQUEST[ "Category" ];
  //	include( "../session.php" );
	include("../include.php");
	// establish connection to MySQL database or output error message.
	$link = mysqli_connect ($dbHost, $dbUser, $dbPassword);
	if (!mysqli_select_db($dbName)) echo mysqli_errno().": ".mysqli_error()."<BR>";
	
	$Title = "Search";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>1142 Comment Search</title>

</head>
<body>

<?php
    // added categories july 10, 2005
   	// Do-wop the category option list
  	$CatOptions = "<option value=\"-1\">search all categories</option>";
  	$CategoryListQuery = "SELECT i_CategoryId, vc_Name FROM Category ORDER BY vc_Name";
  	$CategoryListResultId =  mysqli_query ($link, $CategoryListQuery);

  	while( $CategoryListResult = mysqli_fetch_object($CategoryListResultId)){
  		$CatOptions .= "<OPTION VALUE=\"$CategoryListResult->i_CategoryId\"";
        if( $SelectedCategory == $CategoryListResult->i_CategoryId ) $CatOptions .= " SELECTED";
    		$CatOptions .= ">$CategoryListResult->vc_Name</OPTION>";
	  }    
    
            // converted to while loop oct 16 2002
    $intMinCommentArchive = 1;
    $intTrueMaxCommentArchive = 29;
    $intMaxCommentArchive = $intTrueMaxCommentArchive;
    $strCurrentArchive = "";

    $intFirstCommentId = 0;
    $intLastCommentId = -1;
    $strCommentRange = "";

		$LookFor = $_REQUEST[ 'LookFor' ];
		$ArchiveToSearch = $_REQUEST[ 'ArchiveToSearch' ];
		$ByUser = $_REQUEST[ 'ByUser' ];

	$Total = 0;
	echo "<h1>".$Title."</h1>";
//	if(!Empty($LookFor)){
	    echo "Results for <B>$LookFor</B>";
	    if( $ByUser != "" ) {
		echo " posted by user <b>$ByUser</b>";
	    }
	    if( $ArchiveToSearch > 0 ) {
		echo " in CommentArchive$ArchiveToSearch ";
		$intMinCommentArchive = $ArchiveToSearch;
		$intMaxCommentArchive = $ArchiveToSearch;
	    }
	   if( $ArchiveToSearch == 0 ) {
		$intMinCommentArchive = 1001;
		$intMaxCommentArchive = 0;
	   }
	

	    echo "<span style=\"font-size: 10px;\"> ";

	    for( $intCurrentCA = $intMinCommentArchive; $intCurrentCA <= $intMaxCommentArchive; $intCurrentCA++ )
	    {
		$strCurrentArchive = "CommentArchive$intCurrentCA";

		$Query = 	"SELECT COUNT(t_Comment) AS COUNTER 
				FROM $strCurrentArchive ";
		if( $ByUser != "" ) {
			$Query.= "JOIN Users On Users.i_UID = 
					$strCurrentArchive.i_UID
				AND Users.vc_Username = \"$ByUser\" ";
		} 

    if( $SelectedCategory > 0 ) {
      $Query.= " WHERE i_CategoryId = $SelectedCategory";
    }

		$UInfId = mysqli_query ($link, $Query);
		$UInfRes = mysqli_fetch_object($UInfId);

		$intFirstCommentId = ( ($intCurrentCA-1) * 50000) + 1;
		$intLastCommentId = $intFirstCommentId + 49999;

		$strCommentRange = "$intFirstCommentId - $intLastCommentId";

		if(!Empty($UInfRes->COUNTER)){ echo "<HR>$UInfRes->COUNTER Results in $strCommentRange <BR>"; $Total += $UInfRes->COUNTER;}
		else echo "<hr />0 Results in $strCommentRange<br />";
		$UInfId2 = mysqli_query ($link, $Q2);
	    }

// search comment table
	    if( $ArchiveToSearch < 1 ) {	
		$strCurrentArchive = "Comment";
	    	
		$Query = " 	SELECT COUNT(t_Comment) AS COUNTER 
				FROM $strCurrentArchive ";
 		if( $ByUser != "" ) {
			$Query.="JOIN Users on Users.i_UID = Comment.i_UID
				AND Users.vc_Username = \"$ByUser\" ";
		}
    if( $SelectedCategory > 0 ) {
      $Query.= " WHERE i_CategoryId = $SelectedCategory";
    }

		$UInfId = mysqli_query ($link, $Query);
		$UInfRes = mysqli_fetch_object($UInfId);

		$intFirstCommentId = ($intTrueMaxCommentId * 50000) + 1;
		$intLastCommentId = $intFirstCommentId + 49999;

		$strCommentRange = "Current Comment Table";

		if(!Empty($UInfRes->COUNTER)){ echo "<HR>$UInfRes->COUNTER Results in $strCommentRange <BR>"; $Total += $UInfRes->COUNTER;}
		else echo "<HR>0 Results in $strCommentRange<BR>";

	    }
//	}
?>
</span><br /><br />
<?Php
	echo "$Total Total Results Found";
?>
<br />
<br />
	<form action="postcount.php" method="post">
	<table>
		<tr>
			<td width="20%">
				Search Phrase:
			</td>
			<td>
				<input name="LookFor" TYPE="Text" 
value="<?php echo $LookFor; ?>">
			</td>
		</tr>
		<tr>
			<td>
           			Search by user
			</td>
			<td>
				<input type="text" name="ByUser" 
value="<?php echo $ByUser ?>" />
			</td>
		</tr>
		<tr>
			<td>
				Search comment archive:
			</td>
			<td>
				<select name="ArchiveToSearch">
					<option value="-1" 
<?php if( $ArchiveToSearch == -1 ) { echo "selected"; } ?>
				>Search all archives</option>
					<option value="0"
<?php if( $ArchiveToSearch == 0 ) { echo "selected"; } ?>
				>Search current comment table</option>
<?php
for( $intCurrentCA = 1; $intCurrentCA <= 
$intTrueMaxCommentArchive; $intCurrentCA++ ) {
	echo "<option value=\"$intCurrentCA\"";
	if( $intCurrentCA == $ArchiveToSearch ) {
		echo " selected ";
	}
	echo ">Comment Archive $intCurrentCA</option>";
}

?>
				</select>
			</td>
		</tr>
    <tr>
      <td>Select a category to search
      </td>
      <td>
        <select name="Category">
          <?php
            echo $CatOptions
          ?>          
        </select>
      </td>
    </tr>
    </tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="Submit"></td>
		</tr>
	</form>
	</table>
</CENTER><BR><BR><BR>
<FONT SIZE=1><CENTER><A HREF="http://www.1142.org">Main</A> | <A HREF="Statistics.php">Re-Select</A> | <A HREF="UserInfo.php">Users</A> | <A HREF="oddball.php">Random Statistics</A></CENTER></FONT>
</FONT>
</BODY>
</HTML>
<?php
	// close connection to MySQL Database
	mysqli_close($link);
?>
