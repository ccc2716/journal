<?php
/****************************************************************
//  File: Error.php						*
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
//  to draw the "Error" page in the system			*
//	$Id$							*
//	This function looks for the following input:		*
*   $_REQUEST["error"], $_REQUEST["next"], $_REQUEST["jour_id"],	*
*   $_REQUEST["M_action"], $_REQUEST['Journ']			*
//**************************************************************/


function Error()
{
	global $TemaArray;
	
$Error= $_REQUEST["error"];
$Next= $_REQUEST["next"];

switch($Error)
{
	case "BadPass":
		$Text= "Dit nye password opfylder ikke alle krav til et godt password. Password er IKKE &aelig;ndret.";
		$Forklar= "Der skal v&aelig;re store bogstaver, sm&aring; bogstaver og tal i password og l&aelig;ngden skal v&aelig;re minimum 8 tegn.";
		break;

	case "WrongPass":
		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
			}

			// Finally, destroy the session.
			session_destroy();
		$Text= "<SPAN class=header><FONT class=action>Forkert password.</FONT></SPAN>
			<P>Det indtastede password passede ikke til det gemte.</P>
			<P>Kontroller om CAPS LOCK eller num lock er sl&aring;et til, ret fejlen og pr&oslash;v igen.</P>
			<input type=\"text\" name=\"Dummy\" Size=\"50\" value=\"Her kan du pr&oslash;ve om CAPS LOCK eller num lock er til.\">
			<P>I tvivlstilf&aelig;lde, henvendelse hos systemadministratoren.</P>";
		break;

	case "TwoPass":
		$Text= "De to indtastninger af dit nye password er ikke ens. Password er IKKE &aelig;ndret.";
		break;

	case "DupJourn":
		$Text= "Journalen (".$_REQUEST['Journ'].") findes i forvejen";
		$Forklar= "V&aelig;lg et andet navn eller brug den eksisterende journal.";
		break;

	case "IllChar":
		$Text= "Journalnavnet (".$_REQUEST['Journ'].") indeholder tegn, der ikke kan anvendes";
		$Forklar= "F&oslash;lgende tegn m&aring; anvendes: a - z, 0 - 9, \"-\" og \"_\"";
		break;

	case "NoSelect":
		$Text= "Der var ikke valgt en journal.";
		$Forklar= "V&aelig;lg en journal fra listen.";
		break;

	case "NoUser":
		$Text= "Der var ikke valgt en bruger.";
		$Forklar= "V&aelig;lg en bruger fra listen.";
		break;

	default:
		$Text= "Ukendt fejl er opst&aring;et: ".$Error." Next: ".$Next;
}


	top();
?>
	<div class="midt">
<?php

printf("<P><FONT class=felt>Der er opst&aring;et f&oslash;lgende fejl:</FONT></P> %s\n", $Text);
if(strlen($Forklar) > 0)
	printf("<P>%s</P>\n", $Forklar);
if(isset($_REQUEST["jour_id"])) $SendId= "&id=".$_REQUEST["jour_id"]; else $SendId= "";
printf("<A class=menu HREF=\"main.php?M_action=%s%s\">Forts&aelig;t.</A>\n", $Next, $SendId);
?>
		</div>
<?php
// Hack coming here:
$_REQUEST["M_action"]= $_REQUEST["M_action"]."-".$Error;	// Print sensible explanation in journal.log

bottom(isset($_SESSION["userObj"]));
}
?>
