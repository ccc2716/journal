<?php
/****************************************************************
//  File: RetPersonel.php					*
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
//  to draw the "Ret Personel" page in the system		*
//  This function is looking for the following input:		*
//  $_REQUEST["jour_id"]						*
//**************************************************************/


function RetPersonel()
{

top();
?>

	<div class="midt">
<?php
// Lav listen med brug af MySql udtr¾k.


// Performing SQL query
$query = 'SELECT funktion, '.		// 0
		'grad, '.		// 1
		'initialer, '.		// 2
		'navn, '.		// 3
		'enhed, '.		// 4
		'email, '.		// 5
		'MA, '.			// 6
		'admin, '.		// 7
		'phone'.		// 8
		' FROM personel WHERE personel.id="'.$_REQUEST["jour_id"].'"';
// echo $query;
$result = DebugQuery($query);
$line = mysql_fetch_row($result);

// Now print existing values
printf("<FORM action=\"main.php\" method=\"get\" onsubmit=\"mySetSize()\">\n");
printf("<INPUT Type=HIDDEN NAME=\"M_action\" VALUE=\"GemRetPersonel\">\n");
printf("<INPUT id=\"M_height\" Type=HIDDEN NAME=\"M_height\" VALUE=\"720\">");
printf("<INPUT Type=HIDDEN NAME=\"jour_id\" VALUE=\"%s\">\n", $_REQUEST["jour_id"]);
?>

   <P>
<FONT class=felt>Funktion:</FONT>
<SELECT name="funktion">
<?php
$funk= stripcslashes($line[0]);
printf("<OPTION %s>STHJ</OPTION>\n", ($funk=="STHJ")?"selected":"");
printf("<OPTION %s>EBM</OPTION>\n", ($funk=="EBM")?"selected":"");
printf("<OPTION %s>OBM</OPTION>\n", ($funk=="OBM")?"selected":"");
printf("<OPTION %s>VO</OPTION>\n", ($funk=="VO")?"selected":"");
printf("<OPTION %s>SGO</OPTION>\n", ($funk=="SGO")?"selected":"");
printf("<OPTION %s>SIG</OPTION>\n", ($funk=="SIG")?"selected":"");
printf("<OPTION %s>EO</OPTION>\n", ($funk=="EO")?"selected":"");
printf("<OPTION %s>OO</OPTION>\n", ($funk=="OO")?"selected":"");
printf("<OPTION %s>CH</OPTION>\n", ($funk=="CH")?"selected":"");
printf("<OPTION %s>G&aelig;st</OPTION>\n", ($funk=="G&aelig;st")?"selected":"");
?>
   </SELECT>&nbsp;&nbsp;

<FONT class=felt>Grad:</FONT>
<SELECT name="grad">
<?php
$grad= stripcslashes($line[1]);
printf("<OPTION %s>MG</OPTION>\n", ($grad=="MG")?"selected":"");
printf("<OPTION %s>KP</OPTION>\n", ($grad=="KP")?"selected":"");
printf("<OPTION %s>SG</OPTION>\n", ($grad=="SG")?"selected":"");
printf("<OPTION %s>OS</OPTION>\n", ($grad=="OS")?"selected":"");
printf("<OPTION %s>LT</OPTION>\n", ($grad=="LT")?"selected":"");
printf("<OPTION %s>PL</OPTION>\n", ($grad=="PL")?"selected":"");
printf("<OPTION %s>KN</OPTION>\n", ($grad=="KN")?"selected":"");
printf("<OPTION %s>MJ</OPTION>\n", ($grad=="MJ")?"selected":"");
?>
   </SELECT>
<br><br>
<FONT class=felt>Initialer:</FONT>
<?php
printf("<INPUT class=\"hilight\" Type=\"text\" NAME=\"initialer\" size=\"6\" Value=\"%s\">&nbsp;&nbsp;", stripcslashes($line[2]));
?>

<FONT class=felt>MA-nummer:</FONT>
<?php
printf("<INPUT class=\"hilight\" Type=\"text\" NAME=\"MA\" size=\"12\" Value=\"%s\">&nbsp;&nbsp;", stripcslashes($line[6]));
?><br><br>

<FONT class=felt>Navn:</FONT><br>
<?php
printf("<INPUT class=\"hilight\" Type=\"text\" NAME=\"navn\" size=\"64\" Value=\"%s\">&nbsp;&nbsp;", stripcslashes($line[3]));
?>

<br><br>
<FONT class=felt>Enhed:</FONT><br>
<?php
printf("<INPUT class=\"hilight\" Type=\"text\" NAME=\"enhed\" size=\"64\" Value=\"%s\">", stripcslashes($line[4]));
?>
<br><br>
<FONT class=felt>email:</FONT><br>
<?php
printf("<INPUT class=\"hilight\" Type=\"text\" NAME=\"email\" size=\"64\" Value=\"%s\">", stripcslashes($line[5]));
?>
<br><br>
<FONT class=felt>telefon:</FONT><br>
<?php
printf("<INPUT class=\"hilight\" Type=\"text\" NAME=\"phone\" size=\"64\" Value=\"%s\">", stripcslashes($line[8]));

// Below is only allowed for admins.

if(($_SESSION["userObj"]->userRightsCheck(RIGHTS_Admin)) || ($_SESSION["userObj"]->userRightsCheck(RIGHTS_System)))
{
?>
<br><br>
<FONT class=felt>Nyt Password:</FONT><br>
<INPUT class="hilight" Type="text" NAME="pass" size="16" Value="">
<br><br>

<FONT class=felt>Tillad personel&aelig;ndringer:</FONT>
<?php
$l7= (stripcslashes($line[7])+0);
printf("<input type=\"checkbox\" name=\"RightPersonel\" %s>", ($l7 & RIGHTS_Personel)?"checked":"");
?>


<FONT class=felt>Tillad enheds&aelig;ndringer:</FONT>
<?php
printf("<input type=\"checkbox\" name=\"RightEnheder\" %s>", ($l7 & RIGHTS_Enheder)?"checked":"");
?>


<FONT class=felt>Tillad write access:</FONT>
<?php
printf("<input type=\"checkbox\" name=\"RightAccess\" %s>", ($l7 & RIGHTS_ReadWrite)?"checked":"");
?>
<br><br>

<FONT class=felt>Administrator:</FONT>
<?php
printf("<input type=\"checkbox\" name=\"RightAdmin\" %s><br><br>", ($l7 & RIGHTS_Admin)?"checked":"");

}
?>

<INPUT type="submit" value="Ret personel">
   </P>
</FORM>
<?php
// Free resultset
mysql_free_result($result);

// Closing connection
// mysql_close($link);
?>
		</div>
<?php
bottom();

}

/****************************************************************
 * function GemRetPersonel()					*
 * 								*
 * This function is looking for the following input:		*
 * $_SESSION["User"], $_REQUEST["jour_id"], $_REQUEST["RightAdmin"],	*
 * $_REQUEST["RightPersonel"], $_REQUEST["RightEnheder"],	*
 * $_REQUEST["RightAccess"], $_SESSION["DbSelected"],		*
 * $_REQUEST["pass"], $_REQUEST["funktion"], $_REQUEST["grad"],	*
 * $_REQUEST["initialer"], $_REQUEST["navn"], $_REQUEST["MA"],	*
 * $_REQUEST["enhed"], $_REQUEST["email"]			*
 ***************************************************************/


function GemRetPersonel()
{
// Saml data sammen i variable.

// Konstruer alle nødvendige queries og fyr dem af.
$user= $_SESSION["userObj"]->userNumber();

	if (!$_SESSION["userObj"]->userIsLegal()) {
		/* Not Legal user; log him out, he must not make any changes. */
		$host  = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'main.php?M_action=LogUd';
		header("Location: http://$host$uri/$extra");
		print_footer(false);
		exit;	
	}



// Find current "admin"
$p_query= 'SELECT personel.admin FROM personel WHERE id='.$_REQUEST["jour_id"];
$p_result = DebugQuery($p_query);
$ad = mysql_fetch_row($p_result);
$admin= $ad[0]+0;

if (!($admin & RIGHTS_System))
{// insert new rights. In case of system administrator, no change is allowed.
if($_REQUEST["RightAdmin"] == "on")
	{
		$admin|= RIGHTS_Admin;
	}
	else
	{
		$admin&= ~ RIGHTS_Admin;
	}

if($_REQUEST["RightPersonel"] == "on")
	{
		$admin|= RIGHTS_Personel;
	}
	else
	{
		$admin&= ~ RIGHTS_Personel;
	}

if($_REQUEST["RightEnheder"] == "on")
	{
		$admin|= RIGHTS_Enheder;
	}
	else
	{
		$admin&= ~ RIGHTS_Enheder;
	}

if($_REQUEST["RightAccess"] == "on")
	{
		$admin|= RIGHTS_ReadWrite;
	}
	else
	{
		$admin&= ~ RIGHTS_ReadWrite;
	}
}


if ($_SESSION["userObj"]->userMayWritePersonel())
{ // We have the right to do the update.
	
$db= $_SESSION["DbSelected"];
$pwd= $_REQUEST["pass"];

$funk= mysql_real_escape_string(htmlentities($_REQUEST["funktion"]));
$grad= mysql_real_escape_string(htmlentities($_REQUEST["grad"]));
$init= mysql_real_escape_string(htmlentities($_REQUEST["initialer"]));
$navn= mysql_real_escape_string(htmlentities($_REQUEST["navn"]));
$ma= mysql_real_escape_string(htmlentities($_REQUEST["MA"]));
$enh= mysql_real_escape_string(htmlentities($_REQUEST["enhed"]));
$email= mysql_real_escape_string(htmlentities($_REQUEST["email"]));
$phone= mysql_real_escape_string(htmlentities($_REQUEST["phone"]));
$idR= mysql_real_escape_string(htmlentities($_REQUEST["jour_id"]));

if(strlen($pwd) == 0)
{	// No password given, do the update, no password
	$main_query= "UPDATE personel ".
	"SET funktion='".$funk."', grad='".$grad."', initialer='".$init.
	"', navn='".$navn."', MA='".$ma."', enhed='".$enh."', admin='".$admin.
	"', phone='".$phone."', email='".$email."' WHERE id='".$idR."'";
// Performing SQL query
$result = DebugQuery($main_query);
$extra = 'main.php?M_action=VisPersonel';
}
elseif(ValidatePass($pwd))
{	// Validated password given, do update including password
$MD5pass= CryptPass($pwd, $db);
	$main_query= "UPDATE personel ".
	"SET funktion='".$funk."', grad='".$grad."', initialer='".$init.
	"', navn='".$navn."', MA='".$ma."', enhed='".$enh."', admin='".$admin.
	"', phone='".$phone."', email='".$email."', password='".$MD5pass."' WHERE id='".$idR."'";
// Performing SQL query
$result = DebugQuery($main_query);
$extra = 'main.php?M_action=VisPersonel';
}
else
{	// Not valid password is given, DON'T do any update but show the error page
	$extra = 'main.php?M_action=Error&next=RetPersonel&jour_id='.$_REQUEST["jour_id"].'&error=BadPass';
}

}

/* Redirect to main page so reload will not insert another copy of personel. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;
}
?>
