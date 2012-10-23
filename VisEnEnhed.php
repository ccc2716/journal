<?php
/****************************************************************
//  File: VisEnEnhed.php					*
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
//  to draw the "Vis En Enhed" page in the system		*
//	This function is looking for the following input:	*
//  $_REQUEST["jour_id"]						*
//**************************************************************/


function VisEnEnhed()
{
	
	top();
?>

	<div class="midt">
<?php
// Lav listen med brug af MySql udtr¾k.


// Performing SQL query
$query = 'SELECT navn, kaldetal, tlf, email FROM enheder WHERE enheder.id='.$_REQUEST["jour_id"];
// echo $query;
$result = DebugQuery($query);
$line = mysql_fetch_row($result);


printf("<P><FONT class=felt>Navn:</FONT> %s<br>\n", stripcslashes($line[0]));

printf("<br><br><FONT class=felt>Kaldetal:</FONT> %s<br>\n", stripcslashes($line[1]));

printf("<br><br><FONT class=felt>Telefonnummer:</FONT> %s<br>\n", stripcslashes($line[2]));

printf("<br><br><FONT class=felt>Email:</FONT> %s</P>\n", stripcslashes($line[3]));
?>


		</div>
<?php
bottom();
}
?>
