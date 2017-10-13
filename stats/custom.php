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
	$CategoryListResultId =  mysqli_query ($link, $CategoryListQuery);

  	while( $CategoryListResult = mysqli_fetch_object($CategoryListResultId)) {
  		$CatOptions .= "<OPTION VALUE=\"$CategoryListResult->i_CategoryId\"";
  		
        if( $selectedCategory == $CategoryListResult->i_CategoryId ) $CatOptions .= " SELECTED";
    		$CatOptions .= ">$CategoryListResult->vc_Name</OPTION>";
	  	}

   $strCommentRange = "";

    $LookFor = '';
    if( array_key_exists( 'LookFor', $_REQUEST ) ) {
		$LookFor = $_REQUEST[ 'LookFor' ];
	}

    $ByUser = '';	
	if( array_key_exists( 'ByUser', $_REQUEST ) ) {
		$ByUser = $_REQUEST[ 'ByUser' ];
	}

	$Total = 0;
	echo "<h1>".$title."</h1>";
	if(!Empty($LookFor)){
	    echo "Results for <b>$LookFor</b>";
	    if( $ByUser != "" ) {
		echo " posted by user <b>$ByUser</b>";
	    }

	    echo "<span style=\"font-size: 10px;\"> ";
	    	
		$Query = " 	SELECT COUNT(t_Comment) AS COUNTER FROM Comment ";

 		if( $ByUser != "" ) {
			$Query.="JOIN Users on Users.i_UID = Comment.i_UID
				AND Users.vc_Username = \"$ByUser\" ";
		}

		$Query.="	WHERE t_Comment LIKE '%$LookFor%' ";

	    if( $selectedCategory > 0 ) {
	      $Query.= " AND i_CategoryId = $selectedCategory";
	    }


		$Q2 = " 	SELECT Comment.i_CommentId AS LABELLER FROM Comment ";
		if( $ByUser != "" ) {
			$Q2 .= "JOIN Users on Users.i_UID = Comment.i_UID
							AND Users.vc_Username = \"$ByUser\" ";
		}
		$Q2 .= "	WHERE t_Comment LIKE '%$LookFor%' ";

    if( $selectedCategory > 0 ) {
      $Q2.= " AND i_CategoryId = $selectedCategory";
    }
		$Q2.= "	ORDER BY Comment.i_CommentId ASC";

    $Query = $Q2;
		$UInfId = mysqli_query ($link, $Query);
		$UInfRes = mysqli_fetch_object($UInfId);

		$strCommentRange = "Current Comment Table";

		if(!Empty($UInfRes->COUNTER)){ echo "<HR>$UInfRes->COUNTER Results in $strCommentRange <BR>"; $Total += $UInfRes->COUNTER;}
		else echo "<HR>0 Results in $strCommentRange<BR>";
		$UInfId2 = mysqli_query ($link, $Q2);
		while($UInfRes = mysqli_fetch_object($UInfId2)){
			echo "<A HREF=\"../main.php?ViewPost=$UInfRes->LABELLER\">p$UInfRes->LABELLER</A> | ";
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
