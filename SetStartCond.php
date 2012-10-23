<?php
/****************************************************************
//  File: SetStartCond.php					*
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
//  to save the start settings the system			*
//	$Id$							*
//								*
//**************************************************************/


function SetStartConditions()
{
global $DirArray;


$db= $_SESSION["DbSelected"];
$pwd= $_REQUEST["passwd"];

$MD5pass= CryptPass($pwd, $db);

// Performing SQL query
$pass_query= 'SELECT personel.password FROM personel WHERE personel.id='.$_REQUEST["User"];
$result = DebugQuery($pass_query);

$line = mysql_fetch_row($result);

if ($_SESSION["Debug"]->debugActive(2))
	{
		$d= "User: ".$_REQUEST["User"].", Pass: ".$pwd.", db: ".$db.", CryptPass: ".$MD5pass.", DbPass: ".$line[0]."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
		
	}

// Check for correct password.
if($line[0] == $MD5pass)
	{$extra = 'main.php?M_action=VisJournal';}
elseif($_REQUEST["User"] == 0)
	{$extra = 'main.php?M_action=Error&error=NoUser&next=SelDb';}
else
	{$extra = 'main.php?M_action=Error&error=WrongPass&next=SelDb';}

$_SESSION["User"]= $_REQUEST["User"];
$_SESSION['EgenEnhed']= $_REQUEST["EgenEnhed"];


/* Redirect to main page so reload will not harm us. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
header("Location: http://$host$uri/$extra");
print_footer(false);
}
?>
