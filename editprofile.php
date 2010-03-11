<?php
include( "session.php" );
include("include.php");

// establish connection to MySQL database or output error message.
$link = mysql_connect ($dbHost, $dbUser, $dbPassword);
if (!mysql_select_db($dbName, $link)) echo mysql_errno().": ".mysql_error()."<BR>";

$properties = array(
	'hdnUserId'
	, 'btnSubmit'
	, 'txtPassword'
	, 'txtVerifyPassword'
	, 'txtUsername'
	, 'txtUserNumber'
	, 'txtEmail'
	, 'txtURL'
	, 'txtGMTOffset'
	, 'txtDateJoined'
	, 'txtDateLastVisit'
	, 'chkPublicEmail'
);

foreach( $properties as $property ) {
	$$property = array_key_exists( $property, $_REQUEST ) ? $_REQUEST[ $property ] : '';
}

$sessionUserId = $_SESSION[ 'sessionUserId' ];
if( Empty( $sessionUserId )) $sessionUserId = -1;
$hdnUserId = $sessionUserId;

if( $chkPublicEmail == "on" ) {
	$sPublicEmail = " checked ";
	$iPublicEmail = 1;
} else {
	$sPublicEmail = "";
	$iPublicEmail = 0;		
}
	
if( Empty( $hdnUserId ) || ( $hdnUserId == -1  && Empty( $btnSubmit )) ) {
	// New User Entering Profile Data, load values with empty info.
	$hdnUserId = -1;
	$txtUsername = "";
	$txtPassword = "";
	$txtVerifyPassword = "";
	$txtEmail = "";
	$txtURL = "http://";
	$txtDateJoined = "";
	$txtDateLastVisit = "";
	$txtUserNumber = "";
	$txtGMTOffset = "0";
	$sPublicEmail = "";
} else if ($hdnUserId == -1 && !Empty( $btnSubmit ) ) {
	// New User Saving Profile Data.
	if( $txtPassword != $txtVerifyPassword ) {
		echo "Passwords do not match, please try again.<br />";
	} else {
		// Check for an existing user with that username.
		$ProfileQuery = "SELECT i_UID FROM Users ";
		$ProfileQuery .= "WHERE vc_Username = \"$txtUsername\"";
			
		// Check for an existing user with that usernumber.
		$UserNumberQuery = "SELECT i_UID FROM Users ";
		$UserNumberQuery .= "WHERE vc_UserId = \"$txtUserNumber\"";

		$UpdateUserQueryId = mysql_query ($ProfileQuery, $link);
		$UserProfile = mysql_fetch_object($UpdateUserQueryId);

		$UserNumberQueryId = mysql_query ($UserNumberQuery, $link);
		$UserNumber = mysql_fetch_object($UserNumberQueryId);

		if( $UserProfile && $UserProfile->i_UID > 0 ) {
			echo ("Sorry, that username already exists.  Please pick another.<BR>");
		} else {
			if( $UserNumber && $UserNumber->i_UID > 0 ) {
				echo ("Sorry, that usernumber already exists.  Please pick another.<BR>");
			} else {
				$ProfileQuery = "
					INSERT INTO Users (
						  vc_UserName
						, vc_Email
						, vc_URL
						, dt_DateJoined
						, dt_LastVisit
						, vc_Password
						, vc_UserId
						, b_PublicEmail
						, vc_GMTOffset
					) VALUES (
						  \"$txtUsername\"
						, \"$txtEmail\"
						, \"$txtURL\"
						, NOW()
						, NOW()
						, md5(\"$txtPassword\")
						, \"$txtUserNumber\"
						, $iPublicEmail
						, \"$txtGMTOffset\"
					)";
				$UpdateUserQueryId = mysql_query ($ProfileQuery, $link);

				if( $UpdateUserQueryId ) {
					echo "Saved!";
				} else {
					echo "Error:<br>".$ProfileQuery;
					echo "<br>".mysql_errno().": ".mysql_error()."<BR>";
				}

				$ProfileQuery = "SELECT MAX(i_UID) i_UID FROM Users";
				$UpdateUserQueryId = mysql_query ($ProfileQuery, $link);
				if( $UpdateUserQueryId ) {
					$UserProfile = mysql_fetch_object($UpdateUserQueryId);
					$hdnUserId = $UserProfile->i_UID;
				} else {
					echo "User update/creation got fucked.  Bitch to Rob.";
				}
			}
		}
	}
} else if ($hdnUserId > -1 && !Empty( $btnSubmit ) ) {
	// Old User Updating Profile Data
	if( $txtPassword != $txtVerifyPassword ) {
		echo "Passwords Do Not Match!<BR>";
	} else if( '' === $txtPassword || '' === $txtVerifyPassword ) {
		echo 'You must enter your password to update your profile. Sorry.';
	} else {
		$ProfileQuery = "UPDATE Users  
						set vc_Email = \"$txtEmail\", 
						vc_Password =  md5(\"$txtPassword\"),
						dt_LastVisit = NOW(),
						vc_UserId = \"$txtUserNumber\",
						b_PublicEmail = $iPublicEmail,
						vc_GMTOffset = \"$txtGMTOffset\"
						WHERE i_UID = $hdnUserId";

		$UpdateUserQueryId = mysql_query ($ProfileQuery, $link);

		// returning user editing profile
		$PreferencesQuery = "SELECT i_PreferenceId, vc_PreferenceName";
		$PreferencesQuery .= " FROM Preferences ORDER BY i_PreferenceId ASC";
		$PreferencesResultsId = mysql_query ($PreferencesQuery, $link);
	}	
} else {
	// returning user editing profile
	$ProfileQuery = "
		SELECT vc_UserName
			   , vc_Password
			   , vc_Email
			   , vc_URL
			   , dt_DateJoined
			   , dt_LastVisit
			   , vc_UserId
			   , i_ShareStyles
			   , vc_GMTOffset
		 FROM Users 
		WHERE i_UID = {$hdnUserId}";

	$ProfileResultsIdId = mysql_query ($ProfileQuery, $link);
	$UserProfile = mysql_fetch_object($ProfileResultsIdId);

	$txtUsername = $UserProfile->vc_UserName;
	$txtPassword = $UserProfile->vc_Password;
	$txtVerifyPassword = $UserProfile->vc_Password;
	$txtEmail = $UserProfile->vc_Email;
	$txtURL = $UserProfile->vc_URL;
	$txtDateJoined = $UserProfile->dt_DateJoined;
	$txtDateLastVisit = $UserProfile->dt_LastVisit;
	$txtUserNumber = $UserProfile->vc_UserId;
	$iShareStyles = $UserProfile->i_ShareStyles;
	$txtGMTOffset = $UserProfile->vc_GMTOffset;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?php echo $siteTitle ?> - Edit Your Profile</TITLE>
</HEAD>
<BODY>
<a href="index.php">return to index</A>
<FORM NAME="UserProfileForm" ACTION="editprofile.php" METHOD="post">
<TABLE>
	<tr>
		<TD>
			UserName
		</td>
		<td>
			<input type="hidden" name="hdnUserId" value="<?PHP echo $hdnUserId;?>">
			<?php

				if( $hdnUserId == -1 )
				{
					echo "<input type=\"text\" name=\"txtUsername\" value=\"";
				}
				echo $txtUsername;
				if( $hdnUserId == -1 )
				{
					echo "\" maxlength=\"100\">";
				}else
				{
					echo "<input type=\"hidden\" name=\"txtUsername\" value=\"$txtUsername\">";
				}
			?>
		</td>
	</tr>
	<tr>
		<td>
			UserNumber
		</td>
		<td>
			<input type="text" name="txtUserNumber" 
value="<?PHP echo $txtUserNumber;?>" maxlength="500">
		</td>
	</tr>

	<tr>
		<td>
			Password
		</td>
		<td>
			<input type="password" name="txtPassword" value="" maxlength="20">
		</td>
	</tr>
	<tr>
		<td>
			Verify Password
		</td>
		<td>
			<input type="password" name="txtVerifyPassword" value="" maxlength="20">
		</td>
	</tr>
	<tr>
		<td>
			E-mail
		</td>
		<td>
			<input type="text" name="txtEmail" value="<?PHP echo $txtEmail;?>" maxlength="255">
		</td>
	</tr>
	<tr>
		<td>
			Show E-Mail on Public Profile Pages?
		</td>
		<td>
			<input type="checkbox" name="chkPublicEmail" <?PHP echo $sPublicEmail;?>" maxlength="255">
		</td>
	</tr>
	<tr>
		<td>
			URL
		</td>
		<td>
			<input type="text" name="txtURL" value="<?PHP echo $txtURL;?>" maxlength="255">
		</td>
	</tr>
	<tr>
		<td>
			GMT Offset
		</td>
		<td>
			<input type="text" name="txtGMTOffset" value="<?PHP echo $txtGMTOffset;?>" maxlength="3">
		</td>
	</tr>
	<tr>
		<td>
			Date Joined
		</td>
		<td>
			<?PHP echo $txtDateJoined;?>
		</td>
	</tr>
	<tr>
		<td>
			Date Last Visit
		</td>
		<td>
			<?PHP echo $txtDateLastVisit;?>
		</td>
	</tr>
</TABLE>
<input type="hidden" name="iRowCount" value="<?PHP echo $iRowCount ?>">
<input type="submit" name="btnSubmit" value="Submit" 
	ONCLICK="QuoteReplace(document.UserProfileForm.txtBiography);">
</FORM>
<a href="index.php">return to index</A>
</BODY>
<?php
	// close connection to MySQL Database
	mysql_close($link);
?>
</HTML>
