<?php
/****************************************************************
//  File: VisPersonel.php					*
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
//  to draw the "Vis Personel" page in the system		*
//	$Id$							*
//								*
//**************************************************************/


function VisPersonel()
{
$KanRette= ($_SESSION["userObj"]->userMayWritePersonel());

$l_no= 0;

top();
?>

	<div class="midt">
<?php
// Lav listen med brug af MySql udtr¾k.


// Performing SQL query
$query = 'SELECT funktion, grad, initialer, navn, MA, enhed, email, id, admin, phone FROM personel ORDER BY navn';
// echo $query;
$result = DebugQuery($query);

echo "<table class=\"felt2\">";
$line_format= "%-8s %-5s %-5s  %-30.30s  %-6s  %-30s %-15s  %-10s %s";

printf("<TR><TH>%8s<TH>%5s<TH>%5s<TH>%30s<TH>%6s<TH>%30s<TH>%15s<TH>%10s<TH>%s\n"
	, "Funktion", "Grad", "Initialer", "Navn", "<ACRONYM TITLE=\"MedArbejdernummer\">MA</ACRONYM>", "email", "Telefon", "Enhed", "<ACRONYM TITLE=\"Admin, Personel, Enheder, Write, System\" >admin</ACRONYM>");
// Printing results in HTML

$Target= $KanRette?"RetPersonel":"VisEnPersonel";

while ($line = mysql_fetch_row($result)) {
	if ($l_no++ % 2)
		$line_class= "odd";
	else
		$line_class= "even";

	printf("<TR class=%s><TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%8s</A>"// funktion
		."<TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%5s</A>"		// grad
		."<TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%5s</A>"		// init
		."<TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%30s</A>"		// navn
		."<TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%6s</A>"		// MA
		."<TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%30s</A>"		// email
		."<TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%15s</A>"		// telefon
		."<TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%10s</A>"		// enhed
		."<TD class=sig><A class=norma HREF=\"main.php?M_action=".$Target."&amp;jour_id=%s\">%5s</A>"		// admin
		."\n",
		$line_class, $line[7], $line[0]
		, $line[7], $line[1]
		, $line[7], $line[2]
		, $line[7], html_entity_decode($line[3])
		, $line[7], $line[4]
		, $line[7], $line[6]
		, $line[7], $line[9]
		, $line[7], $line[5]
		, $line[7], (($line[8] & RIGHTS_Admin)?"A":"-").(($line[8] & RIGHTS_Personel)?"P":"-")
			.(($line[8] & RIGHTS_Enheder)?"E":"-").(($line[8] & RIGHTS_ReadWrite)?"W":"-")
			.(($line[8] & RIGHTS_System)?"S":"-"));
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
