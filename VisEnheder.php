<?php
/****************************************************************
//  File: VisEnheder.php					*
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
//  to draw the "Vis Enheder" page in the system		*
//	$Id$							*
//								*
//**************************************************************/



function VisEnheder()
{
$KanRette= ($_SESSION["userObj"]->userMayWriteEnhed());

$l_no= 0;

top();
?>

	<div class="midt">
<?php
// Lav listen med brug af MySql udtr¾k.


// Performing SQL query
$query = 'SELECT navn, kaldetal, tlf, email, id FROM enheder ORDER BY navn';
// echo $query;
$result = DebugQuery($query);


echo "<table class=\"felt2\">";

$Target= $KanRette?"RetEnhed":"VisEnEnhed";

printf("<TR><TH>%30s<TH>%8s<TH>%16s<TH>%40s<br>\n", "Navn", "Kaldetal", "Telefon", "email");
// Printing results in HTML

while ($line = mysql_fetch_row($result)) {
	if ($l_no++ % 2)
		$line_class= "odd";
	else
		$line_class= "even";

	printf("<TR class=%s><TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%s</A>"
		."<TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%s</A>"
		."<TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%s</A>"
		."<TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%s</A>"
		."\n"
			, $line_class, stripcslashes($line[4]), stripcslashes($line[0])
			, stripcslashes($line[4]), stripcslashes($line[1])
			, stripcslashes($line[4]), stripcslashes($line[2])
			, stripcslashes($line[4]), stripcslashes($line[3]));
	}

// Free resultset
mysql_free_result($result);
echo "</table>";

?>

</div>

<?php
bottom();

}
?>
