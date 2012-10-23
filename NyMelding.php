<?php
/****************************************************************
//  File: NyMelding.php						*
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
//  to draw the "Ny Melding" page in the system			*
//	$Id$							*
//								*
*  This function is looking for the following input:		*
*  $InitArray["time"]["TimeZone"],				*
*  $_SESSION["EgenEnhed"]					*
//**************************************************************/


require_once("TimeFunctions.php");

function drawnuc()	//print form
{
?>
<INPUT Type=HIDDEN NAME="nbc" VALUE="nuc">
<table border= "2">
<tr>
<td colspan= "6"; align= "center">
	CBRN-NUC
</td>
</tr>
<tr>
<td>
	&nbsp;
</td>
<td>1</td>
<td>2</td>
<td>3</td>
<td>
	Data
</td>
<td>
	Forklaring
</td>
</tr>
<tr><td>
		A
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		x
	</td>
	<td>
		x
	</td>
	<td>
		<input class="hilight" tabindex="60" type="text" name="NucA" value="">
	</td>
<td>
	Identifikationsnummer (Anvendes kun ved CBRN - 2 &amp; 3)
</td>
</tr>
<tr><td>
		B
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="61" type="text" name="NucB" value="">
	</td>
<td>
	Standplads (Observat&oslash;r)
</td>
</tr>
<tr><td>
		C
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="62" type="text" name="NucC" value="">
	</td>
<td>
	Retning til eksplosionen
</td>
</tr>
<tr><td>
		D
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="63" type="text" name="NucD" value="">
	</td>
<td>
	Tidspunkt for eksplosionen
</td>
</tr>
<tr><td>
		H
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="64" type="text" name="NucH" value="">
	</td>
<td>
	H&oslash;jde (Luft, Overflade, Ukendt)
</td>
</tr>
<tr><td>
		J
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="65" type="text" name="NucJ" value="">
	</td>
<td>
	Glimt til lyd (op til 300 sekunder)
</td>
</tr>
<tr><td>
		L
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="66" type="text" name="NucL" value="">
	</td>
<td>
	Skyens bredde (m&aring;les 5 minutter efter eksplosionen; vinkel)
</td>
</tr>
<tr><td>
		M
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		Overkant:<input class="hilight" tabindex="67" type="text" name="NucM1" value=""><br>
		Underkant:<input class="hilight" tabindex="68" type="text" name="NucM2" value="">
	</td>
<td>
	Vinkel til skyen (m&aring;les 10 minutter efter eksplosionen)
</td>
</tr>
</table>
Bem&aelig;rkninger:<br>
<textarea class="hilight" tabindex="69" name="NucZB" rows="4" cols="40"></textarea>
<?php
}



function drawchem()	//print form
{
?>
<INPUT Type=HIDDEN NAME="nbc" VALUE="chem">
<table border= "2">
<tr>
<td colspan= "6"; align= "center">
	CBRN-CHEM
</td>
</tr>
<tr>
<td>
	&nbsp;
</td>
<td>1</td>
<td>2</td>
<td>3</td>
<td>
	Data
</td>
<td>
	Forklaring
</td>
</tr>
<tr><td>
		A
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		x
	</td>
	<td>
		x
	</td>
	<td>
		<input class="hilight" tabindex="60" type="text" name="chemA" value="">
	</td>
<td>
	Identifikationsnummer (Anvendes kun ved CBRN - 2 &amp; 3)
</td>
</tr>
<tr><td>
		B
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="61" type="text" name="chemB" value="">
	</td>
<td>
	Standplads (Observat&oslash;r)
</td>
</tr>
<tr><td>
		C
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="62" type="text" name="chemC" value="">
	</td>
<td>
	Retning til angrebssted
</td>
</tr>
<tr><td>
		D
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="63" type="text" name="chemD" value="">
	</td>
<td>
	Tidspunkt for angrebets start
</td>
</tr>
<tr><td>
		E
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="64" type="text" name="chemE" value="">
	</td>
<td>
	Tidspunkt for angrebets oph&oslash;r
</td>
</tr>
<tr><td>
		F
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="65" type="text" name="chemF" value="">
	</td>
<td>
	Angrebssted (stednavn/UTM-koordinat samt sk&oslash;nnet/konstateret)
</td>
</tr>
<tr><td>
		G
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="66" type="text" name="chemG" value="">
	</td>
<td>
	Fremf&oslash;ringsm&aring;de/udl&aelig;gningsm&aring;de
</td>
</tr>
<tr><td>
		H
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="67" type="text" name="chemH" value="">
	</td>
<td>
	Kampstoftype (evt. farvereaktion p&aring; sporepapir; udl&aelig;gningsh&oslash;jde: luft/overflade)
</td>
</tr>
<tr><td>
		I
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="68" type="text" name="chemI" value="">
	</td>
<td>
	Antal fly, granater el. lign.
</td>
</tr>
<tr><td>
		Y
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		Medvindsretning:<input class="hilight" tabindex="69" type="text" name="chemY1" value=""><br>
		hastighed:<input class="hilight" tabindex="70" type="text" name="chemY2" value="">
	</td>
<td>
	Medvindsretning og hastighed
</td>
</tr>
<tr><td>
		ZA
	</td>
	<td>
		x
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
	<td>
		<input class="hilight" tabindex="71" type="text" name="chemZA" value="">
	</td>
<td>
	Vejrforhold p&aring; angrebstidspunktet/-stedet (stab. forhold, temp., luftfugt., vejrlig, skyd&aelig;kke)
</td>
</tr>
</table>
Bem&aelig;rkninger:<br>
<textarea class="hilight" tabindex="72" name="chemZB" rows="4" cols="40"></textarea>
<?php
}


function NyMelding()
{
global $InitArray;

$egenenhed= $_SESSION["userObj"]->userEgenEnhed();

top("onload=\"fillall()\"");

echo('<div class="midt">'."\n");

if ( ($_SESSION["userObj"]->userMayWrite()) && (!($_SESSION["userObj"]->userRightsCheck(RIGHTS_System))))		// sysadmin is not allowed.
{
?>
<script type="text/javascript">


function kopi(pArrayList, id, idinp) {
	var l= pArrayList.length;
	var txt2 = new String("");
	var n= 0;
	var m= 0;

	var x=document.getElementById(idinp).value;

	var patt= new RegExp( x, "i");

	document.getElementById(id).style.background= "#FFFFD0";
	
	for (i= 1; i< l; i++)
	{
		if (patt.test(pArrayList[i]))
		{
			txt1= pArrayList[i];
			txt2= txt2.concat( "<OPTION id=\""+id+i+"\" value=\""+i+"\">"+txt1+"</OPTION>" );
			n= n+1;
			m= i;
		}
	}

	document.getElementById(id).innerHTML= txt2;

	if (n == 1)
	{
		document.getElementById(id+m).selected= true;
		// document.getElementById(id).style.color= "#FF1E65";
		document.getElementById(id).style.background= "#18DE00";
	}
	
	if (n == 0)
	{
		document.getElementById(id).style.background= "#DE3641";
		alert("Intet passer.");
	}
}

function fill(pArrayList, id)
{
	var l= pArrayList.length;
	var txt2 = new String("");

	for (i= 1; i< l; i++)
	{
		txt1= pArrayList[i];
		txt2= txt2.concat( "<OPTION id=\""+id+i+"\" value=\""+i+"\">"+txt1+"</OPTION>");
	}
	document.getElementById(id).innerHTML= txt2;
}

function fillall ()
{
	fill(enheder,"idAfs");
	fill(enheder, "idModt");
	fill(enheder, "idInfo");
}
</script>


<FORM action="main.php" method="get" onsubmit="mySetSize()">
<INPUT Type=HIDDEN NAME="M_action" VALUE="GemNyMelding">
<INPUT Type=HIDDEN NAME="M_height" VALUE="720">
<TABLE>
<TR>
<TD>
	<A class=command HREF="main.php?M_action=NyCBRNnuc">CBRN-nuc-melding.</A>
</TD>
<TD>
	<A class=command HREF="main.php?M_action=NyCBRNchem">CBRN-chem-melding.</A>
</TD>
</TR>
<TR>
<TD>	
<FONT class=felt>DTG:</FONT><br>
<INPUT class="hilight" tabindex="10" Type=text NAME="dtg" size="16" value=<?php echo t_dtg($InitArray["time"]["TimeZone"]);?>>
</TD>
<TD>
<FONT class=felt>Fortrinsret:</FONT><br>
<SELECT tabindex="20" name= "prio">
<OPTION selected value= "0">Rutine</OPTION>
<OPTION value= "1">Il</OPTION>
<OPTION value= "2">Operations Il</OPTION>
<OPTION value= "3">ALARM</OPTION>
</SELECT>

</TD>
</TR>
<TR>
<TD>
<?php
// Lav listen med brug af MySql udtræk.


// Performing SQL query
$query = 'SELECT enheder.id, enheder.kaldetal, enheder.navn FROM enheder ORDER BY enheder.kaldetal';
$result = DebugQuery($query);

// Printing results in HTML (script)
echo('<script type="text/javascript" charset="utf-8">'."\n");
echo('var enheder=new Array();'."\n");

while ($line = mysql_fetch_row($result)) {
	// printf("<OPTION %s value=\"%s\">%5s - %20s</OPTION>\n", ($egenenhed==$line[0]?"selected":""), $line[0], $line[1], $line[2]);
	
	printf('enheder[%s]= "%s - %s";'."\n", $line[0], $line[1], $line[2]);
	
	}
echo("</script>\n");


// Free resultset
mysql_free_result($result);
?>
<FONT class=felt>Afsender:</FONT><br>
<input tabindex="30" type="text" name="inp-A" value="" id="idAfsSel" onkeyup="kopi(enheder, 'idAfs', 'idAfsSel')" >
<br>
<SELECT id="idAfs" class="hilight" size="6" name="afsender">
  </SELECT>

</TD>
<TD>
<FONT class=felt>Modtager(e):</FONT><br>
<input tabindex="40" type="text" name="inp-M" value="" id="idModtSel" onkeyup="kopi(enheder, 'idModt', 'idModtSel')" >
<br>
<SELECT id="idModt" class="hilight" multiple size="6" name="modtager">

</SELECT>
</TD>
<TD>
<FONT class=felt>Info:</FONT><br>
<input tabindex="50" type="text" name="inp-I" value="" id="idInfoSel" onkeyup="kopi(enheder, 'idInfo', 'idInfoSel')" >
<br>
<SELECT id="idInfo" class="hilight" multiple size="6" name="info">

</SELECT>
</TD>
</TR>
</TABLE>


<br><br>
<FONT class=felt>Overskrift:</FONT><br>
<INPUT tabindex="60" class="hilight" Type="text" NAME="overskrift" size="64" Value="">
<br><br>
<FONT class=felt>Tekst:</FONT><br>
<TEXTAREA tabindex="70" class="hilight" name="kort_text" rows="3" cols="60">
</TEXTAREA>
<br><br>
<?php VisRetLegitimation(0); ?>
<br><br>
<FONT class=felt>Signalmiddel:</FONT><br>
<SELECT tabindex="80" name="signalmiddel">
<OPTION>RDO</OPTION>
<OPTION>SINE</OPTION>
<OPTION>TLF</OPTION>
<OPTION>ORD</OPTION>
<OPTION>Fiin</OPTION>
<OPTION>email</OPTION>
<OPTION>Andet</OPTION>
<OPTION>Lokalt</OPTION>
   </SELECT>
<br><br><br>
<INPUT tabindex="90" type="submit" value="Gem ny melding">
</FORM>
<?php
}
else
{
?>
<P>Ny melding kan ikke oprettes uden skriveadgang.</P>
<?php
}
?>
		</div>
<?php
bottom();
}


function NyCBRN($type= "error")
{
global $InitArray;

$egenenhed= $_SESSION["userObj"]->userEgenEnhed();

top("onload=\"fillall()\"");

echo('<div class="midt">'."\n");

if ( ($_SESSION["userObj"]->userMayWrite()) && (!($_SESSION["userObj"]->userRightsCheck(RIGHTS_System))))		// sysadmin is not allowed.
{
?>
<script type="text/javascript">


function kopi(pArrayList, id, idinp) {
	var l= pArrayList.length;
	var txt2 = new String("");
	var n= 0;
	var m= 0;

	var x=document.getElementById(idinp).value;

	var patt= new RegExp( x, "i");

	document.getElementById(id).style.background= "#FFFFD0";

	for (i= 1; i< l; i++)
	{
		if (patt.test(pArrayList[i]))
		{
			txt1= pArrayList[i];
			txt2= txt2.concat( "<OPTION id=\""+id+i+"\" value=\""+i+"\">"+txt1+"</OPTION>" );
			n= n+1;
			m= i;
		}
	}

	document.getElementById(id).innerHTML= txt2;

	if (n == 1)
	{
		document.getElementById(id+m).selected= true;
		// document.getElementById(id).style.color= "#FF1E65";
		document.getElementById(id).style.background= "#18DE00";
	}

	if (n == 0)
	{
		document.getElementById(id).style.background= "#DE3641";
		alert("Intet passer.");
	}
}

function fill(pArrayList, id)
{
	var l= pArrayList.length;
	var txt2 = new String("");

	for (i= 1; i< l; i++)
	{
		txt1= pArrayList[i];
		txt2= txt2.concat( "<OPTION id=\""+id+i+"\" value=\""+i+"\">"+txt1+"</OPTION>");
	}
	document.getElementById(id).innerHTML= txt2;
}

function fillall ()
{
	fill(enheder,"idAfs");
	fill(enheder, "idModt");
	fill(enheder, "idInfo");
}
</script>


<FORM action="main.php" method="get" onsubmit="mySetSize()">
<INPUT Type=HIDDEN NAME="M_action" VALUE="GemNyCBRN">
<INPUT Type=HIDDEN NAME="M_height" VALUE="720">
<TABLE>
<TR>
<TD>	
<FONT class=felt>DTG:</FONT><br>
<INPUT class="hilight" tabindex="10" Type=text NAME="dtg" size="16" value=<?php echo t_dtg($InitArray["time"]["TimeZone"]);?>>
</TD>
<TD>
<FONT class=felt>Fortrinsret:</FONT><br>
<SELECT tabindex="20" name= "prio">
<OPTION selected value= "0">Rutine</OPTION>
<OPTION value= "1">Il</OPTION>
<OPTION value= "2">Operations Il</OPTION>
<OPTION value= "3">ALARM</OPTION>
</SELECT>

</TD>
</TR>
<TR>
<TD>
<?php
// Lav listen med brug af MySql udtræk.


// Performing SQL query
$query = 'SELECT enheder.id, enheder.kaldetal, enheder.navn FROM enheder ORDER BY enheder.kaldetal';
$result = DebugQuery($query);

// Printing results in HTML (script)
echo('<script type="text/javascript" charset="utf-8">'."\n");
echo('var enheder=new Array();'."\n");

while ($line = mysql_fetch_row($result)) {
	// printf("<OPTION %s value=\"%s\">%5s - %20s</OPTION>\n", ($egenenhed==$line[0]?"selected":""), $line[0], $line[1], $line[2]);
	
	printf('enheder[%s]= "%s - %s";'."\n", $line[0], $line[1], $line[2]);
	
	}
echo("</script>\n");


// Free resultset
mysql_free_result($result);
?>

	<FONT class=felt>Afsender:</FONT><br>
	<input tabindex="30" type="text" name="inp-A" value="" id="idAfsSel" onkeyup="kopi(enheder, 'idAfs', 'idAfsSel')" >
	<br>
	<SELECT id="idAfs" class="hilight" size="6" name="afsender">
	  </SELECT>

	</TD>
	<TD>
	<FONT class=felt>Modtager(e):</FONT><br>
	<input tabindex="40" type="text" name="inp-M" value="" id="idModtSel" onkeyup="kopi(enheder, 'idModt', 'idModtSel')" >
	<br>
	<SELECT id="idModt" class="hilight" multiple size="6" name="modtager">

	</SELECT>
	</TD>
	<TD>
	<FONT class=felt>Info:</FONT><br>
	<input tabindex="50" type="text" name="inp-I" value="" id="idInfoSel" onkeyup="kopi(enheder, 'idInfo', 'idInfoSel')" >
	<br>
	<SELECT id="idInfo" class="hilight" multiple size="6" name="info">

	</SELECT>
	</TD>
	</TR>
	</TABLE>


<br><br>
<?php
// Draw the specific details to capture the NUC or CHEM signals.

switch($type){
case "nuc":
	drawnuc();	//print form
	break;

case "chem":
	drawchem();	//print form
	break;

case "error":
default:
	printf("An error has happened in NYCBRN - %s.\nPlease contact administrator.\n", $type);
	break;
}


?>
<br><br>
<?php VisRetLegitimation(0); ?>
<br><br>
<FONT class=felt>Signalmiddel:</FONT><br>
<SELECT tabindex="80" name="signalmiddel">
<OPTION>RDO</OPTION>
<OPTION>SINE</OPTION>
<OPTION>TLF</OPTION>
<OPTION>ORD</OPTION>
<OPTION>Fiin</OPTION>
<OPTION>email</OPTION>
<OPTION>Andet</OPTION>
<OPTION>Lokalt</OPTION>
   </SELECT>
<br><br><br>
<INPUT type="submit" tabindex="90" value="Gem ny melding">
</FORM>
<?php
}
else
{
?>
<P>Ny melding kan ikke oprettes uden skriveadgang.</P>
<?php
}
?>
		</div>
<?php
bottom();
}



/****************************************************************
*								*
*  This function is used					*
*  to save a new signal in the system				*
*								*
*  This function is looking for the following inputs:		*
*  $link, $_SESSION["User"],					*
*  $_SERVER["QUERY_STRING"]					*
****************************************************************/


function GemNyMelding()
{

global $link;

$mo= 0;
$inf= 0;


	if (!($_SESSION["userObj"]->userIsLegal()) || ($_SESSION["userObj"]->userRightsCheck(RIGHTS_System))) {
	/* Not Legal user; log him out, he must not make any changes. */
		$host  = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'main.php?M_action=LogUd';
		header("Location: http://$host$uri/$extra");
		print_footer(false);
		exit;	
	}



// Saml data sammen i variable.

$pairs= explode("&", $_SERVER["QUERY_STRING"]);

reset($pairs);
while (list($key, $val) = each($pairs)) {
	$parm= explode("=", $val);
	switch ($parm[0]){
		case "modtager":
			$modt[$mo++]= $parm[1];
		break;

		case "info":
			$info[$inf++]= $parm[1];
		break;

		case "dtg":
			$dtg= dtg_strip_blank($_REQUEST["dtg"]);
			$time_stamp= t_stamp($dtg);
		break;

		case "afsender":
			$afsenderID= $_REQUEST["afsender"];
		break;

		case "overskrift":
			$overskrift= $_REQUEST["overskrift"];
			// echo "overskrift.".$overskrift."<br>";
		break;

		case "kort_text":
			$kort_text= $_REQUEST["kort_text"];
			// echo "kort_text.".$kort_text."<br>";
		break;

		case "signalmiddel":
			$signalmiddel= $_REQUEST["signalmiddel"];
			// echo "Signalmiddel.".$signalmiddel."<br>";
		break;

		case "prio":
			$prio= $_REQUEST["prio"];
		break;

		case "legim_Q":
			$legim_Q= $_REQUEST["legim_Q"];
		break;

		case "legim_svar":
			$legim_svar= $_REQUEST["legim_svar"];
		break;

		default:	// Error handling!
		break;
		}
	}

// Konstruer alle nødvendige queries og fyr dem af.

if ( $_SESSION["userObj"]->userMayWrite() && ($_SESSION["userObj"]->userIsLegal()))		// sysadmin is not allowed.
{

if ($overskrift == "") $overskrift= str_replace("\r\n", " ", substr($kort_text, 0, 60));

$overskrift= mysql_real_escape_string(htmlentities($overskrift), $link);
$kort_text= mysql_real_escape_string(htmlentities($kort_text), $link);

// Special treatment for $legim_Q:
if(strlen($legim_Q)==0)
	{
		$legim_Q_string= 'NULL';
	}
else
	{
		$legim_Q_string= "'".$legim_Q."'";
	}

$user= $_SESSION["userObj"]->userNumber();

$main_query=	"INSERT INTO"
		." signaler(id"
		.", afsenderID"
		.", dtg"
		.", dtg_stamp"
		.", overskrift"
		.", kort_text"
		.", keyopID"
		.", indtast_tid"
		.", priority"
		.", legim_Q"
		.", legim_svar"
		.", signalmiddel) "
		."VALUES (NULL, '$afsenderID', '$dtg', '$time_stamp', '$overskrift', '$kort_text', '$user', NULL, '$prio', $legim_Q_string, '$legim_svar',  '$signalmiddel')";

// Performing SQL query
$result = DebugQuery($main_query);
$s_id= mysql_insert_id();

// Link alle modtagere i signalet.
if ($mo != 0)
	foreach ($modt as $m)
		{
			$link_query= "INSERT INTO modtagere(signalID, enhederID) VALUES ($s_id, $m)";
			// echo $link_query."<br>";
			// Performing SQL query
			$result = DebugQuery($link_query);
		}

// Link alle info-modtagere i signalet.
if ($inf != 0)
	foreach ($info as $m)
		{
			$link_query= "INSERT INTO info(signalID, enhederID) VALUES ($s_id, $m)";
			// echo $link_query."<br>";
			// Performing SQL query
			$result = DebugQuery($link_query);
		}
// exit;
}

/* Redirect to main page so reload will not insert another copy of signal. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'main.php?M_action=VisJournal';
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;
}



/****************************************************************
*								*
*  This function is used					*
*  to save a new CBRN signal in the system			*
*								*
*  This function is looking for the following inputs:		*
*  $link, $_SESSION["User"],					*
*  $_SERVER["QUERY_STRING"]					*
****************************************************************/


function GemNyCBRN()
{

global $link;

$mo= 0;
$inf= 0;



	if (!($_SESSION["userObj"]->userIsLegal()) || ($_SESSION["userObj"]->userRightsCheck(RIGHTS_System))) {
		/* Not Legal user; log him out, he must not make any changes. */
		$host  = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'main.php?M_action=LogUd';
		header("Location: http://$host$uri/$extra");
		print_footer(false);
		exit;	
	}



// Saml data sammen i variable.

$pairs= explode("&", $_SERVER["QUERY_STRING"]);

reset($pairs);
while (list($key, $val) = each($pairs)) {
	$parm= explode("=", $val);
	switch ($parm[0]){
		case "modtager":
			$modt[$mo++]= $parm[1];
		break;

		case "info":
			$info[$inf++]= $parm[1];
		break;

		case "dtg":
			$dtg= dtg_strip_blank($_REQUEST["dtg"]);
			$time_stamp= t_stamp($dtg);
		break;

		case "afsender":
			$afsenderID= $_REQUEST["afsender"];
		break;

		case "nbc":
			$nbc= $_REQUEST["nbc"];
		break;

		case "NucA":
			$NucA= $_REQUEST["NucA"];
		break;

		case "NucB":
			$NucB= $_REQUEST["NucB"];
		break;

		case "NucC":
			$NucC= $_REQUEST["NucC"];
		break;

		case "NucD":
			$NucD= $_REQUEST["NucD"];
		break;

		case "NucH":
			$NucH= $_REQUEST["NucH"];
		break;

		case "NucJ":
			$NucJ= $_REQUEST["NucJ"];
		break;

		case "NucL":
			$NucL= $_REQUEST["NucL"];
		break;

		case "NucM1":
			$NucM1= $_REQUEST["NucM1"];
		break;

		case "NucM2":
			$NucM2= $_REQUEST["NucM2"];
		break;

		case "NucZB":
			$NucZB= $_REQUEST["NucZB"];
		break;

		case "chemA":
			$chemA= $_REQUEST["chemA"];
		break;

		case "chemB":
			$chemB= $_REQUEST["chemB"];
		break;

		case "chemC":
			$chemC= $_REQUEST["chemC"];
		break;

		case "chemD":
			$chemD= $_REQUEST["chemD"];
		break;

		case "chemE":
			$chemE= $_REQUEST["chemE"];
		break;

		case "chemF":
			$chemF= $_REQUEST["chemF"];
		break;

		case "chemG":
			$chemG= $_REQUEST["chemG"];
		break;

		case "chemH":
			$chemH= $_REQUEST["chemH"];
		break;

		case "chemI":
			$chemI= $_REQUEST["chemI"];
		break;

		case "chemY1":
			$chemY1= $_REQUEST["chemY1"];
		break;

		case "chemY2":
			$chemY2= $_REQUEST["chemY2"];
		break;

		case "chemZA":
			$chemZA= $_REQUEST["chemZA"];
		break;

		case "chemZB":
			$chemZB= $_REQUEST["chemZB"];
		break;

		case "signalmiddel":
			$signalmiddel= $_REQUEST["signalmiddel"];
			// echo "Signalmiddel.".$signalmiddel."<br>";
		break;

		case "prio":
			$prio= $_REQUEST["prio"];
		break;

		case "legim_Q":
			$legim_Q= $_REQUEST["legim_Q"];
		break;

		case "legim_svar":
			$legim_svar= $_REQUEST["legim_svar"];
		break;

		default:	// Error handling!
		break;
		}
	}

// Konstruer alle nødvendige queries og fyr dem af.
if ( $_SESSION["userObj"]->userMayWrite() and $_SESSION["userObj"]->userIsLegal() )
{


switch($nbc){
	case "nuc":
	
	$overskrift= "CBRN - NUC";
	$kort_text= sprintf("NucA - %s\n", $NucA);
	$kort_text.= sprintf("NucB - %s\n", $NucB);
	$kort_text.= sprintf("NucC - %s\n", $NucC);
	$kort_text.= sprintf("NucD - %s\n", $NucD);
	$kort_text.= sprintf("NucH - %s\n", $NucH);
	$kort_text.= sprintf("NucJ - %s\n", $NucJ);
	$kort_text.= sprintf("NucL - %s\n", $NucL);
	$kort_text.= sprintf("NucM1 - %s\n", $NucM1);
	$kort_text.= sprintf("NucM2 - %s\n", $NucM2);
	$kort_text.= sprintf("NucZB - %s", $NucZB);

	break;

	case "chem":
	$overskrift= "CBRN - CHEM";
	$kort_text= sprintf("chemA - %s\n", $chemA);
	$kort_text.= sprintf("chemB - %s\n", $chemB);
	$kort_text.= sprintf("chemC - %s\n", $chemC);
	$kort_text.= sprintf("chemD - %s\n", $chemD);
	$kort_text.= sprintf("chemE - %s\n", $chemE);
	$kort_text.= sprintf("chemF - %s\n", $chemF);
	$kort_text.= sprintf("chemG - %s\n", $chemG);
	$kort_text.= sprintf("chemH - %s\n", $chemH);
	$kort_text.= sprintf("chemI - %s\n", $chemI);
	$kort_text.= sprintf("chemY1 - %s\n", $chemY1);
	$kort_text.= sprintf("chemY2 - %s\n", $chemY2);
	$kort_text.= sprintf("chemZA - %s\n", $chemZA);
	$kort_text.= sprintf("chemZB - %s", $chemZB);

	break;
	
	default:
	// Error handling;
	break;
}

$kort_text= mysql_real_escape_string(htmlentities($kort_text), $link);

// echo "<br>TIMESTAMP: ".t_stamp($dtg);
$user= $_SESSION["userObj"]->userNumber();

$main_query=	"INSERT INTO"
		." signaler(id"
		.", afsenderID"
		.", dtg"
		.", dtg_stamp"
		.", overskrift"
		.", kort_text"
		.", keyopID"
		.", indtast_tid"
		.", priority"
		.", legim_Q"
		.", legim_svar"
		.", signalmiddel) "
		."VALUES (NULL, '$afsenderID', '$dtg', '$time_stamp', '$overskrift', '$kort_text', '$user', NULL, '$prio', '$legim_Q', '$legim_svar',  '$signalmiddel')";

// Performing SQL query
$result = DebugQuery($main_query);
$s_id= mysql_insert_id();

// Link alle modtagere i signalet.
if ($mo != 0)
	foreach ($modt as $m)
		{
			$link_query= "INSERT INTO modtagere(signalID, enhederID) VALUES ($s_id, $m)";
			// echo $link_query."<br>";
			// Performing SQL query
			$result = DebugQuery($link_query);
		}

// Link alle info-modtagere i signalet.
if ($inf != 0)
	foreach ($info as $m)
		{
			$link_query= "INSERT INTO info(signalID, enhederID) VALUES ($s_id, $m)";
			// echo $link_query."<br>";
			// Performing SQL query
			$result = DebugQuery($link_query);
		}
// exit;
}

/* Redirect to main page so reload will not insert another copy of signal. */
$host  = $_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'main.php?M_action=VisJournal';
header("Location: http://$host$uri/$extra");
print_footer(false);
exit;
}

?>
