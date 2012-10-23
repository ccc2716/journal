<?php
/****************************************************************
//  File: RetEnhed.php						*
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
//  to draw the "Ret Enhed" page in the system			*
*								*
*  This function is looking for the following input:		*
*  $_REQUEST["jour_id"]						*
//**************************************************************/


function RetEnhed()
{
	
	top();
?>

	<div class="midt">
<?php
// Lav listen med brug af MySql udtr¾k.


// Performing SQL query
$query = 'SELECT navn, kaldetal, tlf, email FROM enheder WHERE enheder.id='.$_REQUEST["jour_id"];
// echo $query;
$result = DebugQuery($query);
$line = mysql_fetch_row($result);
printf("<FORM action=\"main.php\" method=\"get\" onsubmit=\"mySetSize()\">\n");
printf("<INPUT Type=HIDDEN NAME=\"M_action\" VALUE=\"GemRetEnhed\">\n");
printf("<INPUT id=\"M_height\" Type=HIDDEN NAME=\"M_height\" VALUE=\"720\">");
printf("<INPUT Type=HIDDEN NAME=\"jour_id\" VALUE=\"%s\">\n", $_REQUEST["jour_id"]);
?>

<br>
<FONT class=felt>Navn:</FONT><br>
<?php
printf("<INPUT class=\"hilight\" Type=\"text\" NAME=\"navn\" size=\"16\" Value=\"%s\">", stripcslashes($line[0]));
?>
<br><br>
<FONT class=felt>Kaldetal:</FONT><br>
<?php
printf("<INPUT class=\"hilight\" Type=\"text\" NAME=\"kaldetal\" size=\"6\" Value=\"%s\">", stripcslashes($line[1]));
?>
<br><br>
<FONT class=felt>Telefonnummer:</FONT><br>
<?php
printf("<INPUT class=\"hilight\" Type=\"text\" NAME=\"tlf\" size=\"32\" Value=\"%s\">", stripcslashes($line[2]));
?>
<br><br>
<FONT class=felt>Email:</FONT><br>
<?php
printf("<INPUT class=\"hilight\" Type=\"text\" NAME=\"email\" size=\"64\" Value=\"%s\">", stripcslashes($line[3]));
?>
<br><br>

<INPUT type="submit" value="Ret enhed">
</FORM>

		</div>
<?php
bottom();

}

/****************************************************************
 * 								*
 * function GemRetEnhed()					*
 * 								*
 * This function is looking for the following inputs:		*
 * $_SESSION["User"], $_REQUEST["navn"], $_REQUEST["kaldetal"],	*
 * $_REQUEST["tlf"], $_REQUEST["email"], $_REQUEST["jour_id"]	*
 ***************************************************************/


function GemRetEnhed()
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



$main_query= "UPDATE enheder ".
	"SET navn= '".mysql_real_escape_string(htmlentities($_REQUEST["navn"]))
	."', kaldetal= '".mysql_real_escape_string(htmlentities($_REQUEST["kaldetal"]))
	."', tlf= '".mysql_real_escape_string(htmlentities($_REQUEST["tlf"]))
	."', email= '".mysql_real_escape_string(htmlentities($_REQUEST["email"]))
	."' WHERE id='".mysql_real_escape_string(htmlentities($_REQUEST["jour_id"]))."'";

if ($_SESSION["userObj"]->userMayWriteEnhed())
{
// Performing SQL query
$result = DebugQuery($main_query);
}

/* Redirect to main page so reload will not insert another copy of signal. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'main.php?M_action=VisEnhed';
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;
}
?>
