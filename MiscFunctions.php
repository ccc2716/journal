<?php

/****************************************************************
//  File: MiscFunctions.php					*
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
//  This file holds various functions used in the system	*
//								*
//	$Id$							*
//								*
//**************************************************************/


/****************************************************************
//								*
//  function DebugQuery($query)					*
//								*
//  Function that will do a query				*
//  and if "DebugLevel" & 4,					*
//  will print the query before executing it			*
//  Returns the result from the query. This result must then	*
//  be released by mysql_free_result($result);			*
//								*
//***************************************************************/
 

function DebugQuery($query)
{
	if ($_SESSION["Debug"]->debugActive(4))
	{
		$d= "Query, ".$_REQUEST["M_action"].": ".$query."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
		$start_time= microtime(true);
	}
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	if ($_SESSION["Debug"]->debugActive(4))
	{
		$tid= microtime(true)-$start_time;
		$t= sprintf("%5.5s", $tid*1000);
		$d= "Query, ".$_REQUEST["M_action"].": ".$t." milliseconds\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}
	return $result;
}

/****************************************************************
//								*
//  function CryptPass($Pass, $JournalName)			*
//								*
//  Function that will scramble a password for storage.		*
//								*
//	MD5Arg is constructed:					*
//	password - <JournalName>xxx - <JournalSecret>		*
//								*
//								*
//***************************************************************/
 

function CryptPass($Pass, $JournalName){
	$MD5Arg= $Pass.$JournalName.JournalSecret;

	$MD5pass= md5($MD5Arg);

	if ($_SESSION["Debug"]->debugActive(2))
		{
			$d= "CryptPass:-- MD5Arg: ".$MD5Arg.", Return: ".$MD5pass."\n";
			$_SESSION["Debug"]->debugFileWrite($d);
		}

	return($MD5pass);
}

/****************************************************************
 *								*
 * 	ValidatePass($pass)					*
 * 								*
 *  Applies rules for good password selection			*
 * 	returns true if $pass is good				*
 * 	returns false if $pass is too simple			*
 * 								*
 * *************************************************************/

function ValidatePass($pass)
{
	if (strlen(strpbrk($pass, 'ABCDEFGHIJKLMNOPQRSTUVWXYZÆØÅ')) > 0) $Cap= true;
	if (strlen(strpbrk($pass, "abcdefghijklmnopqrstuvwxyzæøå")) > 0) $Low= true;
	if (strlen(strpbrk($pass, "0123456789")) > 0) $Num= true;
	if (strlen($pass) > 7) $Len= true;
	
	if ($_SESSION["Debug"]->debugActive(2))
	{
		$d= "ValidatePass:-- Password:".$pass."\n";
//		$_SESSION["Debug"]->debugFileWrite($d);
		$d2= "ValidatePass:-- Cap: ".(($Cap)?"OK":"fail").", Low: ".(($Low)?"OK":"fail").
			", Num: ".(($Num)?"OK":"fail").", Len: ".(($Len)?"OK":"fail")."\n";
		$_SESSION["Debug"]->debugFileWrite($d."\n".$d2);
	}
			
	if($Cap && $Low && $Num && $Len)
		return(true);
	else
		{
		return(false);
		}
}


/****************************************************************
*	function PrintSession()					*
*								*
*	Print all $_SESSION data to see what is in there	*
* 		Can ONLY be called AFTER session_start()	*
* **************************************************************/

function PrintSession(){
	if ($_SESSION["Debug"]->debugActive(8))
		{
			$dp= print_r($_SESSION, true);
			$d= "Session: ".$dp."\n";
			$_SESSION["Debug"]->debugFileWrite($d);
		}	
}

/****************************************************************
*	Constants						*
* 								*
****************************************************************/

// Network numbers.
define("NetOp", 1);
define("NetNed", 2);

/******************************************
Her defineres Rettigheder.
******************************************/

define("RIGHTS_Admin",	1);		// administrator, må meget.
define("RIGHTS_Personel", 2);		// Må oprette personel.
define("RIGHTS_Enheder", 4);		// Må oprette enheder.
define("RIGHTS_ReadWrite", 8);		// Read and Write Access til systemet.
define("RIGHTS_System",	64);		// administrator(*GUD*), må alt.


?>
