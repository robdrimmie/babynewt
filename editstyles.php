<?php
	require_once "session.php";
	require_once "include.php";
	require_once "Style.php";
	
	$sessionUserId = $_SESSION[ 'sessionUserId' ];
	if( Empty( $sessionUserId )) {
		die( "you must be logged in" );
	}
	
	$hdnUserId = $sessionUserId;

/*
Page flow:
- get a drop-down of all the user's styles and a drop-down of other peoples' styles.
- if a style is already selected (default to user's current style)
 - fill the text area with selected style
 - "make this my stylesheet" button.
 !!FUNCTIONALIZE THIS!!
 - stylesheet name textbox
 - public style checkbox
 - "save stylesheet" button
 - "restart stylesheet" button -> reload the page with !Empty(btnNewStylesheet) == true.
 - more fields?
 - preview area, with lorum ipsum for all the categories.

- else if creating a new style is selected
 - load textarea with as many classnames as possible.
 - stylesheet name textbox
 - public style checkbox
 - "save stylesheet" button
 - "restart stylesheet" button -> reload the page with !Empty(btnNewStylesheet) == true.
 - more fields?
 - preview area, with lorum ipsum for all the categories.
- else no specific style is selected, 
 - present user with button "Create a new stylesheet"
- end if
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>1142.org - Edit Your Stylesheet</TITLE>
<META NAME="Author" CONTENT="Robert M. Drimmie">
</HEAD>
<BODY>

<a href="index.php">return to index</A> | <a href="EditTemplate.php">jump to Templates</a> | <a href="uploadStyle.php">upload a stylesheet to 1142 </a><br>

<a href="categories.php">Category list</A>: Preview your selected stylesheet and get category stylesheet names<br>
<?php
	//	if an editable style hasn't been selected 
	$selUserStyle = null;
	if( array_key_exists( 'selUserStyle', $_REQUEST ) ) {
		$selUserStyle = $_REQUEST[ 'selUserStyle' ];
	}
	if( Empty($selUserStyle) )
	{
		// initialize editable select area to default value of -1
		// get current style from database
		$CurStyleSQL= "SELECT i_StyleSheetId FROM UserStyleSheet
				WHERE i_UID = $sessionUserId";
		$CurStyleSQLId = mysql_query( $CurStyleSQL );

		$objUserStyle = mysql_fetch_object( $CurStyleSQLId );
	 	if( $objUserStyle ) {	
			$selUserStyle = $objUserStyle->i_StyleSheetId;
		}
	}
	//	if a viewable style hasn't been selected 
	if( Empty( $_REQUEST[ 'selPublicStyle' ] ) )
	{
		// initialize public select area to default value of -1
		$selPublicStyle = -1;
	} else {
		$selPublicStyle = $_REQUEST[ 'selPublicStyle' ];		
	}

	$iStylePublic = 0;

	$chkStylePublic = false;
 	if( array_key_exists( 'chkStylePublic', $_REQUEST ) ) {
		$chkStylePublic = $_REQUEST[ 'chkStylePublic' ];
	}
	if ($chkStylePublic == "on") {
		$iStylePublic = 1;
	}

	// if the stylesheet is saved
	$btnSaveStyle = null;
	if( array_key_exists( 'btnSaveStyle', $_REQUEST ) ) {
		$btnSaveStyle = $_REQUEST[ 'btnSaveStyle' ];
	}

	$btnMakeStyle = null;
	if( array_key_exists( 'btnMakeStyle', $_REQUEST ) ) {
		$btnMakeStyle = $_REQUEST[ 'btnMakeStyle' ];
	}

 	$btnMakePublicStyle= null;
	if( array_key_exists( 'btnMakePublicStyle', $_REQUEST ) ) {
		$btnMakePublicStyle  = $_REQUEST[ 'btnMakePublicStyle' ];
	}

 	$hdnStyleId= null;
	if( array_key_exists( 'hdnStyleId', $_REQUEST ) ) {
		$hdnStyleId = $_REQUEST[ 'hdnStyleId' ];
	}

	$txtStyleName = null;
	if( array_key_exists( 'txtStyleName', $_REQUEST ) ) {
		$txtStyleName = $_REQUEST[ 'txtStyleName' ];
	}

	$selUserStyle = null;
	if( array_key_exists( 'selUserStyle', $_REQUEST ) ) {		
		$selUserStyle = $_REQUEST[ 'selUserStyle' ];
	}

	$txtStyleSheet = null;
	if( array_key_exists( 'txtStyleSheet', $_REQUEST ) ) {		
		$txtStyleSheet = $_REQUEST[ 'txtStyleSheet' ];
	}

	$hdnPublicStyleId = null;
	if( array_key_exists( 'hdnPublicStyleId', $_REQUEST ) ) {		
		$hdnPublicStyleId = $_REQUEST[ 'hdnPublicStyleId' ];
	}

	if( !Empty($btnSaveStyle) || !Empty($btnMakeStyle))
	{
		$style = new Style($db);
		if( $hdnStyleId == -1 )
		{
			$UpdateStyleQuery = "INSERT INTO StyleSheet (i_UID, i_StyleSheetTypeId, i_Public, vc_Name)";
			$UpdateStyleQuery .= " VALUES ($hdnUserId, 1, $iStylePublic, \"$txtStyleName\")";

			$UpdateStyleQueryId = mysql_query( $UpdateStyleQuery );

			$GetStyleIdQuery = "SELECT MAX(i_StyleSheetId) i_StyleSheetId FROM StyleSheet where i_UID = $hdnUserId";
			$GetStyleIdQueryId = mysql_query( $GetStyleIdQuery );

			$GetStyleIdResults = mysql_fetch_object( $GetStyleIdQueryId );
			$selUserStyle = $GetStyleIdResults->i_StyleSheetId;

			$UpdateStyleQueryId = $style->create( $selUserStyle, $txtStyleSheet);
		} else {
			$selUserStyle = $hdnStyleId;

			$UpdateStyleQuery = "UPDATE StyleSheet ";
			$UpdateStyleQuery .= " set i_UID = $hdnUserId,";
			$UpdateStyleQuery .= " i_StyleSheetTypeId = 1,";
			$UpdateStyleQuery .= " i_Public = $iStylePublic,";
			$UpdateStyleQuery .= " vc_Name = \"$txtStyleName\"";
			$UpdateStyleQuery .= " WHERE i_StyleSheetId = $hdnStyleId";

			$UpdateStyleQueryId = mysql_query( $UpdateStyleQuery );

			$UpdateStyleQueryId = $style->update( $hdnStyleId, $txtStyleSheet);

			if( !Empty($btnMakeStyle) )
			{
				$UpdateUserStyleQuery = "DELETE FROM UserStyleSheet WHERE i_UID = $hdnUserId";
				$UpdateUserStyleQueryId = mysql_query( $UpdateUserStyleQuery );

				$UpdateUserStyleQuery = "INSERT INTO UserStyleSheet (i_UID, i_StyleSheetId)";
				$UpdateUserStyleQuery .= " VALUES ($hdnUserId, $hdnStyleId)";
				$UpdateUserStyleQueryId = mysql_query( $UpdateUserStyleQuery );
			}
		}
	} else if( !Empty( $btnMakePublicStyle ) )
	{
		$UpdateUserStyleQuery = "DELETE FROM UserStyleSheet WHERE i_UID = $hdnUserId";
		$UpdateUserStyleQueryId = mysql_query( $UpdateUserStyleQuery );

		$UpdateUserStyleQuery = "INSERT INTO UserStyleSheet (i_UID, i_StyleSheetId)";
		$UpdateUserStyleQuery .= " VALUES ($hdnUserId, $hdnPublicStyleId)";
		$UpdateUserStyleQueryId = mysql_query( $UpdateUserStyleQuery );
	}

	// get a drop-down of all the user's styles and a drop-down of other peoples' styles.
	$UserStylesQuery = "SELECT StyleSheet.i_StyleSheetId, vc_Name ";
	$UserStylesQuery .= " FROM StyleSheet";
	$UserStylesQuery .= " WHERE StyleSheet.i_UID = $hdnUserId";
	$UserStylesQuery .= " ORDER BY StyleSheet.i_StyleSheetId";
	
	$PublicStylesQuery = "SELECT StyleSheet.i_StyleSheetId, vc_Name, ";
	$PublicStylesQuery .= " DBStyleSheet.t_StyleSheet";
	$PublicStylesQuery .= " FROM StyleSheet, DBStyleSheet";
	$PublicStylesQuery .= " WHERE StyleSheet.i_Public = 1";
	$PublicStylesQuery .= " AND DBStyleSheet.i_StyleSheetId = StyleSheet.i_StyleSheetId ";
	$PublicStylesQuery .= " ORDER BY StyleSheet.i_StyleSheetId ";
	$UserStylesQueryId = mysql_query ($UserStylesQuery, $link);

	$PublicStylesQueryId = mysql_query ($PublicStylesQuery, $link);

	echo "<form name=\"frmSelection\" action=\"editstyles.php\" method=\"post\">";
	echo "Your Styles: <select name=\"selUserStyle\">";
	echo "<option value=\"-1\"";
	if( $selUserStyle == -1 ) echo " SELECTED";
	echo ">Select A Style</option>";
	while( $UserStyles = mysql_fetch_object($UserStylesQueryId) )
	{
		echo "<option value=\"";
		echo $UserStyles->i_StyleSheetId;
		echo "\"";
		if( $UserStyles->i_StyleSheetId == $selUserStyle ) echo " SELECTED";
		echo ">";
		echo "[$UserStyles->i_StyleSheetId] $UserStyles->vc_Name";
		echo "</option>";	
	}
	echo "</select>";

	echo "<input type=\"submit\" name=\"btnPickUserStyle\" value=\"Edit Your Stylesheet\">";

	echo "<br />";
	echo "Public Styles: <select name=\"selPublicStyle\">";
	echo "<option value=\"-1\"";

	if( $selPublicStyle == -1 ) echo " SELECTED";
	echo ">Select A Style</option>";
	while( $PublicStyles = mysql_fetch_object($PublicStylesQueryId) )
	{
		echo "<option value=\"";
		echo $PublicStyles ->i_StyleSheetId;
		echo "\"";
		if( $PublicStyles ->i_StyleSheetId == $selPublicStyle ) echo " SELECTED";
		echo ">";
		echo "[$PublicStyles->i_StyleSheetId] $PublicStyles->vc_Name";
		echo "</option>";	
	}
	echo "</select>";
	echo "<input type=\"submit\" name=\"btnPickPublicStyle\" value=\"View A Public Stylesheet\">";
	echo "</form>";

	$btnNewStyle = null;
	if( array_key_exists( 'btnNewStyle', $_REQUEST ) ) {
		$btnNewStyle = $_REQUEST[ 'btnNewStyle' ];
	}

	// if an editable style is already selected (default to user's current style, or if the button for a new stylesheet is selected)
	if ( (($selUserStyle > -1) || !Empty( $btnNewStyle )) && (Empty( $btnPickPublicStyle ) ) ) {
		// Create a text area
		echo "<form name=\"frmStyleSheet\" action=\"editstyles.php\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"hdnStyleId\" value=\"";
		if( !Empty( $btnNewStyle ) ) {
			// if the button for a new stylesheet has been selected		
			// set the hidden stylesheet id to -1 to indicate "new"
			echo "-1\">";
			echo "Public: <input type=\"checkbox\" name=\"chkStylePublic\">";
			echo "<br />Name: <input type=\"input\" name=\"txtStyleName\">";
			// fill the textarea with as many stylesheet classes as possible
			echo "<br><textarea name=\"txtStyleSheet\" cols=\"80\" rows=\"20\">";
		} else {
			$UserStylesQuery = "SELECT StyleSheet.i_StyleSheetId, StyleSheet.i_Public, StyleSheet.vc_Name, ";
			$UserStylesQuery .= " DBStyleSheet.t_StyleSheet";
			$UserStylesQuery .= " FROM StyleSheet, DBStyleSheet";
			$UserStylesQuery .= " WHERE StyleSheet.i_StyleSheetId = $selUserStyle";
			$UserStylesQuery .= " AND DBStyleSheet.i_StyleSheetId = StyleSheet.i_StyleSheetId ";

			$UserStylesQueryId = mysql_query ($UserStylesQuery, $link);
			$UserStyles = mysql_fetch_object($UserStylesQueryId);

			// load the selected stylesheet data
			// store the stylesheet of the selected style
			echo "$UserStyles->i_StyleSheetId\">";
			// load the db values

			echo "Public: <input type=\"checkbox\" name=\"chkStylePublic\"";

			if( $UserStyles->i_Public == 1 ) echo " CHECKED";
			echo ">";

			echo "<br />Name: <input type=\"input\" name=\"txtStyleName\" value=\"";
			echo $UserStyles->vc_Name;
			echo "\">";
			// fill the textarea with selected style
			echo "<br><textarea name=\"txtStyleSheet\" cols=\"80\" rows=\"20\">";

			echo $UserStyles->t_StyleSheet;
		}
//		- close the text area
		echo "</textarea><br>";

		echo "<input type=\"submit\" name=\"btnSaveStyle\" value=\"Save Stylesheet\">";
		echo "<input type=\"submit\" name=\"btnMakeStyle\" value=\"Make This My Stylesheet\">";

	} else if( !Empty($_REQUEST[ 'btnPickPublicStyle' ]) ) {
		//		- "make this my stylesheet" button.
		//		!!FUNCTIONALIZE THIS!!
		//		- stylesheet name textbox
		//		- public style checkbox
		//		- "save stylesheet" button
		//		- "restart stylesheet" button -> reload the page with !Empty(btnNewStylesheet) == true.
		//		- more fields?
		//		- preview area, with lorum ipsum for all the categories.
		
		//		- stylesheet name textbox
		//		- public style checkbox
		//		- "save stylesheet" button
		//		- "restart stylesheet" button -> reload the page with !Empty(btnNewStylesheet) == true.
		//		- "make this my stylesheet" button.
		//		- more fields?
		//		- preview area, with lorum ipsum for all the categories.
		
		//	- if no specific style is selected,
		$UserStylesQuery = "SELECT StyleSheet.i_StyleSheetId, StyleSheet.i_Public, StyleSheet.vc_Name, ";
		$UserStylesQuery .= " DBStyleSheet.t_StyleSheet";
		$UserStylesQuery .= " FROM StyleSheet, DBStyleSheet";
		$UserStylesQuery .= " WHERE StyleSheet.i_StyleSheetId = $selPublicStyle";
		$UserStylesQuery .= " AND DBStyleSheet.i_StyleSheetId = StyleSheet.i_StyleSheetId ";

		$UserStylesQueryId = mysql_query ($UserStylesQuery, $link);
		$UserStyles = mysql_fetch_object($UserStylesQueryId);

		echo "<form name=\"frmStyleSheet\" action=\"editstyles.php\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"hdnPublicStyleId\" value=\"";
		echo "$UserStyles->i_StyleSheetId\">";
		echo $UserStyles->vc_Name;

		echo "<input type=\"submit\" name=\"btnMakePublicStyle\" value=\"Make This My Stylesheet\">";
		echo "<BR><PRE>";
		// fill the textarea with selected style
		echo $UserStyles->t_StyleSheet;
		echo "</PRE></form>";
	}

	echo "<form name=\"frmNewStylesheet\" action=\"editstyles.php\" method=\"post\">";
	echo "<input type=\"submit\" name=\"btnNewStyle\" value=\"New Stylesheet\">";
	echo "</form>";

	echo "<br><a href=\"main.php\">return to index</A>";
	echo "</body>";
	echo "</html>";

	// close connection to MySQL Database
	mysql_close($link);
?>
