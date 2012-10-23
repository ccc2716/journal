<?php
/****************************************************************
//  File: NyJournal.php						*
//								*
//  This file is a part of the "journal system for HJV"		*
//								*
//  Copyright Sten Carlsen 2006, 2007				*
//								*
//  Journal-systemet is free software: you can redistribute it
//  and/or modify it under the terms of the GNU General Public
//  License as published by the Free Software Foundation, either
//  version 3 of the License, or (at your option) any later
//  version.
// 
//  Journal-systemet is distributed in the hope that it will be
//  useful, but WITHOUT ANY WARRANTY; without even the implied
//  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
//  PURPOSE. See the GNU General Public License for more details.
// 
//  You should have received a copy of the GNU General Public
//  License along with Journal-systemet. If not, see
//  <http://www.gnu.org/licenses/>.
//								*
//  This file holds the function used				*
//  to draw the "Ny Journal" page in the system			*
//	$Id$							*
//								*
//**************************************************************/



function NyJournal()
{
	global $TemaArray;
	
	top();
?>

	<div class="midt">
<FORM action="main.php" enctype="multipart/form-data" method="post" onsubmit="mySetSize()">
<INPUT Type=HIDDEN NAME="M_action" VALUE="GemNyJournal">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<INPUT Type=HIDDEN NAME="M_height" VALUE="720">

   <P>
<FONT class=felt>Journalens Navn:</FONT> Eks.: 20080602hhdkve (&Aring;R M&aring;ned Dag Enhed). Kun sm&aring; bogstaver kan bruges, store bogstaver vil blive konverteret til sm&aring;, undlad &aelig;&oslash;&aring;.<br>
<INPUT Type="text" NAME="journal" size="16" Value="">
<br>
<br>
<TABLE>
<TR>
<TD><FONT class=felt>Initialiseringsdata(Personel):</FONT><br>
<input name="personelini" type="file" size="50" maxlength="100" value="personel.ini">
</TD>
<TD>
	<P><A class=menu HREF="personel.ini">Eksempel p&aring; personel data file.</A><br>
	<FONT class=menu>Brug "Back"-tasten i browseren for at komme tilbage.</FONT>
	</P>
</TD>
</TR>
<TR>
<TD>
<FONT class=felt>Initialiseringsdata(Enheder):</FONT><br>
<input name="enhedini" type="file" size="50" maxlength="100" value="enheder.ini">
</TD>
<TD>
	<P><A class=menu HREF="enheder.ini">Eksempel p&aring; enheds data file.</A><br>
	<FONT class=menu>Brug "Back"-tasten i browseren for at komme tilbage.</FONT>
	</P>
</TD>
</TR>
</TABLE>
<br>
<br>
<br>
<INPUT type="submit" value="Opret journal">
   </P>
</FORM>
		</div>
<?php
bottom(false);
}




/****************************************************************
*								*
*   This function is used					*
*   to make a new journal database in the system		*
*								*
*  This function is looking for the following input:		*
*  $_REQUEST["journal"], $AdminArray["AdminName"],		*
*  $AdminArray["AdminPass"], $_FILES["enhedini"],		*
*  $_FILES["personelini"]					*
****************************************************************/

function GemNyJournal()
{
global $AdminArray;


require_once( 'Version.php' );


// Saml data sammen i variable.
$dba= $_REQUEST["journal"];
$db= strtolower($dba);

// Check for characters that don't work in filenames.
if (preg_match("/[^a-z0-9_-]/", $db))
{	// journal name has illegal characters. Send user to error page.
/* Redirect to error page. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'main.php?M_action=Error&error=IllChar&next=SelDb&Journ='.$dba;
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;	
}


// Check om databasen eksisterer i forvejen.
// Performing SQL query
$query = 'SHOW DATABASES LIKE "'.JournalName.$db.'"';

$result = DebugQuery($query);

// echo "Svar:<br>";
$line = mysql_fetch_row($result);
if ($line[0] != JournalName.$db)
{

// Konstruer alle nødvendige queries og fyr dem af.

// ~~~~~~~~~~~~~ Create Database ~~~~~~~~~~~~~~~~~~~~~~~

$db2_query=	"CREATE DATABASE ".JournalName.$db." ".
		"CHARACTER SET utf8 COLLATE utf8_danish_ci;";
$db3_query=	"USE ".JournalName.$db.";";

// Performing SQL query
$result = DebugQuery($db2_query);

// Performing SQL query
$result = DebugQuery($db3_query);

// enheder.
// ~~~~~~~~~~~~~ Create TABLE:enheder ~~~~~~~~~~~~~~~~~~~~~~~

$t2_query=	"CREATE TABLE IF NOT EXISTS `enheder` (`id` int(3) NOT NULL auto_increment,
		`navn` varchar(255) NOT NULL default '',
		`kaldetal` char(6) default '00',
		`tlf` varchar(30) default '',
		`email` varchar(255) default '',
		`net` int(2) default '0',
		PRIMARY KEY  (`id`)
		) TYPE=MyISAM COMMENT='Liste over indgående enheder.' AUTO_INCREMENT=1 ;";



// Performing SQL query
$result = DebugQuery($t2_query);

// modtagere.
// ~~~~~~~~~~~~~ Create TABLE:modtagere ~~~~~~~~~~~~~~~~~~~~~~~

$t2_query=	"CREATE TABLE IF NOT EXISTS `modtagere` (
		`signalID` int(11) unsigned NOT NULL default '0',
		`enhederID` int(11) unsigned NOT NULL default '0',
		KEY `signalID` (`signalID`)
		) TYPE=MyISAM COMMENT='relation mellem signaler og modtagere';";

// echo "$t2_query<br><br>\n";

// Performing SQL query
$result = DebugQuery($t2_query);

// info.
// ~~~~~~~~~~~~~ Create TABLE:info ~~~~~~~~~~~~~~~~~~~~~~~

$t2_query=	"CREATE TABLE IF NOT EXISTS `info` (
		`signalID` int(11) unsigned NOT NULL default '0',
		`enhederID` int(11) unsigned NOT NULL default '0',
		KEY `signalID` (`signalID`)
		) TYPE=MyISAM COMMENT='relation mellem signaler og info-modtagere';";


// Performing SQL query
$result = DebugQuery($t2_query);

// personel.
// ~~~~~~~~~~~~~ Create TABLE:personel ~~~~~~~~~~~~~~~~~~~~~~~

$t1_query=	"CREATE TABLE `personel` (
		`id` int(3) unsigned NOT NULL auto_increment,
		`funktion` varchar(64) collate utf8_danish_ci NOT NULL default 'sthj',
		`grad` varchar(64) collate utf8_danish_ci NOT NULL default 'mg',
		`initialer` varchar(64) collate utf8_danish_ci NOT NULL default '',
		`navn` varchar(255) collate utf8_danish_ci NOT NULL default '',
		`MA` varchar(12) default '',
		`enhed` varchar(255) collate utf8_danish_ci default '',
		`email` varchar(255) default '',
		`phone` varchar(255) default '',
		`direction` varchar(255) default '',
		`admin` int(4) NOT NULL default '0',
		`password` varchar(255) default '',
		`tema` int(4) NOT NULL default '0',
		PRIMARY KEY  (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci COMMENT='personel';";

// Performing SQL query
$result = DebugQuery($t1_query);

// signaler.
// ~~~~~~~~~~~~~ Create TABLE:signaler ~~~~~~~~~~~~~~~~~~~~~~~

$t1_query=	"CREATE TABLE `signaler` (
		`id` int(11) unsigned NOT NULL auto_increment,
		`afsenderID` int(5) NOT NULL default '0',
		`dtg` varchar(30) collate utf8_danish_ci NOT NULL default '',
		`dtg_stamp` datetime NOT NULL default '0000-00-00 00:00:00',
		`overskrift` varchar(255) collate utf8_danish_ci NOT NULL default '',
		`kort_text` text collate utf8_danish_ci,
		`keyopID` int(5) default NULL,
		`indtast_tid` timestamp NOT NULL default CURRENT_TIMESTAMP,
		`signalmiddel` varchar(10) default NULL,
		`priority` int(2) NOT NULL default '0',
		`Rettelse` int(3) NOT NULL default '0',
		`legim_Q` varchar(2) default NULL,
		`legim_svar` varchar(2) default NULL,
		`legim_sig` varchar(2) default NULL,
		PRIMARY KEY  (`id`),
		KEY `dtg_stamp` (`dtg_stamp`),
		FULLTEXT KEY `tekst` (`overskrift`,`kort_text`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci COMMENT='signaler';";

// Performing SQL query
$result = DebugQuery($t1_query);


// ~~~~~~~~~~~~~ Create TABLE:noter ~~~~~~~~~~~~~~~~~~~~~~~

$t1_query=	"CREATE TABLE `noter` (
		`id` int(11) unsigned NOT NULL auto_increment,		       
		`signal_ID` int(11) unsigned NOT NULL,			       
		`note_ID` int(5) default NULL,				       
		`note_tid` timestamp NOT NULL default CURRENT_TIMESTAMP,       
		`note_text` text collate utf8_danish_ci,		       
		PRIMARY KEY  (`id`),
		FULLTEXT KEY `tekst` (`note_text`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci COMMENT='noter';";

// Performing SQL query
$result = DebugQuery($t1_query);



// ~~~~~~~~~~~~~ Create TABLE:foretaget ~~~~~~~~~~~~~~~~~~~~~~~

$t1_query=	"CREATE TABLE `foretaget` (
		`id` int(11) unsigned NOT NULL auto_increment,
		`signal_ID` int(11) unsigned NOT NULL,				
		`foretagetID` int(5) NOT NULL default '0',
		`over_bool` int(4) NOT NULL default '0',
		`over_text` varchar(60) NOT NULL default '',
		`side_bool` int(4) NOT NULL default '0',
		`side_text` varchar(60) NOT NULL default '',
		`under_bool` int(4) NOT NULL default '0',
		`under_text` varchar(60) NOT NULL default '',
		`journ_bool` int(4) NOT NULL default '0',
		`journ_text` varchar(60) NOT NULL default '',
		`sitkort_bool` int(4) NOT NULL default '0',
		`sitkort_text` varchar(60) NOT NULL default '',
		`rund_bool` int(4) NOT NULL default '0',
		`rund_text` varchar(60) NOT NULL default '',
		`foretaget_tid` timestamp NOT NULL default CURRENT_TIMESTAMP,
		PRIMARY KEY  (`id`),
		FULLTEXT KEY `tekst` (`over_text`, `side_text`, `under_text`, `journ_text`, `sitkort_text`, `rund_text`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci COMMENT='foretaget';";

// Performing SQL query
$result = DebugQuery($t1_query);


// ~~~~~~~~~~~~~ PrePopulate TABLE:personel ~~~~~~~~~~~~~~~~~~~~~~~

/* Insert the first user into system: admin */
$admin= mysql_real_escape_string(htmlentities($AdminArray["AdminName"]));
$pass= mysql_real_escape_string($AdminArray["AdminPass"]);
$MD5pass= CryptPass($pass, JournalName.$db);

$ad_query=	"INSERT INTO personel(id, funktion, grad, initialer, navn, MA, enhed, email, admin, password) ".
		"VALUES (NULL,
			'admin',
			DEFAULT,
			DEFAULT,
			'".$admin."',
			DEFAULT,
			DEFAULT,
			DEFAULT,
			'255',
			'".$MD5pass."')";

// Performing SQL query
$result = DebugQuery($ad_query);

// Preload all personel that usually are present from personel.ini
if ($_FILES["personelini"]["error"] == 0)
	$personelfile= $_FILES["personelini"]["tmp_name"];
	else
	$personelfile= "personel.ini";

$InitArray = parse_ini_file($personelfile, true);

// List .ini file for debug
if ($_SESSION["Debug"]->debugActive(1))
	{
		$dp= print_r($InitArray, true);
		$d= "Personel InitArray: ".$dp."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}


foreach ($InitArray as $key => $value) {
	$MD5pass= CryptPass($InitArray[$key]["password"], JournalName.$db);
	$admin=0;
	if($InitArray[$key]["RIGHTS_Admin"] == "true") $admin+= RIGHTS_Admin;
	if($InitArray[$key]["RIGHTS_Personel"] == "true") $admin+= RIGHTS_Personel;
	if($InitArray[$key]["RIGHTS_Enheder"] == "true") $admin+= RIGHTS_Enheder;
	if($InitArray[$key]["RIGHTS_ReadWrite"] == "true") $admin+= RIGHTS_ReadWrite;
	
//	echo "-------".$InitArray[$key]["navn"]."----------<br>";
	
	$query= sprintf("INSERT INTO personel(id, funktion, grad, initialer, navn, MA, enhed, email, phone, admin, password) ".
	"VALUES(NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
	mysql_real_escape_string(htmlentities($InitArray[$key]["funktion"])),
	mysql_real_escape_string(htmlentities($InitArray[$key]["grad"])),
	mysql_real_escape_string(htmlentities($InitArray[$key]["initialer"])),
	mysql_real_escape_string(htmlentities($InitArray[$key]["navn"], ENT_COMPAT, "UTF-8", false)),
	mysql_real_escape_string(htmlentities($InitArray[$key]["MA"])),
	mysql_real_escape_string(htmlentities($InitArray[$key]["enhed"])),
	mysql_real_escape_string(htmlentities($InitArray[$key]["email"])),
	mysql_real_escape_string(htmlentities($InitArray[$key]["telefon"])),
	$admin, $MD5pass);

	$result = DebugQuery($query);
}


// ~~~~~~~~~~~~~ PrePopulate TABLE:enheder ~~~~~~~~~~~~~~~~~~~~~~~


// Preload all Enheder that usually are there from enheder.ini

if ($_FILES["enhedini"]["error"] == 0)
	$enhedfile= $_FILES["enhedini"]["tmp_name"];
	else
	$enhedfile= "enheder.ini";

$InitArray = parse_ini_file($enhedfile, true);

// List .ini file for debug
if ($_SESSION["Debug"]->debugActive(1))
	{
		$dp= print_r($InitArray, true);
		$d= "Enheder InitArray: ".$dp."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}


foreach ($InitArray as $key => $value) {
	$query= sprintf("INSERT INTO enheder(id, navn, kaldetal, tlf, email) VALUES(NULL, '%s', '%s', '%s', '%s')",
		mysql_real_escape_string(htmlentities($InitArray[$key]["navn"], ENT_COMPAT, "UTF-8", false)),
		mysql_real_escape_string(htmlentities($InitArray[$key]["kaldetal"])),
		mysql_real_escape_string(htmlentities($InitArray[$key]["tlf"])),
		mysql_real_escape_string(htmlentities($InitArray[$key]["email"])));

	$result = DebugQuery($query);
}


/* Redirect to main page so reload will not insert another copy of signal. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'main.php?M_action=SelDb';
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;

}
else
{	// journal was present, so we don't make it again. Send user to error page.
/* Redirect to error page. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'main.php?M_action=Error&error=DupJourn&next=SelDb&Journ='.$db;
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;	
}

}
?>
