<?php
/****************************************************************
//  File: index.php						*
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
//  This file holds the start page for the system		*
//								*
//								*
//								*
//**************************************************************/


require_once("footer.php");
require_once("network_functions.php");
require_once("top.php");
require_once("bottom.php");

$StartTimer=  microtime(true);
$InitArray = parse_ini_file("journal.ini", true);
$TemaArray= $InitArray["Tema"];

top();
?>
	<div class="midt">

		<P class=header><FONT class=action>Betingelser for brug.</FONT></P>
		<P>Dette program stilles til gratis til r&aring;dighed for  journalf&oslash;ring. Programmet er fri software, 
		udgivet under GPLv3 licensen.</P><P>Se n&aelig;rmere under <A class=manual HREF="licens.html" target="_blank">licens.</A></P>
		<P>Brug af programmet foreg&aring;r p&aring; brugerens eget ansvar; data indtastet i systemet tilh&oslash;rer brugeren.</P>
		<A class=command HREF="main.php?M_action=SelDb">Jeg accepterer betingelserne og starter programmet</A>
	</div>

<?php
bottom(false);
