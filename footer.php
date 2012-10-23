<?php
/****************************************************************
//  File: footer.php						*
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
//  This file holds the footer() function used in the system	*
//								*
//	$Id$							*
//								*
//**************************************************************/


//***************************************************************
//								*
//	print_footer($Print);					*
//								*
// Used for printing the standard footer on every web page.	*
//	If $Print is true, it will print the information,	*
//		otherwise no print, only logging.		*
//								*
//**************************************************************/

require_once( 'Version.php' );
require_once("MiscFunctions.php");

function print_footer($Print= true)
{
global $StartTimer;

$f_time= 0;
foreach (glob("Brugervejledning*.pdf") as $filename) {
	if (filemtime($filename) > $f_time){
		$f_name= $filename;
		$f_time= filemtime($filename);
	}
}
//    echo "$f_name date " . $f_time . "\n<br>";
?>
<TABLE width= "100%">
<TR>
<TD width= "600"><P class="manual">Status info.</P>
</TD>
<TD><A class=manual type="application/pdf" href=<?php echo "\"".$f_name."\""; ?> target="_blank">Brugervejledning (&Aring;bner i nyt vindue).</A>
</TD></TR>
<?php

// Determine preferred language. danish or anything else.
	$lang_a= explode(",",$_SERVER["HTTP_ACCEPT_LANGUAGE"],3);
	$lang= substr($lang_a[0], 0, 2);

if(isset($_SESSION["DbSelected"]) and array_key_exists("userObj", $_SESSION)){

$user= $_SESSION["userObj"]->userNumber();
$egenenhed= $_SESSION["userObj"]->userEgenEnhed();
$db= $_SESSION["DbSelected"];


// Performing SQL query
$query = 'SELECT personel.navn FROM personel WHERE personel.id='.$user;
$result = DebugQuery($query);

$line = mysql_fetch_row($result);

if ($Print) printf("<tr><td>Journal er: %s&nbsp;&nbsp;&nbsp;&nbsp;\n", $db);

$bruger= stripcslashes($line[0]);

if ($Print) printf("Bruger er: %s</td>\n", $bruger);

// Free resultset
mysql_free_result($result);

// Performing SQL query
$query = 'SELECT navn, kaldetal, tlf, email FROM enheder WHERE id='.$egenenhed;
// echo $query;
$result = DebugQuery($query);
$line = mysql_fetch_row($result);

if ($Print) printf("<td>Journalen f&oslash;res for: %s; kaldetal: %s; telefon: %s; email: %s</td></tr>\n", $line[0], $line[1], $line[2], $line[3]);

// Free resultset
mysql_free_result($result);
}

// Get date into $dato

date_default_timezone_set("Europe/Copenhagen");

//	$dato= date("r", getlastmod ( void));
	$tid= date("r");


// Print date depending on preferred language.
    switch ($lang){
	case "da":
	    echo "<tr><td>Denne side er sendt fra serveren $tid</td>";
//	    echo "<P>Denne side er senest &aelig;ndret $dato</P>";
	    break;

	default:
	    echo "<tr><td>This page was served $tid</td>";
//	    echo "<P>This page was last changed $dato</P>";
    }

//	echo "<P>&copy;Copyright Sten Carlsen. 2006, 2007</P>";

if ($Print) printf("<td><P class=\"manual\">Access rights: %s %s</P>\n", ($_SESSION["userObj"]->userMayWrite()?"Read Write Access":"Read Only Access"),
 	($_SESSION["userObj"]->userRightsCheck(RIGHTS_System)?"System":" "));

$TransTid= microtime(true)-$StartTimer;
$t= sprintf("%5.5s", $TransTid*1000);

	echo "<P class=\"manual\">Version ".Version." - ".$t." ms</P></td></tr>";
	echo "</TABLE>";

// LOG stuff.

	$FileLog=fopen("journal.log", "a");
	// Tid; Journal; Bruger; Handling;
	$logdata= sprintf("%s;%s;%s;%s;%s;%s;\n", $_SERVER['REMOTE_ADDR'],$tid, Version, $_SESSION["DbSelected"], html_entity_decode($bruger), $_REQUEST["M_action"]);
//	echo "--------".$logdata;
	fwrite($FileLog, $logdata);
/*	$xxx= "Char trans:".print_r(get_html_translation_table(HTML_ENTITIES), true);
	fwrite($FileLog, $xxx);*/
	fclose($FileLog);

}
?>
