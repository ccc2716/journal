<?php
/****************************************************************
//  File: VisEnPersonel.php					*
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
//  to draw the "Vis En Personel" page in the system		*
//	This function is looking for the following input:	*
//  $_REQUEST["jour_id"]						*
//								*
//**************************************************************/


function VisEnPersonel()
{
	
	top();
?>

	<div class="midt">
<?php
// Lav listen med brug af MySql udtræk.


// Performing SQL query
$query = 'SELECT funktion, grad, initialer, navn, enhed, email, MA, phone FROM personel WHERE personel.id='.$_REQUEST["jour_id"];
// echo $query;
$result = DebugQuery($query);
$line = mysql_fetch_row($result);


printf("<P><FONT class=felt>Funktion:</FONT> %s<br>\n", stripcslashes($line[0]));

printf("<br><FONT class=felt>Grad:</FONT> %s<br>\n", stripcslashes($line[1]));

printf("<br><FONT class=felt>Initialer:</FONT> %s<br>\n", stripcslashes($line[2]));

printf("<br><br><FONT class=felt>Navn:</FONT> %s<br>\n", stripcslashes($line[3]));

printf("<br><br><FONT class=felt>MA-nummer:</FONT> %s<br>\n", stripcslashes($line[6]));

printf("<br><br><FONT class=felt>Enhed:</FONT> %s<br>\n", stripcslashes($line[4]));

printf("<br><br><FONT class=felt>email:</FONT> %s</P>\n", stripcslashes($line[5]));

printf("<br><br><FONT class=felt>telefon:</FONT> %s</P>\n", stripcslashes($line[7]));

// Free resultset
mysql_free_result($result);
?>
		</div>
<?php
bottom();
}
?>
