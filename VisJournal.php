<?php
/****************************************************************
//  File: VisJournal.php					*
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
//  to draw the "Vis Journal" page in the system		*
//	$Id$							*
//	This function looks for the following input:		*
* 	$_SESSION["User"], $_SESSION["EgenEnhed"],		*
* 	$_SESSION["direction"]					*
//**************************************************************/


function VisJournal()
{
define("PAGE_LEN", 30);		// Default 30 lines.
define("PAGE_WASTE", 110);	// Space used at top of page for menu etc.
define('LINE_HEIGHT', 21);	// Size of one line in pixels, will probably be different with different browsers. To be tested.


$mh= LINE_HEIGHT * PAGE_LEN +PAGE_WASTE;	// Default.


if(isset($_SESSION['M_height']))
{
	$mh= $_SESSION['M_height'];
if ($_SESSION["Debug"]->debugActive(4096))
	{
		$d= "VisJournal: PageLen-1 mh".$mh."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}
}

if (isset($_REQUEST["M_height"]))
{
	$mh= $_REQUEST['M_height'];
	$_SESSION['M_height']= $mh;
	if ($_SESSION["Debug"]->debugActive(4096))
		{
			$d= "VisJournal: PageLen-2 mh".$mh."\n";
			$_SESSION["Debug"]->debugFileWrite($d);
		}
}

$page_len= round(floor(($mh-PAGE_WASTE)/LINE_HEIGHT));		// Number of text lines we actually want to show.


if ($page_len < 1) $page_len= 1;		// Always show at least one line.

if ($_SESSION["Debug"]->debugActive(4096))
	{
		$d= "VisJournal: PageLen-3 ".$page_len." - mh ".$mh."\nlock: ".$_REQUEST['M_lock']." - sec: ".$_REQUEST['sec']." - ser: ".$_SESSION['ser']."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}





$l_no= 0;

top("onresize=\"myResize()\"");
?>

	<div class="midt">



<script type="text/javascript">
var lock= 0;

function myResize()
{
	if (lock++ < 1)
	{
		setTimeout(myCall(), 500);
	}
}

function myCall()
{
	var loc= location.href;
	var h=window.innerHeight;

	var match= loc.search("&M_height");
	if (match > 0)
		{
			var loc2= loc.substr(0,match);
		}
		else
		{
			var loc2= loc;
		}


		location.replace(loc2+"&M_height="+h);	
}
</script>

<?php
// Performing SQL query. Find number of signals.
$count_query = 'SELECT COUNT(*) FROM signaler, enheder WHERE signaler.afsenderID=enheder.id';

$result = DebugQuery($count_query);

$line = mysql_fetch_row($result);
$start_row= $line[0]-$page_len;
if ($start_row<0) $start_row= 0;

// Free resultset
mysql_free_result($result);


$user= $_SESSION["userObj"]->userNumber();
$egenenhed= $_SESSION["userObj"]->userEgenEnhed();

$QueryList=	' signaler.id'					// 0
		.', signaler.dtg'				// 1
		.', enheder.navn'				// 3
		.', signaler.overskrift'			// 4
		.', signaler.keyopID'				// 5
		.', signaler.priority'				// 6
		;

// Performing SQL query. Find the newest signals.

switch($_SESSION["userObj"]->userDirection()){
	case "top":
		$query = 'SELECT '.$QueryList.
		' FROM signaler, enheder '.
		' WHERE signaler.afsenderID=enheder.id '.
		' ORDER BY dtg_stamp DESC, signaler.id '.
		' LIMIT '.$page_len;
	break;
	
	case "bottom":
	default:
		$query = 'SELECT '.$QueryList.
		' FROM signaler, enheder '.
		' WHERE signaler.afsenderID=enheder.id '.
		' ORDER BY dtg_stamp, signaler.id '.
		' LIMIT '.$start_row.",18446744073709551615";
	break;
}


// echo $query."---".$user;
$result = DebugQuery($query);

echo "<table class=\"felt2\">\n";
echo "<thead>\n";

// printf("<TR><TH width=6%%>%s<TH width=11%%>%s<TH width=10%%>%s<TH width=10%%>%s<TH width=6%%>%s<TH width=58%%>%s\n"
printf("<TR><TH>%s<TH>%s<TH>%s<TH>%s<TH>%s<TH>%s\n"
	, "L&oslash;be nr.", "DTG", "Fra", "Til"
	, "<abbr TITLE=\"Foresat, Sideordnet, Underordnet, Journalbilag, Kortf&oslash;rt, Rundsendt\">FSUJKR</abbr>", "Overskrift");
// Printing results in HTML
echo "</thead>\n";


// Cycle through all listed signals.
while ($line = mysql_fetch_row($result)) {	
	// Performing SQL query. Find all receivers for this signal.
	$modt_query = 	"SELECT enheder.id, enheder.navn ".
			"FROM enheder, modtagere ".
			"WHERE modtagere.signalID=".$line[0]." AND enheder.id=modtagere.enhederID";

	$result_2 = DebugQuery($modt_query);
	$egennavn= "";
	$modtnavn= "";
	$allemodtagere= "";
	$antalmodt= 0;
	while ($modt = mysql_fetch_row($result_2)) {	// Loop with all receivers.
		$allemodtagere.= ((strlen($allemodtagere) == 0)?"":"; ").$modt[1];
		$antalmodt++;
		if ($modt[0] == $egenenhed)
			{$egennavn= $modt[1];}
		if(strlen($modtnavn) == 0) $modtnavn= $modt[1];
		}
		
	// echo $egennavn."-A-".$modtnavn."<br>";
	// Free resultset
	mysql_free_result($result_2);

	if ($line[4] == $user)	// Identify all your own signals.
	{
		$line_my= "mysig";
		$anker_my= "mya";
	}
	else 
	{
		$line_my= "sig";
		$anker_my= "norma";
	}
	if ($l_no++ % 2)	// Different colours to lines.
		$line_class= "odd";
	else
		$line_class= "even";

// Find latest foretaget.

// Performing SQL query. Find all foretaget for this signal.
	$fore_query = 	"SELECT "
			.' over_bool'				// 0
			.', side_bool'				// 1
			.', under_bool'				// 2
			.', journ_bool'				// 3
			.', sitkort_bool'			// 4
			.', rund_bool '				// 5
			." FROM foretaget "
			." WHERE signal_ID=".$line[0]
			." ORDER BY foretaget_tid DESC";

	$fore_result = DebugQuery($fore_query);
	$f_res = mysql_fetch_row($fore_result);

$Foretaget= ($f_res[0]?"F":"-").($f_res[1]?"S":"-").($f_res[2]?"U":"-").($f_res[3]?"J":"-").($f_res[4]?"K":"-").($f_res[5]?"R":"-");
// echo "---$Foretaget\n";

	printf("<TR name=\"sigs\" class=%s><TD class=%s><A class=%s HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">%s%s</A>"
		."<TD class=%s><A class=%s HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">%s</A>"
		."<TD class=%s><A class=%s HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">%s"
		."<TD class=%s><A class=%s HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">"
		.(($antalmodt >= 2)?"<abbr TITLE=\"%s\">%s</abbr>":"%s%s")
		."<TD class=%s><A class=%s HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">%s"
		."<TD class=%s><A class=%s HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">%s\n",
		$line_class, $line_my, $anker_my, $line[0], ($line[5] == 0)?" ":"*", $line[0]
		, $line_my, $anker_my, $line[0], $line[1]
		, $line_my, $anker_my, $line[0], $line[2]
		, $line_my, $anker_my, $line[0], (($antalmodt >= 2)?$allemodtagere:""), ($egennavn=="")?$modtnavn:$egennavn
		, $line_my, $anker_my, $line[0], $Foretaget
		, $line_my, $anker_my, $line[0], stripcslashes($line[3]));
				
	}

// Free resultset
mysql_free_result($result);

echo "</table>";
// echo "Serial: ".$_SESSION["ser"]."<br>";
echo "</div>";

bottom();
?>
<?php
}
?>
