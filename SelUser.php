<?php
/****************************************************************
//  File: SelUser.php						*
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
//  to draw the "Vælg bruger og enhed" page in the system	*
//	$Id$							*
//								*
//**************************************************************/


function SelUser()
{
	global $TemaArray;
	
if ($_REQUEST["DbSelected"] == "0")	// No selection was made, send him back to do the selection.
{
	/* Redirect to main page so reload will not insert another copy of signal. */
	$host  = $_SERVER['HTTP_HOST'];
	$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'main.php?M_action=Error&error=NoSelect&next=SelDb';
	header("Location: http://$host$uri/$extra");
	print_footer(false);
	exit;
}
else
{
	$_SESSION["DbSelected"]= $_REQUEST["DbSelected"];
	
	top();
?>

	<div class="midt">
<?php
// Lav listen med brug af MySql udtr¾k.



// Performing SQL query
$query = 'SELECT personel.id, personel.navn FROM personel ORDER BY personel.navn';
$result = DebugQuery($query);

?>

<FORM action="main.php" method="get" onsubmit="mySetSize()">
   <P>
<INPUT Type=HIDDEN NAME= "M_action" VALUE= "SetStartCond">
<INPUT Type=HIDDEN NAME="M_height" VALUE="720">


<SELECT name="User">
      <OPTION selected value="0">-V&aelig;lg dig selv fra listen-</OPTION>

<?php
// Printing results in HTML

while ($line = mysql_fetch_row($result)) {

 	
      echo "<OPTION value=\"$line[0]\">$line[1]</OPTION>\n";   
  
}

// Free resultset
mysql_free_result($result);

?>     

   </SELECT>
<br>
<br>
<br>

<?php
// Performing SQL query
$query = 'SELECT id, navn FROM enheder ORDER BY navn';
$result = DebugQuery($query);
?>

<SELECT name="EgenEnhed">
      <OPTION selected value="0">-V&aelig;lg journalf&oslash;rende enhed fra listen-</OPTION>

<?php
// Printing results in HTML

while ($line = mysql_fetch_row($result)) {

 	
      echo "<OPTION value=\"$line[0]\">$line[1]</OPTION>\n";   
  
}

// Free resultset
mysql_free_result($result);
?>
   </SELECT>
<br>
<br>
<br>Indtast dit password:
<br>
<INPUT type= "password" NAME= "passwd">
<br>
<br>
<INPUT type="submit" value="Forts&aelig;t">
   </P>
</FORM>
		</div>
<?php
bottom(false);
}
}
?>
