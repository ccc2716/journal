<?php
/****************************************************************
//  File: NyPersonel.php					*
//								*
//  This file is a part of the "journal system for HJV"		*
//								*
//  Copyright Sten Carlsen 2006, 2007				*
//								*
    Journal-systemet is free software: you can redistribute it
    and/or modify it under the terms of the GNU General Public
    License as published by the Free Software Foundation, either
    version 3 of the License, or (at your option) any later
    version.

    Journal-systemet is distributed in the hope that it will be
    useful, but WITHOUT ANY WARRANTY; without even the implied
    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
    PURPOSE. See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public
    License along with Journal-systemet. If not, see
    <http://www.gnu.org/licenses/>.
//								*
//  This file holds the function used				*
//  to draw the "Ny Personel" page in the system		*
//	$Id$							*
//								*
//**************************************************************/



function NyPersonel()
{

top();
?>

	<div class="midt">

<FORM action="main.php" method="get" onsubmit="mySetSize()">
<INPUT Type=HIDDEN NAME="M_action" VALUE="GemNyPersonel">
<INPUT Type=HIDDEN NAME="M_height" VALUE="720">

   <P>
<FONT class=felt>Funktion:</FONT>
<SELECT name="funktion">
<OPTION selected>STHJ</OPTION>
<OPTION>EBM</OPTION>
<OPTION>OBM</OPTION>
<OPTION>VO</OPTION>
<OPTION>SGO</OPTION>
<OPTION>SIG</OPTION>
<OPTION>EO</OPTION>
<OPTION>OO</OPTION>
<OPTION>CH</OPTION>
<OPTION>G&aelig;st</OPTION>
   </SELECT>
&nbsp;&nbsp;
<FONT class=felt>Grad:</FONT>
<SELECT name="grad">
<OPTION selected>MG</OPTION>
<OPTION>KP</OPTION>
<OPTION>SG</OPTION>
<OPTION>OS</OPTION>
<OPTION>LT</OPTION>
<OPTION>PL</OPTION>
<OPTION>KN</OPTION>
<OPTION>MJ</OPTION>
   </SELECT>
<br><br>
<FONT class=felt>Initialer:</FONT>
<INPUT class="hilight" Type="text" NAME="initialer" size="6" Value="">
&nbsp;&nbsp;
<FONT class=felt>MA-nummer:</FONT>
<INPUT class="hilight" Type="text" NAME="MA" size="6">

<br><br><FONT class=felt>Enhed:</FONT><br>
<INPUT class="hilight" Type="text" NAME="enhed" size="64" Value="-Hvilken enhed tilh&oslash;rer du?-">
<br><br>
<FONT class=felt>Navn:</FONT><br>
<INPUT class="hilight" Type="text" NAME="navn" size="64" Value="-Indtast dit navn-">
<br><br>
<FONT class=felt>email:</FONT><br>
<INPUT class="hilight" Type="text" NAME="email" size="64" Value="">
<br><br>
<FONT class=felt>Telefon:</FONT><br>
<INPUT class="hilight" Type="text" NAME="phone" size="64" Value="">
<br><br>
<FONT class=felt>Password:</FONT><br>
<INPUT class="hilight" Type="text" NAME="pass" size="16" Value="">
<br><br>


<?php
// Below is only allowed for admins.

if(($_SESSION["userObj"]->userRightsCheck(RIGHTS_Admin)) || ($_SESSION["userObj"]->userRightsCheck(RIGHTS_System)))
{
?>


<FONT class=felt>Tillad personel&aelig;ndringer:</FONT>
<input type="checkbox" name="RightPersonel">


<FONT class=felt>Tillad enheds&aelig;ndringer:</FONT>
<input type="checkbox" name="RightEnheder">


<FONT class=felt>Tillad write access:</FONT>
<input type="checkbox" name="RightAccess">
<br><br>

<FONT class=felt>Administrator:</FONT>
<input type="checkbox" name="RightAdmin"><br><br>
<?php
}
?>

<INPUT type="submit" value="Opret personel">
   </P>
</FORM>

		</div>
<?php
bottom();
}

/****************************************************************
 * 								*
 * 	function GemNyPersonel()				*
 * Opretter en ny personel med data sendt fra NyPersonel()	*
 * 								*
 * This function is looking for the following inputs:		*
 * $_REQUEST["pass"], $_SESSION["User"], $_SESSION["DbSelected"], 	*
 * $_REQUEST["RightAdmin"], $_REQUEST["RightPersonel"], 	*
 * $_REQUEST["RightEnheder"], $_REQUEST["RightAccess"], 	*
 * $_REQUEST["funktion"], $_REQUEST["grad"],			*
 * $_REQUEST["initialer"], $_REQUEST["navn"], $_REQUEST["MA"],	*
 * $_REQUEST["enhed"], $_REQUEST["email"]			*
 * *************************************************************/


function GemNyPersonel()
{
require_once( 'Version.php' );

// Saml data sammen i variable.

// Konstruer alle nødvendige queries og fyr dem af.

$pwd= $_REQUEST["pass"];


	if (!$_SESSION["userObj"]->userIsLegal()) {
		/* Not Legal user; log him out, he must not make any changes. */
		$host  = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'main.php?M_action=LogUd';
		header("Location: http://$host$uri/$extra");
		print_footer(false);
		exit;	
	}


if(ValidatePass($pwd))
{	// Password ok
	$ValPass= true;
}
else
{	// Password NOT ok, show error page
	$ValPass= false;
	$extra = 'main.php?M_action=Error&next=NyPersonel&error=BadPass';
}

if ($_SESSION["userObj"]->userMayWritePersonel() && $ValPass)
{
	$db= $_SESSION["DbSelected"];

$MD5pass= CryptPass($pwd, $db);

// insert new rights
if(($_SESSION["userObj"]->userRightsCheck(RIGHTS_Admin)) || ($_SESSION["userObj"]->userRightsCheck(RIGHTS_System)))
{	// We are administrator and can grant every right.
$admin= 0;
if($_REQUEST["RightAdmin"] == "on")
	{
		$admin|= RIGHTS_Admin;
	}

if($_REQUEST["RightPersonel"] == "on")
	{
		$admin|= RIGHTS_Personel;
	}

if($_REQUEST["RightEnheder"] == "on")
	{
		$admin|= RIGHTS_Enheder;
	}

if($_REQUEST["RightAccess"] == "on")
	{
		$admin|= RIGHTS_ReadWrite;
	}
}
else
{	// We are NOT administrator and have no right to grant any right;
	// default right is the right to write to the journal pending other factors.
	$admin= RIGHTS_ReadWrite;
}

$main_query= "INSERT INTO personel(id, funktion, grad, initialer, navn, MA, enhed, email, phone, direction, password, admin) ".
"VALUES (NULL, '".(($_REQUEST["funktion"]))."', '"
	.mysql_real_escape_string(htmlentities($_REQUEST["grad"]))."', '"
	.mysql_real_escape_string(htmlentities($_REQUEST["initialer"]))."', '"
	.mysql_real_escape_string(htmlentities($_REQUEST["navn"]))."',  '"
	.mysql_real_escape_string(htmlentities($_REQUEST["MA"]))."', '"
	.mysql_real_escape_string(htmlentities($_REQUEST["enhed"]))."', '"
	.mysql_real_escape_string(htmlentities($_REQUEST["email"]))."', '"
	.mysql_real_escape_string(htmlentities($_REQUEST["phone"]))."', '"
	.mysql_real_escape_string(htmlentities($DirArray['Direction']))."', '"
	.$MD5pass."', '"
	.$admin."')";

// Performing SQL query
$result = DebugQuery($main_query);

$extra = 'main.php?M_action=Admin';
}

/* Redirect to main page so reload will not insert another copy of signal. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;
}
?>
