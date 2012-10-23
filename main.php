<?php
/************************************************************************
//	File: main.php							*
//									*
//	This file is a part of the "journal system for HJV"		*
//									*
//	Copyright Sten Carlsen 2006, 2007				*
//									*
//	Journal-systemet is free software: you can redistribute it	*
//	and/or modify it under the terms of the GNU General Public	*
//	License as published by the Free Software Foundation, either	*
//	version 3 of the License, or (at your option) any later		*
//	version.							*
//  									*
//	Journal-systemet is distributed in the hope that it will be	*
//	useful, but WITHOUT ANY WARRANTY; without even the implied	*
//	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR		*
//	PURPOSE. See the GNU General Public License for more details.	*
//  									*
//	You should have received a copy of the GNU General Public	*
//	License along with Journal-systemet. If not, see		*
//	<http://www.gnu.org/licenses/>.					*
//									*
//	This file holds the main program for the system			*
//									*
//	$Id$								*
//									*
//**********************************************************************/

function __autoload($class_name) {
    require_once $class_name . '.php';
}

require_once("footer.php");
require_once("top.php");
require_once("bottom.php");
require_once("SelDb.php");
require_once("SelUser.php");
require_once("SetStartCond.php");
require_once("VisJournal.php");
require_once("PrintHelJournal.php");
require_once("NyMelding.php");
require_once("VisMelding.php");
require_once("NyPersonel.php");
require_once("VisPersonel.php");
require_once("VisEnPersonel.php");
require_once("RetPersonel.php");
require_once("NyEnhed.php");
require_once("VisEnheder.php");
require_once("VisEnEnhed.php");
require_once("RetEnhed.php");
require_once("NyJournal.php");
require_once("LogOut.php");
require_once("Error.php");
require_once("Admin.php");
require_once("network_functions.php");
require_once("MiscFunctions.php");
require_once("Version.php");

// House keeping.
global $InitArray, $DirArray, $AccessArray, $link, $StartTimer, $TemaArray;

$StartTimer=  microtime(true);

date_default_timezone_set("Europe/Copenhagen");

$InitArray = parse_ini_file("journal.ini", true);
$DbArray= $InitArray["database"];
$DebugArray= $InitArray["debug"];
$AdminArray= $InitArray["admin"];
$DirArray= $InitArray["display"];
$RetteArray= $InitArray["rettelser"];
$AccessArray= $InitArray["access"];
$TemaArray= $InitArray["Tema"];
$SessionArray= $InitArray["Session"];

/*
ini_set("session.gc_maxlifetime", "6000");
*/
ini_set("session.use_only_cookies", "On");
ini_set("session.cookie_httponly", "On");
session_name($SessionArray["name"]);
session_start();

if(!isset($_SESSION["Debug"]))
	{
		$_SESSION["Debug"]= new debug("debug.log", $DebugArray["DebugLevel"]);
	}
	else
	{
		$_SESSION["Debug"]->debugSetMask($DebugArray["DebugLevel"]);
	}

if (isset($_REQUEST['M_height']))
{
	$_SESSION['M_height']= $_REQUEST['M_height'];
	if ($_SESSION["Debug"]->debugActive(4096))
		{
			$d= "main: PageLen-1 M_height".$_SESSION['M_height']."\n";
			$_SESSION["Debug"]->debugFileWrite($d);
		}
}

// Connecting to database server
$link = mysql_connect($DbArray["DbServer"], $DbArray["DbUser"], $DbArray["DbPassword"])
   or die('Could not connect to '.$DbArray["DbServer"].' : ' . mysql_error());
// echo 'Connected successfully';

PrintSession();

// Selecting database (journal)

if(isset($_SESSION['DbSelected']))
	{
		$db= $_SESSION["DbSelected"];
		mysql_select_db($db) or die('Could not select database '.
			$_SESSION["DbSelected"].' (SESSION)');
	}
	elseif(isset($_REQUEST["DbSelected"]))
	{
		if ($_REQUEST["DbSelected"] <> "0")
			{
				$db= $_REQUEST["DbSelected"];
				mysql_select_db($db) or die('Could not select database '.
					$_SESSION["DbSelected"].' (REQUEST)');
			}
	}
		else
		{
			/* Not Legal user; log him out, he must not make any changes or read anything.
			 	A few cases are allowed.*/

			switch($_REQUEST["M_action"]){
				case "SelDb":
				case "SelUser":
				case "NyJournal":
				case "Error":
				case "GemNyJournal":
					break;

				default:
					$_REQUEST["M_action"]= "LogUd";
					break;
			}
		}
	

if ($_SESSION["Debug"]->debugActive(1024))
	{
		$d= print_r($_SESSION["userObj"], TRUE);
		$_SESSION["Debug"]->debugFileWrite($d);
	}


if ($_SESSION["Debug"]->debugActive(2048))
	{
		$d= print_r($_SESSION["Debug"], TRUE);
		$_SESSION["Debug"]->debugFileWrite($d);
	}


// List .ini file for debug
if ($_SESSION["Debug"]->debugActive(1))
	{
		$dp= print_r($InitArray, true);
		$d= "Main InitArray: ".$dp."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}

// Main switch is here, branch to the correct function.

if ($_SESSION["Debug"]->debugActive(32))
	{
		$d= "main(M_action): ".$_REQUEST["M_action"]."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}
if ($_SESSION["Debug"]->debugActive(64))
	{
		$d= "main - QUERY_STRING: ".$_SERVER["QUERY_STRING"]."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}
if ($_SESSION["Debug"]->debugActive(256))
	{
		$dp= print_r($_REQUEST, true);
		$d= 'main - $_REQUEST: '.$dp."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}

switch ($_REQUEST["M_action"]){
	case "SelUser":
		SelUser();
	break;

	case "Error":
		Error();
	break;

	case "GemNytPassword":
		GemNytPassword();
	break;

	case "GemTema":
		GemTema();
	break;

	case "GemSettings":
		GemSettings();
	break;

	case "PrintJournal":
		PrintHelJournal(PrintLinie);
	break;

	case "PrintHelJournal":
		PrintHelJournal(PrintMelding);
	break;

	case "SetStartCond":
		SetStartConditions();
		$_SESSION["userObj"]= new activeUser($InitArray);
	break;

	case "GemNyMelding":
		GemNyMelding();
	break;

	case "GemNyCBRN":
		GemNyCBRN();
	break;

	case "GemRetMelding":
		GemRetMelding();
	break;

	case "VisJournal":
	case "Main":
		VisJournal();
	break;

	case "NyMelding":
		NyMelding();
	break;

	case "NyCBRNnuc":
		NyCBRN("nuc");
	break;

	case "NyCBRNchem":
		NyCBRN("chem");
	break;

	case "VisMelding":
		VisMelding();
	break;

	case "GemForetaget":
		GemForetaget();
	break;

	case "NyPersonel":
		NyPersonel();
	break;

	case "VisPersonel":
		VisPersonel();
	break;

	case "VisEnPersonel":
		VisEnPersonel();
	break;

	case "RetPersonel":
		RetPersonel();
	break;

	case "GemNyPersonel":
		GemNyPersonel();
	break;

	case "GemRetPersonel":
		GemRetPersonel();
	break;

	case "NyJournal":
		NyJournal();
	break;

	case "GemNyJournal":
		GemNyJournal();
	break;

	case "NyEnhed":
		NyEnhed();
	break;

	case "RetEnhed":
		RetEnhed();
	break;

	case "VisEnhed":
		VisEnheder();
	break;

	case "VisEnEnhed":
		VisEnEnhed();
	break;

	case "GemNyEnhed":
		GemNyEnhed();
	break;

	case "GemRetEnhed":
		GemRetEnhed();
	break;

	case "Admin":
		Admin();
	break;

	case "VisLog":
		VisLog();
	break;

	case "Backup":
		DownloadFile();
	break;

	case "SelDb":
		SelDb();
	break;

	case "GemNoter":
		GemEkstraNoter();
	break;

	case "LogUd":
	default:
		LogOut();
	break;
	}
?>
