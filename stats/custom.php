<?php
	require_once '../include.php';
	require_once '../session.php';

	if( array_key_exists( 'Category', $_REQUEST ) ) {
	  $selectedCategory = $_REQUEST[ 'Category' ];
	} else {
		$selectedCategory = 1;
	}

	$title = "Search";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
	<title>1142 Comment Search</title>
	</head>
	<body>

<?php
	$CatOptions = "<option value=\"-1\">search all categories</option>";
	$CategoryListQuery = "SELECT i_CategoryId, vc_Name FROM Category ORDER BY vc_Name";
	$CategoryListResultId =  mysql_query ($CategoryListQuery, $link);

  	while( $CategoryListResult = mysql_fetch_object($CategoryListResultId)){
  		$CatOptions .= "<OPTION VALUE=\"$CategoryListResult->i_CategoryId\"";
        if( $selectedCategory == $CategoryListResult->i_CategoryId ) $CatOptions .= " SELECTED";
    		$CatOptions .= ">$CategoryListResult->vc_Name</OPTION>";
	  }    
    
            // converted to while loop oct 16 2002
    $intMinCommentArchive = 1;
    $intTrueMaxCommentArchive = 31;
    $intMaxCommentArchive = $intTrueMaxCommentArchive;
    $strCurrentArchive = "";

    $intFirstCommentId = 0;
    $intLastCommentId = -1;
    $strCommentRange = "";

    $LookFor = '';
    if( array_key_exists( 'LookFor', $_REQUEST ) ) {
		$LookFor = $_REQUEST[ 'LookFor' ];
	}
	
    $ArchiveToSearch = '';	
	if( array_key_exists( 'ArchiveToSearch', $_REQUEST ) ) {
		$ArchiveToSearch = $_REQUEST[ 'ArchiveToSearch' ];
	}

    $ByUser = '';	
	if( array_key_exists( 'ByUser', $_REQUEST ) ) {
		$ByUser = $_REQUEST[ 'ByUser' ];
	}

	$Total = 0;
	echo "<h1>".$title."</h1>";
	if(!Empty($LookFor)){
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

		$Query.="	WHERE t_Comment LIKE '%$LookFor%'";

    if( $selectedCategory > 0 ) {
      $Query.= " AND i_CategoryId = $selectedCategory";
    }

		$Q2 = " 	SELECT $strCurrentArchive.i_CommentId AS 
              LABELLER FROM $strCurrentArchive ";

 		if( $ByUser != "" ) {
			$Q2.= "JOIN Users On Users.i_UID =
					$strCurrentArchive.i_UID
				AND Users.vc_Username = \"$ByUser\" ";
		}

		$Q2.= "	WHERE t_Comment LIKE '%$LookFor%' ";

    if( $selectedCategory > 0 ) {
      $Q2.= " AND i_CategoryId = $selectedCategory";
    }
		$Q2.= "	ORDER BY $strCurrentArchive.i_CommentId ASC";

//	  echo $Query;
      
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);

		$intFirstCommentId = ( ($intCurrentCA-1) * 50000) + 1;
		$intLastCommentId = $intFirstCommentId + 49999;

		$strCommentRange = "$intFirstCommentId - $intLastCommentId";

		if(!Empty($UInfRes->COUNTER)){ echo "<HR>$UInfRes->COUNTER Results in $strCommentRange <BR>"; $Total += $UInfRes->COUNTER;}
		else echo "<hr />0 Results in $strCommentRange<br />";
		$UInfId2 = mysql_query ($Q2, $link);
		while($UInfRes = mysql_fetch_object($UInfId2)){
			echo "<A HREF=\"../main.php?ViewPost=$UInfRes->LABELLER\">p$UInfRes->LABELLER</A> | ";
		}
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
		$Query.="	WHERE t_Comment LIKE '%$LookFor%' ";

    if( $selectedCategory > 0 ) {
      $Query.= " AND i_CategoryId = $selectedCategory";
    }


		$Q2 = " 	SELECT $strCurrentArchive.i_CommentId AS LABELLER 
				FROM $strCurrentArchive ";
		if( $ByUser != "" ) {
			$Q2.="JOIN Users on Users.i_UID = Comment.i_UID
				AND Users.vc_Username = \"$ByUser\" ";
		}
		$Q2.= "	WHERE t_Comment LIKE '%$LookFor%' ";

    if( $selectedCategory > 0 ) {
      $Q2.= " AND i_CategoryId = $selectedCategory";
    }
		$Q2.= "	ORDER BY $strCurrentArchive.i_CommentId ASC";

    $Query = $Q2;
		$UInfId = mysql_query ($Query, $link);
		$UInfRes = mysql_fetch_object($UInfId);

		$intFirstCommentId = ($intTrueMaxCommentId * 50000) + 1;
		$intLastCommentId = $intFirstCommentId + 49999;

		$strCommentRange = "Current Comment Table";

		if(!Empty($UInfRes->COUNTER)){ echo "<HR>$UInfRes->COUNTER Results in $strCommentRange <BR>"; $Total += $UInfRes->COUNTER;}
		else echo "<HR>0 Results in $strCommentRange<BR>";
		$UInfId2 = mysql_query ($Q2, $link);
		while($UInfRes = mysql_fetch_object($UInfId2)){
			echo "<A HREF=\"../main.php?ViewPost=$UInfRes->LABELLER\">p$UInfRes->LABELLER</A> | ";
		}
	    }
	}
?>
</span><br /><br />
<?Php
	echo "$Total Total Results Found";
?>
<br />
<br />
	<form action="custom.php" method="post">
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
<font size='1'><center><a href="http://www.1142.org">Main</a> | <a href="Statistics.php">Re-Select</A> | <A HREF="UserInfo.php">Users</A> | <A HREF="oddball.php">Random Statistics</A></CENTER></FONT>
</FONT>
</body>
</html>
