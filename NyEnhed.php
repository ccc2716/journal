<?php
/****************************************************************
//  File: NyEnhed.php						*
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
//  to draw the "Ny Enhed" page in the system			*
//	$Id$							*
//								*
//**************************************************************/



function NyEnhed()
{
	
	top();
?>

	<div class="midt">

<FORM action="main.php" method="get" onsubmit="mySetSize()">
<INPUT Type=HIDDEN NAME="M_action" VALUE="GemNyEnhed">
<INPUT Type=HIDDEN NAME="M_height" VALUE="720">
   <P>
<br>
<FONT class=felt>Navn:</FONT><br>
<INPUT class="hilight" Type="text" NAME="navn" size="16" Value="">
<br><br>
<FONT class=felt>Kaldetal:</FONT><br>
<INPUT class="hilight" Type="text" NAME="kaldetal" size="6" Value="">
<br><br>
<FONT class=felt>Telefonnummer:</FONT><br>
<INPUT class="hilight" Type="text" NAME="tlf" size="32" Value="">
<br><br>
<FONT class=felt>Email:</FONT><br>
<INPUT class="hilight" Type="text" NAME="email" size="64" Value="">
<br><br>

<INPUT type="submit" value="Opret enhed">
   </P>
</FORM>

</div>

<?php
bottom();
?>
<?php
}
/****************************************************************
*								*
*  This function is used to save				*
*  the data for a new "enhed" in the system			*
*								*
*  This function is looking for the following inputs:		*
*  $_SESSION["User"], $_REQUEST["navn"],			*
*  $_REQUEST["kaldetal"], $_REQUEST["tlf"], $_REQUEST["email"]	*
//**************************************************************/



function GemNyEnhed()
{
// Saml data sammen i variable.

// Konstruer alle nødvendige queries og fyr dem af.


	if (!$_SESSION["userObj"]->userIsLegal()) {
		/* Not Legal user; log him out, he must not make any changes. */
		$host  = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'main.php?M_action=LogUd';
		header("Location: http://$host$uri/$extra");
		print_footer(false);
		exit;	
	}

$main_query= "INSERT INTO enheder(id, navn, kaldetal, tlf, email) ".
"VALUES (NULL, '".mysql_real_escape_string(htmlentities($_REQUEST["navn"]))."', '"
	.mysql_real_escape_string(htmlentities($_REQUEST["kaldetal"]))."', '"
	.mysql_real_escape_string(htmlentities($_REQUEST["tlf"]))."', '"
	.mysql_real_escape_string(htmlentities($_REQUEST["email"]))."')";


// Performing SQL query
$result = DebugQuery($main_query);

/* Redirect to main page so reload will not insert another copy of signal. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'main.php?M_action=Admin';
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;
}
?>
