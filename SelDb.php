<?php
/****************************************************************
//  File: SelDb.php						*
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
//  to draw the "Vælg Journal" page in the system		*
//	$Id$							*
//								*
//**************************************************************/



function SelDb()
{
	global $TemaArray;

// Start with a new beginning,, keep only direction.
if(isset($_SESSION["DbSelected"])){
	unset($_SESSION["DbSelected"]);
	unset($_SESSION["User"]);
}



	top();
?>

	<div class="midt">
<?php
// Lav listen med brug af MySql udtræk.

// Performing SQL query
$query = 'SHOW DATABASES LIKE "'.JournalName.'%"';
$result = DebugQuery($query);

?>
<FORM action="main.php" method="get" onsubmit="mySetSize()">
   <P>
<INPUT Type=HIDDEN NAME= "M_action" VALUE= "SelUser">
<INPUT Type=HIDDEN NAME="M_height" VALUE="720">

<SELECT name="DbSelected">
      <OPTION selected value="0">-V&aelig;lg en journal fra listen-</OPTION>

<?php
// Printing results in HTML

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
 
   foreach ($line as $col_value) {
       echo "<OPTION value=\"$col_value\">$col_value</OPTION>\n";
   }
  
}

// Free resultset
mysql_free_result($result);
?>     

   </SELECT>
<br>
<br>
<br>
<INPUT type="submit" value="Forts&aelig;t.">
   </P>
</FORM>
		</div>
<?php
bottom(false);
}
?>
