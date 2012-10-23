<?php
/****************************************************************
//  File: Admin.php						*
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
//  This file holds the function drawing			*
//  the Administration page					*
//	$Id$							*
//								*
//**************************************************************/


function Admin()
{
	global $TemaArray;
	
	top();
?>

	<div class="midt">
		<P class=header><FONT class=action>Personel:</FONT></P>
<?php
if ($_SESSION["userObj"]->userMayWritePersonel())
	{
	echo '<P><A class=command HREF="main.php?M_action=NyPersonel">Ny personel.</A></P>';
	}
?>
		<P><A class=command HREF="main.php?M_action=VisPersonel">List personel.</A></P>
		<P class=header><FONT class=action>Enheder:</FONT></P>
<?php
if ($_SESSION["userObj"]->userMayWriteEnhed())
	{
	echo '<P><A class=command HREF="main.php?M_action=NyEnhed">Ny enhed.</A></P>';
	}
?>
		
		<P><A class=command HREF="main.php?M_action=VisEnhed">List enheder.</A></P>
		<P class=header><FONT class=action>Ops&aelig;tning:</FONT></P>
<?php
		printf("<P><FONT class=felt>Nuv&aelig;rende ops&aelig;tning:</FONT> <I>Nyeste tilf&oslash;jes %s.</I></P>\n",
			(($_SESSION["userObj"]->userDirection()) == "top"?"&oslash;verst":"nederst"));
?>
		<FORM action="main.php" method="get" onsubmit="mySetSize()">
		<INPUT Type=HIDDEN NAME="M_action" VALUE="GemSettings">
		<INPUT Type=HIDDEN NAME="M_height" VALUE="720">
		<P>
		<FONT class=felt>Nyeste skal v&aelig;re:</FONT>
		<SELECT name="retning">
<?php
		printf("<OPTION %s value=\"%s\">&Oslash;verst</OPTION>\n", (($_SESSION["userObj"]->userDirection()) == "top"?"selected":""), "top");
		printf("<OPTION %s value=\"%s\">Nederst</OPTION>\n", (($_SESSION["userObj"]->userDirection()) == "bottom"?"selected":""), "bottom");
?>
		   </SELECT>
		<br><br>
		<INPUT type="submit" value="Gem Ops&aelig;tning">
		</P>
		</FORM>
		<P class=header><FONT class=action>Tema:</FONT></P>
		<form action="main.php" method="get" accept-charset="utf-8" onsubmit="mySetSize()">
		<INPUT Type=HIDDEN NAME="M_action" VALUE="GemTema">
		<INPUT Type=HIDDEN NAME="M_height" VALUE="720">
			<select name="tema" size="1">
				<option value="1" <?php echo ($_SESSION["userObj"]->userTema() == 1)?"selected":""; ?>>
					<?php echo htmlentities($TemaArray["tema1text"], ENT_QUOTES, 'utf-8') ?></option>
				<option value="2" <?php echo ($_SESSION["userObj"]->userTema() == 2)?"selected":""; ?>>
					<?php echo htmlentities($TemaArray["tema2text"], ENT_QUOTES, 'utf-8') ?></option>
				<option value="3" <?php echo ($_SESSION["userObj"]->userTema() == 3)?"selected":""; ?>>
					<?php echo htmlentities($TemaArray["tema3text"], ENT_QUOTES, 'utf-8') ?></option>
			</select>

			<p><input type="submit" value="Gem Tema"></p>
		</form>
		
		<FORM action="main.php" method="get" onsubmit="mySetSize()">
		<INPUT Type=HIDDEN NAME="M_action" VALUE="GemNytPassword">
		<INPUT Type=HIDDEN NAME="M_height" VALUE="720">
		<FONT class=felt>Nyt password:</FONT>&nbsp;&nbsp;
		<input class="hilight" type="password" size= "20" name="pass" value="">&nbsp;gentag&nbsp;
		<input class="hilight" type="password" size= "20" name="pass2" value="">
		<INPUT type="submit" value="Gem nyt password"></FORM>
		</div>
<?php
bottom();
}

function VisLog()
{
	top();
?>
	<div class="midt">
		<PRE>
<?php
if ($_SESSION["userObj"]->userRightsCheck(RIGHTS_System))
{
// print logfile:
$log= fopen("journal.log", "r");

if ($log) {
    while (!feof($log)) {
        $buffer = fgets($log, 4096);
        echo $buffer;
    }
    fclose($log);
}
else
echo "log file could not be opened.";
}
?>
		</PRE>
		</div>
<?php
bottom();
}

/******************************************************************
 * 
 * DownloadFile()
 * 
 * Generates a backup using "SELECT INTO OUTFILE" to a tmpfile
 * and forces a download to client.
 * 
 * No inputs expected.
 * 
 * 
 * 
 * 
 * ****************************************************************/

function DownloadFile()
{

if ($_SESSION["userObj"]->userRightsCheck(RIGHTS_System))
{
// Generate dump file name
$tmpfname = "dump.sql";


if ($_SESSION["Debug"]->debugActive(128))
	{
		$dp= "Temp Filename: ".$tmpfname.", not functional yet.";
		$d= "Backup-1: ".$dp."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}
	
// Generate dump
// $query=	'SELECT * INTO OUTFILE \''.$tmpfname.'\' FROM signaler';
// $result = DebugQuery($query);



// $handle = fopen($tmpfname, "w");
// fwrite($handle, "writing to tempfile");
// fclose($handle);

// Start download

// Clean up


// do here something

// unlink($tmpfname);
}
else
	if ($_SESSION["Debug"]->debugActive(128))
		{
			$d= "Backup-2: Not System rights.\n";
			$_SESSION["Debug"]->debugFileWrite($d);
		}


/* Redirect to main page so reload will not insert another copy of signal. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'main.php?M_action=Admin';
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;

}


/****************************************************************
//  This function holds the function used			*
//  to save the changed settings from				*
//  the "Admin page" in the system				*
//	$Id$							*
//**************************************************************/

function GemTema()
{

//	$d= print_r($_REQUEST, true);
//	$_SESSION["Debug"]->debugFileWrite($d);

	$_SESSION["userObj"]->userTemaSet($_REQUEST["tema"]);
	

// $_SESSION["userObj"]->userSetDirection($_REQUEST["retning"]);

/* Redirect to main page so reload will not insert another copy of signal. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'main.php?M_action=Admin';
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;
}


/****************************************************************
//  This function holds the function used			*
//  to save the changed settings from				*
//  the "Admin page" in the system				*
//	$Id$							*
//**************************************************************/

function GemSettings()
{

$_SESSION["userObj"]->userSetDirection($_REQUEST["retning"]);

/* Redirect to main page so reload will not insert another copy of signal. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'main.php?M_action=Admin';
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;
}

/****************************************************************
//  This function holds the function used			*
//  to save the changed password from				*
//  the "Admin page" in the system				*
//								*
//**************************************************************/

function GemNytPassword()
{
$db= $_SESSION["DbSelected"];
$pwd= $_REQUEST["pass"];
$pwd2= $_REQUEST["pass2"];

// echo "GNP-1: ".$pwd." - ".$pwd2."<br>";

if($pwd == $pwd2)
{	// Now check if password is good
	if(ValidatePass($pwd))
	{
		$MD5pass= CryptPass($pwd, $db);
		$main_query= "UPDATE personel ".
			"SET password='".$MD5pass."' WHERE id='".$_SESSION["User"]."'";
		// Performing SQL query
		$result = DebugQuery($main_query);
		$extra = 'main.php?M_action=Admin';
	}
	else
	{	// Show "bad password" page
		$extra = 'main.php?M_action=Error&next=Admin&error=BadPass';
	}
}
else
{	// show "not identical" page
	$extra = 'main.php?M_action=Error&next=Admin&error=TwoPass';
}


/* Redirect to main page so reload will not insert another copy of signal. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;
}
?>
