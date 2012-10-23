<?php
/****************************************************************
//  File: TimeFunctions.php					*
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
//  This file holds time related functions used in the system	*
//								*
//	$Id$							*
//								*
//**************************************************************/


//***************************************************************
//								*
//		t_stamp(DTG);					*
//								*
// Makes a timestamp from DTG in standard UTC-time, taking	*
// timezone information in DTG format into account.		*
//								*
//								*
//**************************************************************/


function t_stamp($d)
{
// echo "Whole:".$d;
$d= dtg_strip_blank($d);	// All blanks are now gone.

$day= substr($d, 0, 2);
// echo "day: ".$day;

$hour= substr($d, 2, 2);
// echo "hour: ".$hour;

$min= substr($d, 4, 2);
// echo "min: ".$min;

$tz= substr($d, 6, 1);

$d2= substr($d, 7);		// Month and year now remains.

$mon= substr($d2, 0, strcspn($d2, "0123456789"));
switch(strtolower($mon))
{
	case "jan":
	case "januar":
	case "january":
		$mon="01";
	break;

	case "feb":
	case "februar":
	case "february":
		$mon="02";
	break;

	case "mar":
	case "marts":
	case "march":
		$mon="03";
	break;

	case "apr":
	case "april":
		$mon="04";
	break;

	case "maj":
	case "may":
		$mon="05";
	break;

	case "jun":
	case "juni":
	case "june":
		$mon="06";
	break;

	case "jul":
	case "juli":
	case "july":
		$mon="07";
	break;

	case "aug":
	case "august":
		$mon="08";
	break;

	case "sep":
	case "september":
		$mon="09";
	break;

	case "okt":
	case "oct":
	case "oktober":
	case "october":
		$mon="10";
	break;

	case "nov":
	case "november":
		$mon="11";
	break;

	case "dec":
	case "december":
		$mon="12";
	break;

	default:
		$mon=date("n");
	break;
}
// echo "mon: ".$mon;

$year= trim(substr($d2, strcspn($d2, "0123456789")));
// echo "year: ".$year; exit;

switch ( strtolower($tz) )
{
	case 'z':
		$tz_sec= 0;	// antal sekunder forskydning.
	break;
	
	case 'a':
		$tz_sec= 3600;	// -1 hour
	break;
	
	case 'b':
		$tz_sec= 7200;	// -2 hour
	break;
	
	case 'c':
		$tz_sec= 10800;	// -3 hour
	break;
	
	case 'd':
		$tz_sec= 14400;	// -4 hour
	break;
	
	case 'e':
		$tz_sec= 18000;	// -5 hour
	break;
	
	case 'f':
		$tz_sec= 21600;	// -6 hour
	break;
	
	case 'g':
		$tz_sec= 25200;	// -7 hour
	break;
	
	case 'h':
		$tz_sec= 28800;	// -8 hour
	break;
	
	case 'i':
		$tz_sec= 32400;	// -9 hour
	break;
	
	case 'j':
		$tz_sec= 32400;	// -9 hour
	break;
	
	case 'k':
		$tz_sec= 36000;	// -10 hour
	break;
	
	case 'l':
		$tz_sec= 39600;	// -11 hour
	break;
	
	case 'm':
		$tz_sec= 43200;	// -12 hour
	break;
	
	case 'n':
		$tz_sec= -3600;	// 1 hour
	break;
	
	case 'o':
		$tz_sec= -7200;	// 2 hour
	break;
	
	case 'p':
		$tz_sec= -10800;	// 3 hour
	break;
	
	case 'q':
		$tz_sec= -14400;	// 4 hour
	break;
	
	case 'r':
		$tz_sec= -18000;	// 5 hour
	break;
	
	case 's':
		$tz_sec= -21600;	// 6 hour
	break;
	
	case 't':
		$tz_sec= -25200;	// 7 hour
	break;
	
	case 'u':
		$tz_sec= -28800;	// 8 hour
	break;
	
	case 'v':
		$tz_sec= -32400;	// 9 hour
	break;
	
	case 'w':
		$tz_sec= -36000;	// 10 hour
	break;
	
	case 'x':
		$tz_sec= -39600;	// 11 hour
	break;
	
	case 'y':
		$tz_sec= -43200;	// 12 hour
	break;
	
	default:
		$tz_sec= 0;
	break;
}

$tst= mktime($hour, $min, 0, $mon, $day, $year);	// convert to unix-time.
$tst= $tst-$tz_sec;					// correct for timezone.
$time_st= date( "Y-n-j H:i:s", $tst);			// format as needed again.

// echo "TIMESTAMP: ".$time_st; exit;
return $time_st;
}


//***************************************************************
//								*
//		t_dtg();					*
//								*
// Makes a DTG based on NOW() and timezone info in journal.ini.	*
// Output is a string containing the complete DTG ready to be	*
// used in input field.						*
//								*
//**************************************************************/


function t_dtg( $tz)
{
	
	switch ( $tz )
	{
		case 'UTC':
			$tst= time();
			$tst+= date("Z");
			$dtg= date("dHi", $tst)."Z".date("My", $tst);
		break;
		
		
				
		default:
		case 'local':
			$dtg= date("dHi").(date("I")?"B":"A").date("My");
		break;
	}
	return $dtg;
}



//***************************************************************
//								*
//		dtg_diff($ts1, $ts2);			*
//								*
// 	$ts1 = "2007-01-05 10:30:45";      			*
//	$ts2 = "2007-01-06 10:31:46";				*
//	echo dtg_diff($ts1, $ts2); In minutes($ts1 - $ts2).*
//								*
//**************************************************************/



{
	function dtg_diff($ts1, $ts2) {

	  /*
	  $ts1 = "2007-01-05 10:30:45";
	  $ts2 = "2007-01-06 10:31:46";
	  echo dtg_diff_as_text($ts1, $ts2);
	  */

	  $ts1 = strtotime($ts1);
	  $ts2 = strtotime($ts2);
	  $diff = abs($ts1-$ts2);

	  return $diff/60;

	}
}



//***************************************************************
//								*
//		dtg_strip_blank($dtg);				*
//								*
//	Removes all blanks in a DTG.				*
//								*
// 	"14 11 02 okt 07"; =>		    			*
//	"141102okt07";						*
//								*
//**************************************************************/



{
	function dtg_strip_blank($dtg) {

	  /*
	  Removes all blanks in a DTG.
	  */
	$dtg= str_replace(" ", "", $dtg);
/// echo "---".$dtg."---\n";
	  return $dtg;

	}
}
?>