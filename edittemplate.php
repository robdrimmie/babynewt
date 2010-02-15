<?php
	include("session.php");
	include("include.php");
	// establish connection to MySQL database or output error message.
	$link = mysql_connect ($dbHost, $dbUser, $dbPassword);
	if (!mysql_select_db($dbName, $link)) echo mysql_errno().": ".mysql_error()."<BR>";

	$sessionUserId = $_SESSION[ 'sessionUserId' ];

	if( Empty( $sessionUserId )) $sessionUserId = -;
	$hdnUserId = $sessionUserId;


	$selTemplate = $_REQUEST[ 'selTemplate' ];
	if( Empty( $selTemplate ) ) {
		// get current template from db
		$CurTemplateSQL = "SELECT i_TemplateId 
							 FROM UserTemplate
							WHERE i_UID = $sessionUserId";

		$CurTemplateSQLId = mysql_query( $CurTemplateSQL );

		$objCurTemplate = mysql_fetch_object( $CurTemplateSQLId );

		$selTemplate = $objCurTemplate->i_TemplateId;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
	<?php
		echo "<title>$siteTitle - Edit Your Template</title>";
	?>
	</head>
	<body>
	<a href="main.php">return to index</a> |
	<a href="TemplateHelp.html">Get your helpfile here!</a> |
	<a href="editstyles.php">jump to stylesheets</a>
<?php
	// Save Templates if button selected.
	$btnSaveTemplate = $_REQUEST[ 'btnSaveTemplate' ];
	$selTemplate = $_REQUEST[ 'selTemplate' ];
	$btnNewTemplate = $_REQUEST[ 'btnNewTemplate' ];
	$btnPickPublic = $_REQUEST[ 'btnPickPublic' ];
	$btnSaveTemplate = $_REQUEST[ 'btnSaveTemplate' ];	
	$btnMakeMine = $_REQUEST[ 'btnMakeMine' ];
	
	$txtHeader = $_REQUEST[ 'txtHeader' ];
	$txtComment = $_REQUEST[ 'txtComment' ];
	$txtFooter = $_REQUEST[ 'txtFooter' ];
	$chkTemplatePublic = $_REQUEST[ 'chkTemplatePublic' ];	
	$iTemplatePublic = $_REQUEST[ 'iTemplatePublic' ];
	$txtTemplateName = $_REQUEST[ 'txtTemplateName' ];
	$hdnTemplateId = $_REQUEST[ 'hdnTemplateId' ];

	if( !Empty( $btnSaveTemplate ) ) {
		$iTemplatePublic = 0;
		if ( $chkTemplatePublic == "on" ) 
			$iTemplatePublic = 1;

	 	if( $hdnTemplateId == -1 ) {
			$InsTemplateQuery = "INSERT INTO Template VALUES (0 , \"$txtHeader\", \"$txtComment\", \"$txtFooter\", $hdnUserId, $iTemplatePublic, \"$txtTemplateName\")";
			$InsTemplateQueryId = mysql_query( $InsTemplateQuery );
		}
		else{
			$UpdTemplateQuery = "UPDATE Template SET  i_UID=$hdnUserId, b_Public=$iTemplatePublic, vc_TemplateName = \"$txtTemplateName\", t_TemplateHdr=\"$txtHeader\", t_TemplateCmt=\"$txtComment\", t_TemplateFtr=\"$txtFooter \" WHERE i_TemplateID=$hdnTemplateId";
			$UpdTemplateQueryId = mysql_query( $UpdTemplateQuery );
		}
	}

	// Save the template preference change if required
	if (!Empty($_REQUEST[ 'btnMakeMine' ] )){
		$TempQuery = " DELETE FROM UserTemplate WHERE i_UID = $hdnUserId";
		$TempQueryId = mysql_query( $TempQuery );

		$TempQuery = "INSERT INTO UserTemplate VALUES ($hdnUserId, $hdnTemplateId)";
		$TempQueryId = mysql_query( $TempQuery );

		echo $TempQuery;
	}

	//	get a drop-down of all the users templates.
	$TemplateQuery = "SELECT i_TemplateID
						   , vc_TemplateName 
						FROM Template 
					   WHERE i_UID = $hdnUserId
					ORDER BY i_TemplateID";
	$TemplateQueryId = mysql_query ($TemplateQuery, $link);

	echo "<form name=\"frmSelection\" action=\"EditTemplate.php\" method=\"post\">\n";
		echo "Your Templates: <select name=\"selTemplate\">\n";
		if( Empty($selTemplate) && !Empty($hdnTemplateId)) $selTemplate = $hdnTemplateId;
			while( $Templates = mysql_fetch_object($TemplateQueryId) )
			{
				echo "<option value=\"".$Templates->i_TemplateID."\"";
				if( $Templates->i_TemplateID == $selTemplate ) echo " SELECTED";
				echo ">";
				echo "[$Templates->i_TemplateID] $Templates->vc_TemplateName</option>\n";
			}
		echo "</select>\n";
		
		echo "<input type=\"submit\" name=\"btnPickUserTemplate\" value=\"View/Edit Your Template\">\n";
		echo "<BR>\n";
	
	echo "</form>\n";

	//	get a drop-down of all the public templates.
	$TemplateQuery = "SELECT i_TemplateID, vc_TemplateName 
										FROM Template 
										WHERE b_Public = 1
										ORDER BY i_TemplateID";
	$TemplateQueryId = mysql_query ($TemplateQuery, $link);

	echo "<form name=\"frmSelection\" action=\"EditTemplate.php\" method=\"post\">\n";
		echo "Public Templates: <select name=\"selTemplate\">\n";
			while( $Templates = mysql_fetch_object($TemplateQueryId) ) {
				echo "<option value=\"$Templates->i_TemplateID\"";
				if( $_REQUEST[ 'selTemplate' ] == $Templates->i_TemplateID ) {
					echo " SELECTED ";
				}				
				echo ">[$Templates->i_TemplateID] $Templates->vc_TemplateName</option>\n";	
			}
		echo "</select>\n";
		
		echo "<input type=\"submit\" name=\"btnPickPublic\" value=\"View Public Template\">\n";
		echo "<BR>\n";
	
	echo "</form>\n";


		//	if an editable style is already selected (default to user's current style, or if the button for a new stylesheet is selected)
		if (  Empty($btnMakeMine) 
					&& (($selTemplate > -1) || !Empty( $btnNewTemplate )) 
					&& Empty($btnPickPublic) || !Empty($btnSaveTemplate) 
		) {
			// When "save template" is hit, the selected template value 
			// isn't loaded, but the hidden form value is.
			// therefore make the selected template value the hidden one.
			if( ( Empty($selTemplate) || $selTemplate == -1 ) 
					&& $hdnTemplateId > 0
			) {
					$selTemplate = $hdnTemplateId;
			}
//		Create a text area
			echo "<form name=\"frmTemplate\" action=\"EditTemplate.php\" method=\"post\">";
			echo "<input type=\"hidden\" name=\"hdnTemplateId\" value=\"";
			if( !Empty( $btnNewTemplate ) ) {
				// if the button for a new stylesheet has been selected		
				// set the hidden stylesheet id to -1 to indicate "new"
				echo "-1\">";
				// fill the textarea with as many stylesheet classes as possible
				echo "Name: <input type=\"input\" name=\"txtTemplateName\" value = \"I aint got no name.\">";
				echo "<br />Public: <input type=\"checkbox\" name=\"chkTemplatePublic\">";
				echo "<br />Header Template<br><textarea name=\"txtHeader\" cols=\"100\" rows=\"17\"></textarea><br>";
				echo "Comment Template<br><textarea name=\"txtComment\" cols=\"100\" rows=\"17\"></textarea><br>";
				echo "Footer Template<br><textarea name=\"txtFooter\" cols=\"100\" rows=\"17\"></textarea><br>";
			} else {
				echo "$selTemplate\">";
				$TemplateQuery = "SELECT vc_TemplateName, t_TemplateHdr, t_TemplateCmt, t_TemplateFtr, b_Public FROM Template WHERE i_TemplateID = $selTemplate";
				$TemplateQueryId = mysql_query ($TemplateQuery, $link);
				$Templates = mysql_fetch_object($TemplateQueryId);
				echo "<H3>$Templates->vc_TemplateName</H3> Public Template";
				// fill the textarea with selected style
				// load the db values
				echo "Name: <input type=\"input\" name=\"txtTemplateName\" value = \"$Templates->vc_TemplateName\">";
				echo "<br />Public: <input type=\"checkbox\" name=\"chkTemplatePublic\"";
				if( $Templates->b_Public == 1 ) echo " CHECKED";
				echo ">";
				echo "<br /><input type=\"submit\" name=\"btnMakeMine\" value=\"Make This my Template\"><BR>";
				echo "Header Template<br><textarea name=\"txtHeader\" cols=\"100\" rows=\"17\">$Templates->t_TemplateHdr</textarea><br>";
				echo "Comment Template<br><textarea name=\"txtComment\" cols=\"100\" rows=\"17\">$Templates->t_TemplateCmt</textarea><br>";
				echo "Footer Template<br><textarea name=\"txtFooter\" cols=\"100\" rows=\"17\">$Templates->t_TemplateFtr</textarea><br>";
			}
			echo "<input type=\"submit\" name=\"btnSaveTemplate\" value=\"Save Template\">";
			echo "</form>";
		} else if ( !Empty( $_REQUEST[ 'btnPickPublic' ] ) )
		{
			//	- if no specific style is selected,
			echo "<form name=\"frmTemplate\" action=\"EditTemplate.php\" method=\"post\">";
			echo "<input type=\"hidden\" name=\"hdnTemplateId\" value=\"$selTemplate\">";
			$TemplateQuery = "SELECT vc_TemplateName, t_TemplateHdr, t_TemplateCmt, t_TemplateFtr FROM Template WHERE i_TemplateID = $selTemplate";
			$TemplateQueryId = mysql_query ($TemplateQuery, $link);
			$Templates = mysql_fetch_object($TemplateQueryId);
			echo "<FONT SIZE=\"xx-large\"><B>$Templates->vc_TemplateName</B></FONT> <input type=\"submit\" name=\"btnMakeMine\" value=\"Make This my Template\"><BR><BR>";
			// fill the textarea with selected style
			echo "Header Template<br><textarea name=\"txtHeader\" cols=\"100\" rows=\"17\">$Templates->t_TemplateHdr</textarea><br>";
			echo "Comment Template<br><textarea name=\"txtComment\" cols=\"100\" rows=\"17\">$Templates->t_TemplateCmt</textarea><br>";
			echo "Footer Template<br><textarea name=\"txtFooter\" cols=\"100\" rows=\"17\">$Templates->t_TemplateFtr</textarea><br>";
		} else if( !Empty( $_REQUEST[ 'btnMakeMine' ] ) ) 
		{
			echo "Your selected template has been updated.";
		} 

		echo "<form name=\"frmNewStylesheet\" action=\"EditTemplate.php\" method=\"post\">";
		echo "<input type=\"submit\" name=\"btnNewTemplate\" value=\"New Template\">";
		echo "</form>";

	echo "</body>";
	echo "</html">

	// close connection to MySQL Database

	mysql_close($link);
?>
