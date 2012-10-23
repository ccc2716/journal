<?php
/****************************************************************
//  File: VisMelding.php					*
//								*
//  This file is a part of the "journal system for HJV"		*
//								*
//  Copyright Sten Carlsen 2006, 2007				*
//								*
//  Journal-systemet is free software: you can redistribute it	*
//  and/or modify it under the terms of the GNU General Public	*
//  License as published by the Free Software Foundation, either*
//  version 3 of the License, or (at your option) any later	*
//  version.							*
// 								*
//  Journal-systemet is distributed in the hope that it will be	*
//  useful, but WITHOUT ANY WARRANTY; without even the implied	*
//  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR	*
//  PURPOSE. See the GNU General Public License for more details.*
// 								*
//  You should have received a copy of the GNU General Public	*
//  License along with Journal-systemet. If not, see		*
//  <http://www.gnu.org/licenses/>.				*
//								*
//  This file holds the functions used				*
//  to draw the "Vis Melding" page in the system		*
//	$Id$							*
//								*
//**************************************************************/


require_once("TimeFunctions.php");

/*
vismelding(){
header;
if (SignalKanRettes())

	{VisEgen();
	VisRetLegitimation()}

else

	{VisNormal();
	VisLegitimation()}

ExtraNoter();
VisForetaget();
print_footer()
}
*/

/****************************************************************
 * 								*
 * 	function SignalKanRettes($key_op, $indtast_tid)		*
 * 								*
 * This function looks for the following input:			*
 * $_SESSION["User"], $_REQUEST["jour_id"], $_SESSION["EgenEnhed"],	*
 * $RetteArray["RetteAnsvar"], $RetteArray["RetteTid"]		*
 ***************************************************************/

/*
function SignalKanRettes($key_op, $indtast_tid){
	global $RetteArray;
	$user= $_SESSION["User"];
	$rights= DetectRights();
	$id= $_REQUEST["jour_id"];
	$egenenhed= $_SESSION["EgenEnhed"];
	$UserOk= false;
	$TimeOk= false;
// Check if user is allowed to correct this signal. Administrator can not change anything.
	switch($RetteArray["RetteAnsvar"]){
		case "any":
			$UserOk= !($rights & RIGHTS_System);
			break;

		case "admin":
			if (($rights & RIGHTS_Admin) && !($rights & RIGHTS_System))
				$UserOk= true;
			else
				$UserOk= ($key_op == $user) && !($rights & RIGHTS_System);
			break;

		case "orig":
		default:
			$UserOk= ($key_op == $user) && !($rights & RIGHTS_System);
			break;
		}
// Check if we are within time limit.
	$TimeGone= dtg_diff(date( "Y-n-j H:i:s"), $indtast_tid);
	if ($RetteArray["RetteTid"] == 0)
		$TimeOk= true;
	else
		if ($RetteArray["RetteTid"] < 0)
			$TimeOk= false;
		else
			$TimeOk= $TimeGone <= $RetteArray["RetteTid"];

	return($UserOk && $TimeOk);
}
*/

/****************************************************************
 * 								*
 * 	function VisEgen(&$signal)				*
 * 								*
 * This function looks for the following input:			*
 * $_SESSION["User"], $_REQUEST["jour_id"], $_SESSION["EgenEnhed"]	*
 ***************************************************************/


function VisEgen(&$signal)
{
	$user= $_SESSION["userObj"]->userNumber();
	$id= $_REQUEST["jour_id"];
	$egenenhed= $_SESSION["userObj"]->userEgenEnhed();

	// Lav listen med brug af MySql udtræk.

	// Performing SQL query for signal.
	$signal_query = "SELECT signaler.dtg,".		/* 0 */
			"enheder.navn,".		/* 1 */
			"signaler.overskrift,".		/* 2 */
			"signaler.kort_text,".		/* 3 */
			"signaler.keyopID,".		/* 4 */
			"signaler.indtast_tid,".	/* 5 */
			"signaler.signalmiddel,".	/* 6 */
			"signaler.afsenderID, ".	/* 7 */
			"signaler.Rettelse, ".		/* 8 */
			"signaler.priority ".		/* 9 */
			"FROM signaler, enheder ".
			"WHERE signaler.afsenderID=enheder.id AND signaler.id=".$id;
	// echo $signal_query."---".$user;
	$signal_result = DebugQuery($signal_query);

	// Printing results in HTML

	$signal = mysql_fetch_row($signal_result);

	// Free resultset
	mysql_free_result($signal_result);

	// Performing SQL query for modtagere
	$modt_query = 	"SELECT enheder.id ".
			"FROM enheder, modtagere ".
			"WHERE modtagere.signalID=$id AND enheder.id=modtagere.enhederID";

	// echo $modt_query."---".$user;
	$modt_result = DebugQuery($modt_query);

	$i= 1; $act_modt= array();
	while ($modt = mysql_fetch_row($modt_result)) {
		$act_modt[$i++]= $modt[0]; /*echo $i,"-X-",$act_modt[$i],"<br>\n";*/
		}
//	echo "print_r<br>\n";
//	print_r($act_modt); print_r($i);

	// Performing SQL query for info
	$info_query = 	"SELECT enheder.id ".
			"FROM enheder, info ".
			"WHERE info.signalID=$id AND enheder.id=info.enhederID";

	// echo $info_query."---".$user;
	$info_result = DebugQuery($info_query);

	$i= 1; $act_info= array();
	while ($info = mysql_fetch_row($info_result)) {
		$act_info[]= $info[0]; $i+= 1;
		}


	// Free resultset
	mysql_free_result($modt_result);
	mysql_free_result($info_result);


	// Print whole signal, properly formatted.
	$keyop= $signal[4];
?>
<FORM action="main.php" method="get" onsubmit="mySetSize()">
<INPUT Type=HIDDEN NAME="M_action" VALUE="GemRetMelding">
<INPUT Type=HIDDEN NAME="M_height" VALUE="720">
<INPUT Type=HIDDEN NAME="rettelse" VALUE=<?php echo $signal[8]+1;?>>
<?php
printf("<INPUT Type=HIDDEN NAME=\"jour_id\" VALUE='".$id."'>\n");

printf("<P><FONT class=felt>L&oslash;benummer:</FONT> %s</P>\n", $id);
?>
<FONT class=felt>DTG:</FONT>
<INPUT class="hilight" Type=text NAME="dtg" size="16" value=<?php echo $signal[0];?>>
&nbsp;&nbsp;<FONT class=felt>Fortrinsret:</FONT>&nbsp;&nbsp;
<SELECT name= "prio">
<?php
printf("<OPTION %s value= \"0\">Rutine</OPTION>\n", ($signal[9] == 0)?"selected":"");
printf("<OPTION %s value= \"1\">Il</OPTION>\n", ($signal[9] == 1)?"selected":"");
printf("<OPTION %s value= \"2\">Operations Il</OPTION>\n", ($signal[9] == 2)?"selected":"");
printf("<OPTION %s value= \"3\">ALARM</OPTION>\n", ($signal[9] == 3)?"selected":"");
printf("</SELECT><br><br>");

	// Performing SQL query
	$afs_query = 'SELECT enheder.id, enheder.kaldetal, enheder.navn FROM enheder ORDER BY enheder.navn';
	$afs_result = DebugQuery($afs_query);

	?>

<FONT class=felt>Afsender:</FONT><br>
<SELECT name="afsender">
<?php
	// Printing results in HTML

	while ($afs_line = mysql_fetch_row($afs_result)) { /*  echo $signal[21],"z",$afsline[0];*/
		printf("<OPTION %s value=\"%s\">%5s - %20s</OPTION>\n", ($signal[7]==$afs_line[0]?"selected":""), $afs_line[0], $afs_line[1], $afs_line[2]);
		}

	// Free resultset
	mysql_free_result($afs_result);
?>
</SELECT>
<br><br>

<TABLE>
	<TR>
		<TD>
<FONT class=felt>Modtager(e):</FONT><br>
<SELECT class="hilight" multiple size="12" name="modtager">
<?php
// Performing SQL query mulige modtagere.
$mu_modt_query = 'SELECT enheder.id, enheder.kaldetal, enheder.navn FROM enheder ORDER BY enheder.navn';
$mu_modt_result = DebugQuery($mu_modt_query);
// Printing results in HTML

/* check if already should be selected. */
while ($mu_modt_line = mysql_fetch_row($mu_modt_result)) {
	printf("<OPTION %s value=\"%s\">%5s - %20s</OPTION>\n", (in_array($mu_modt_line[0], $act_modt)?"selected":""), $mu_modt_line[0], $mu_modt_line[1], $mu_modt_line[2]);
	}

// Free resultset
mysql_free_result($mu_modt_result);
?>
</SELECT>
		</TD>
		<TD>
<FONT class=felt>Info:</FONT><br>
<SELECT class="hilight" multiple size="12" name="info">
<?php
// Performing SQL query
$mu_info_query = 'SELECT enheder.id, enheder.kaldetal, enheder.navn FROM enheder ORDER BY enheder.navn';
$mu_info_result = DebugQuery($mu_info_query);
// Printing results in HTML

while ($mu_info_line = mysql_fetch_row($mu_info_result)) {
	printf("<OPTION %s value=\"%s\">%5s - %20s</OPTION>\n", (in_array($mu_info_line[0], $act_info)?"selected":""), $mu_info_line[0], $mu_info_line[1], $mu_info_line[2]);
	}

// Free resultset
mysql_free_result($mu_info_result);


?>
</SELECT>
		</TD>
	</TR>
</TABLE>

<br><br>
<FONT class=felt>Overskrift:</FONT><br>
<INPUT class="hilight" Type="text" NAME="overskrift" size="64" Value="<?php echo stripcslashes($signal[2]); ?>">
<br><br>
<FONT class=felt>Tekst:</FONT><br>
<TEXTAREA class="hilight" name="kort_text" rows="3" cols="60">
<?php echo (stripcslashes($signal[3])); ?>
</TEXTAREA>
<br><br>
<FONT class=felt>Signalmiddel:</FONT><br>
<SELECT name="signalmiddel">
<OPTION<?php echo (($signal[6]=="TLF")?" selected":""); ?>>TLF</OPTION>
<OPTION<?php echo (($signal[6]=="RDO")?" selected":""); ?>>RDO</OPTION>
<OPTION<?php echo (($signal[6]=="ORD")?" selected":""); ?>>ORD</OPTION>
<OPTION<?php echo (($signal[6]=="Fiin")?" selected":""); ?>>Fiin</OPTION>
<OPTION<?php echo (($signal[6]=="email")?" selected":""); ?>>email</OPTION>
<OPTION<?php echo (($signal[6]=="SINE")?" selected":""); ?>>SINE</OPTION>
<OPTION<?php echo (($signal[6]=="Andet")?" selected":""); ?>>Andet</OPTION>
<OPTION<?php echo (($signal[6]=="Lokalt")?" selected":""); ?>>Lokalt</OPTION>
   </SELECT>
<br><br>

<?php
	// System 7.
	VisRetLegitimation($id);

	if ($keyop != 0)
		{
		// Performing SQL query
		$indt_query = 	"SELECT personel.navn ".
				"FROM personel ".
				"WHERE personel.id=".$keyop;
		// echo $indt_query."---".$keyop;
		$result = DebugQuery($indt_query);
		$indt = mysql_fetch_row($result);

		printf("<P><FONT class=felt>Indtastet af:</FONT> %-s; %s; %s</P>\n", $indt[0], $signal[5], $signal[8]!=0?"Rettet ".$signal[8]." gange.":"Ikke rettet.");

		// Free resultset
		mysql_free_result($result);
		}
?>

<INPUT type="submit" value="Gem rettelse">
   </P>
</FORM>
<?php
}

function VisNormal(&$signal)
{
	$user= $_SESSION["User"];
	$id= $_REQUEST["jour_id"];

	// Lav listen med brug af MySql udtræk.


	// Performing SQL query
	$query = 	"SELECT signaler.dtg, ".	// 0
			"enheder.navn, ".               // 1
			"signaler.overskrift, ".        // 2
			"signaler.kort_text, ".         // 3
			"signaler.keyopID, ".           // 4
			"signaler.indtast_tid, ".       // 5
			"signaler.signalmiddel, ".      // 6
			"signaler.Rettelse, ".          // 7
			"signaler.priority ".		// 8
			"FROM signaler, enheder ".
			"WHERE signaler.afsenderID=enheder.id AND signaler.id=".$id;
	// echo $query."---".$user;
	$result = DebugQuery($query);

	// Printing results in HTML

	$signal = mysql_fetch_row($result);

	// Free resultset
	mysql_free_result($result);

	// Performing SQL query
	$modt_query = 	"SELECT enheder.navn ".
			"FROM enheder, modtagere ".
			"WHERE modtagere.signalID=$id AND enheder.id=modtagere.enhederID";

	// echo $modt_query."---".$user;
	$result = DebugQuery($modt_query);

	// Performing SQL query
	$info_query = 	"SELECT enheder.navn ".
			"FROM enheder, info ".
			"WHERE info.signalID=$id AND enheder.id=info.enhederID";

	// echo $info_query."---".$user;
	$result_2 = DebugQuery($info_query);

	// Print whole signal, properly formatted.
	$keyop= $signal[4];
	printf("<P class=adresser><FONT class=felt>DTG:</FONT> %-10s; <FONT class=felt>L&oslash;benummer:</FONT> %s</P>\n<P class=adresser>", $signal[0], $id);

	switch($signal[8])
	{
		case "1":
			$T_prio= "Il";
			break;
		case "2":
			$T_prio= "Operations Il";
			break;
		case "3":
			$T_prio= "ALARM";
			break;
		case "0":
		default:
			$T_prio= "Rutine";
			break;
	}

	printf("<FONT class=felt>Fortrinsret:</FONT> %-s<br><br>", $T_prio);
	printf("<FONT class=felt>Fra:</FONT> %-s</P>\n", $signal[1]);

	printf("<P class=adresser><FONT class=felt>Til:</FONT>");
	while ($modt = mysql_fetch_row($result)) {
		printf(" %-s;", $modt[0]);
		}
	printf("</P>\n");

	printf("<P class=adresser><FONT class=felt>Info:</FONT>");
	while ($info = mysql_fetch_row($result_2)) {
		printf(" %-s;", $info[0]);
		}
	printf("</P>\n");

	// Free resultset
	mysql_free_result($result);
	mysql_free_result($result_2);

	printf("<P><FONT class=felt>Overskrift:</FONT> %-s</P>\n",nl2br(stripcslashes($signal[2])));
	printf("<P><FONT class=felt>Signalets fulde tekst:</FONT> <br>\n%-s</P>\n", nl2br(stripcslashes($signal[3])));

	printf("<P><FONT class=felt>Signalmiddel:</FONT> %-s</P>\n",nl2br(stripcslashes($signal[6])));


	// System 7.
	VisLegitimation($id);


	if ($keyop != 0)
		{
		// Performing SQL query
		$indt_query = 	"SELECT personel.navn ".
				"FROM personel ".
				"WHERE personel.id=".$keyop;
		// echo $indt_query."---".$keyop;
		$result = DebugQuery($indt_query);
		$indt = mysql_fetch_row($result);

		printf("<P><FONT class=felt>Indtastet af:</FONT> %-s; %s; %s</P>\n", $indt[0], $signal[5], $signal[7]!=0?"Rettet ".$signal[7]." gange.":"Ikke rettet.");

		// Free resultset
		mysql_free_result($result);
		}

}

/****************************************************************
 * 								*
 * 	function VisForetaget(&$signal)				*
 * 								*
 * This function looks for the following input:			*
 * $_REQUEST["jour_id"]						*
 ***************************************************************/



function VisForetaget(&$signal)
{
	$id= $_REQUEST["jour_id"];

// Performing SQL query
	$query = 	"SELECT foretagetID"		/* 0 */
			.", foretaget_tid"		/* 1 */
			.", over_bool"				/* 2 */
			.", over_text"				/* 3 */
			.", side_bool"				/* 4 */
			.", side_text"				/* 5 */
			.", under_bool"				/* 6 */
			.", under_text"				/* 7 */
			.", journ_bool"				/* 8 */
			.", journ_text"				/* 9 */
			.", sitkort_bool"			/* 10 */
			.", sitkort_text"			/* 11 */
			.", rund_bool"				/* 12 */
			.", rund_text"				/* 13 */
			." FROM foretaget "
			." WHERE signal_ID=".$id
			." ORDER BY foretaget_tid";

	// echo $query."---".$user;
	$fore_result = DebugQuery($query);

	// Printing results in HTML


printf("<P><FONT class=felt>Foretaget.</FONT><br>");

// Change from here to while and new note. Remove update option.
	$over|= false;
	$side|= false;
	$under|= false;
	$jour|= false;
	$kort|= false;
	$rund|= false;


	while ($foretag = mysql_fetch_row($fore_result)) {
	$name_query =   "SELECT personel.navn ".
			"FROM personel ".
			"WHERE personel.id=".$foretag[0];
	// echo $indt_query."---".$keyop;
	$name_result = DebugQuery($name_query);
	$name = mysql_fetch_row($name_result);

	printf("%s; %s<br>\n", $name[0], $foretag[1]);
	printf("<P>Foresat: <INPUT %s name=\"over_bool\" type=\"checkbox\"  disabled>%s<br>\n",
		$foretag[2]?"checked":"", nl2br(stripcslashes($foretag[3])));
	printf("Sideordnet: <INPUT %s name=\"side_bool\" type=\"checkbox\"  disabled>%s<br>\n",
		$foretag[4]?"checked":"", nl2br(stripcslashes($foretag[5])));
	printf("Underordnet: <INPUT %s name=\"under_bool\" type=\"checkbox\"  disabled>%s<br>\n",
		$foretag[6]?"checked":"", nl2br(stripcslashes($foretag[7])));
	printf("Journalbilag: <INPUT %s name=\"journ_bool\" type=\"checkbox\"  disabled>%s<br>\n",
		$foretag[8]?"checked":"", nl2br(stripcslashes($foretag[9])));
	printf("f&oslash;rt p&aring; Kort: <INPUT %s name=\"sitkort_bool\" type=\"checkbox\"  disabled>%s<br>\n",
		$foretag[10]?"checked":"", nl2br(stripcslashes($foretag[11])));
	printf("Rundsendt: <INPUT %s name=\"rund_bool\" type=\"checkbox\"  disabled>%s</P>\n",
		$foretag[12]?"checked":"", nl2br(stripcslashes($foretag[13])));
	// Summary of checkmarks.
	$over|= $foretag[2];
	$side|= $foretag[4];
	$under|= $foretag[6];
	$jour|= $foretag[8];
	$kort|= $foretag[10]; 
	$rund|= $foretag[12];
	}
// Free resultset
mysql_free_result($fore_result);



	if ($_SESSION["userObj"]->userMayWrite() && !($_SESSION["userObj"]->userRightsCheck(RIGHTS_System)))
	{
	printf("<FORM action=\"main.php\" method=\"get\" onsubmit=\"mySetSize()\">\n");
	printf("<INPUT Type=HIDDEN NAME=\"M_action\" VALUE=\"GemForetaget\">\n");
	printf("<INPUT id=\"M_height\" Type=HIDDEN NAME=\"M_height\" VALUE=\"720\">");
	printf("<INPUT Type=HIDDEN NAME=\"jour_id\" VALUE='".$id."'>\n");

	printf("<P><FONT class=felt>Foresat: </FONT><INPUT %s name=\"over_bool\" type=\"checkbox\">"
		."<INPUT class=\"hilight\" name=\"over_text\" type=\"text\" size=\"30\"></P>\n",
		$over?"checked":"");
	printf("<P><FONT class=felt>Sideordnet: </FONT><INPUT %s name=\"side_bool\" type=\"checkbox\">"
		."<INPUT class=\"hilight\" name=\"side_text\" type=\"text\" size=\"30\"></P>\n",
		$side?"checked":"");
	printf("<P><FONT class=felt>Underordnet: </FONT><INPUT %s name=\"under_bool\" type=\"checkbox\">"
		."<INPUT class=\"hilight\" name=\"under_text\" type=\"text\" size=\"30\"></P>\n",
		$under?"checked":"");
	printf("<P><FONT class=felt>Journalbilag: </FONT><INPUT %s name=\"journ_bool\" type=\"checkbox\">"
		."<INPUT class=\"hilight\" name=\"journ_text\" type=\"text\" size=\"30\"></P>\n",
		$jour?"checked":"");
	printf("<P><FONT class=felt>f&oslash;rt p&aring; Kort: </FONT><INPUT %s name=\"sitkort_bool\" type=\"checkbox\">"
		."<INPUT class=\"hilight\" name=\"sitkort_text\" type=\"text\" size=\"30\"></P>\n",
		$kort?"checked":"");
	printf("<P><FONT class=felt>Rundsendt: </FONT><INPUT %s name=\"rund_bool\" type=\"checkbox\">"
		."<INPUT class=\"hilight\" name=\"rund_text\" type=\"text\" size=\"30\"></P>\n",
		$rund?"checked":"");

	printf("<INPUT type=\"submit\" value=\"Tilf&oslash;j foretaget\">");
	printf("</FORM>");

	}
}

/****************************************************************
 * 								*
 * 	function VisMelding()					*
 * 								*
 * This function looks for the following input:			*
 * $_REQUEST["jour_id"]						*
 ***************************************************************/



function VisMelding()
{
if ($_SESSION["Debug"]->debugActive(256))
	{
		$dp= print_r($_REQUEST, true);
		$d= 'VisMelding - $_REQUEST: '.$dp."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}

top();
?>

	<div class="midt">
<?php
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Find who entered this melding.

$id= $_REQUEST["jour_id"]; // echo $id;


// Performing SQL query
$query = 	"SELECT signaler.keyopID, signaler.indtast_tid ".
		"FROM signaler ".
		"WHERE signaler.id=".$id;
// echo $query;
$result = DebugQuery($query);

// Printing results in HTML

$key = mysql_fetch_row($result);
$key_op= $key[0];
$indtast_tid= $key[1];

// Free resultset
mysql_free_result($result);


if ($_SESSION["userObj"]->userCanChangeMessage($key_op, $indtast_tid))
	{
	VisEgen($signaler);
	}
else
	{
	VisNormal($signaler);
	}


echo "<HR>";
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

EkstraNoter();

echo "<HR>";
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

VisForetaget($signaler);
?>

		</div>
<?php
bottom();
}


/****************************************************************
 * function GemForetaget()					*
//  This function holds the function used to save		*
//   the data generated by the "Ret Foretaget" button		*
//								*
 * This function looks for the following input:			*
 * $_SESSION["User"], $_REQUEST["over_bool"],			*
 * $_REQUEST["over_text"], $_REQUEST["side_bool"],		*
 * $_REQUEST["side_text"], $_REQUEST["under_bool"],		*
 * $_REQUEST["under_text"], $_REQUEST["journ_bool"],		*
 * $_REQUEST["journ_text"], $_REQUEST["sitkort_bool"],		*
 * $_REQUEST["sitkort_text"], $_REQUEST["rund_bool"],		*
 * $_REQUEST["rund_text"], $_REQUEST["jour_id"]			*
//**************************************************************/


function GemForetaget()
{

	global $link;

	$user= $_SESSION["userObj"]->userNumber();

	if ((!$_SESSION["userObj"]->userIsLegal()) || ($_SESSION["userObj"]->userRightsCheck(RIGHTS_System))) {
		/* Not Legal user; log him out, he must not make any changes. */
		$host  = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'main.php?M_action=LogUd';
		header("Location: http://$host$uri/$extra");
		print_footer(false);
		exit;
	}


if ($_SESSION["userObj"]->userMayWrite())
{
// Konstruer alle nødvendige queries og fyr dem af.



$query_0=	'SELECT over_bool'				/* 0 */
			.", side_bool"				/* 1 */
			.", under_bool"				/* 2 */
			.", journ_bool"				/* 3 */
			.", sitkort_bool"			/* 4 */
			.", rund_bool"				/* 5 */
			.", over_text"				/* 6 */
			.", side_text"				/* 7 */
			.", under_text"				/* 8 */
			.", journ_text"				/* 9 */
			.", sitkort_text"			/* 10 */
			.", rund_text"				/* 11 */
		.' FROM foretaget'
		.' WHERE signal_ID='.$_REQUEST["jour_id"];
// Performing SQL query
	$fore_result = DebugQuery($query_0);


	$over|= false;
	$side|= false;
	$under|= false;
	$jour|= false;
	$kort|= false;
	$rund|= false;

	while ($foretag = mysql_fetch_row($fore_result)) {

	// Summary of checkmarks.
	$over|= $foretag[0] || (strlen($foretag[6]) != 0);
	$side|= $foretag[1] || (strlen($foretag[7]) != 0);
	$under|= $foretag[2] || (strlen($foretag[8]) != 0);
	$jour|= $foretag[3] || (strlen($foretag[9]) != 0);
	$kort|= $foretag[4] || (strlen($foretag[10]) != 0);
	$rund|= $foretag[5] || (strlen($foretag[11]) != 0);
	}




/*
echo $_REQUEST["under_text"]."<br>\n";
$xxx= $_REQUEST["under_text"];
echo $xxx."<br>\n";
echo htmlentities($xxx, ENT_COMPAT, "UTF-8")."<br>\n";
echo mysql_real_escape_string(htmlentities($_REQUEST["under_text"], ENT_COMPAT, "UTF-8"), $link)."<br>\n";
echo mysql_real_escape_string($_REQUEST["under_text"], $link)."<br>\n";
exit();
*/

	$main_query= "INSERT INTO foretaget ".
		"SET foretagetID= '$user', ".
		"signal_ID= ".$_REQUEST["jour_id"].", ".
		"over_bool= '".((($_REQUEST["over_bool"] == "on") or (strlen($_REQUEST["over_text"]) != 0) or ($over == true))?1:0).
			"', over_text= '".mysql_real_escape_string(htmlentities($_REQUEST["over_text"]), $link)."', ".
			
		"side_bool= '".((($_REQUEST["side_bool"] == "on") or (strlen($_REQUEST["side_text"]) != 0) or ($side == true))?1:0).
			"', side_text= '".mysql_real_escape_string(htmlentities($_REQUEST["side_text"]), $link)."', ".
			
		"under_bool= '".((($_REQUEST["under_bool"] == "on") or (strlen($_REQUEST["under_text"]) != 0) or ($under == true))?1:0).
			"', under_text= '".mysql_real_escape_string(htmlentities($_REQUEST["under_text"]), $link)."', ".
			
		"journ_bool= '".((($_REQUEST["journ_bool"] == "on") or (strlen($_REQUEST["journ_text"]) != 0) or ($jour == true))?1:0).
			"', journ_text= '".mysql_real_escape_string(htmlentities($_REQUEST["journ_text"]), $link)."', ".
			
		"sitkort_bool= '".((($_REQUEST["sitkort_bool"] == "on") or (strlen($_REQUEST["sitkort_text"]) != 0) or ($kort == true))?1:0).
			"', sitkort_text= '".mysql_real_escape_string(htmlentities($_REQUEST["sitkort_text"]), $link)."', ".
			
		"rund_bool= '".((($_REQUEST["rund_bool"] == "on") or (strlen($_REQUEST["rund_text"]) != 0) or ($rund == true))?1:0).
			"', rund_text= '".mysql_real_escape_string(htmlentities($_REQUEST["rund_text"]), $link)."', ".
		"foretaget_tid= NULL ";

// Performing SQL query
	$result = DebugQuery($main_query);
	
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
//	function GemRetMelding()				*
//  This function is used					*
//  to save a corrected signal in the system			*
//								*
 * This function looks for the following input:			*
 * $_SESSION["User"], $link, $_SERVER["QUERY_STRING"]		*
//**************************************************************/


function GemRetMelding()
{

global $link;

$mo= 0;
$inf= 0;
$user= $_SESSION["userObj"]->userNumber();

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

		case "rettelse":
			$Rettelse= $_REQUEST["rettelse"];
		break;

		case "overskrift":
			$overskrift= $_REQUEST["overskrift"];
			// echo "overskrift.".$overskrift."<br>";
		break;

		case "kort_text":
			$kort_text= $_REQUEST["kort_text"];
			// echo "kort_text.".$kort_text."<br>";
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

		case "signalmiddel":
			$signalmiddel= $_REQUEST["signalmiddel"];
			// echo "Signalmiddel.".$signalmiddel."<br>";
		break;

		default:	// Error handling!
		break;
		}
	}

// Konstruer alle nødvendige queries og fyr dem af.
$id= $_REQUEST["jour_id"];


if ($_SESSION["userObj"]->userMayWrite())
{



$overskrift= mysql_real_escape_string(htmlentities($overskrift), $link);
$kort_text= mysql_real_escape_string(htmlentities($kort_text), $link);

// echo "<br>TIMESTAMP: ".t_stamp($dtg);

// Special treatment for $legim_Q:
if(strlen($legim_Q)==0)
	{
		$legim_Q_string= ', legim_Q= NULL';
	}
else
	{
		$legim_Q_string= ", legim_Q= '".$legim_Q."'";
	}

$main_query= "UPDATE signaler ".
	"SET afsenderID= '".$afsenderID.
	"', dtg= '".$dtg.
	"', dtg_stamp= '".$time_stamp.
	"', overskrift= '".$overskrift.
	"', kort_text= '".$kort_text.
	"', signalmiddel= '".$signalmiddel.
	"', Rettelse= '".$Rettelse.
	"', priority= '".$prio.
	"'".$legim_Q_string.
	", legim_svar= '".$legim_svar.
	"' WHERE signaler.id=".$id;


// echo $main_query."<br>\n";

// Performing SQL query
$result = DebugQuery($main_query);
// $s_id= mysql_insert_id();
// echo "NEXT-1<br>\n";
// Link alle modtagere i signalet.
// First remove all old entries
$modt_delete_query= "DELETE IGNORE FROM modtagere WHERE modtagere.signalID=".$id;
// echo $modt_delete_query."<br>\n";
$modt_delete_result = DebugQuery($modt_delete_query);
// echo "NEXT-2<br>\n";

// Then add all new ones.
if ($mo != 0)
	foreach ($modt as $m)
		{
			$link_query= "INSERT INTO modtagere(signalID, enhederID) VALUES ($id, $m)";
			// echo $link_query."<br>";
			// Performing SQL query
			$link_result = DebugQuery($link_query);
		}

// Link alle info-modtagere i signalet.
// First remove all old entries
$info_delete_query= "DELETE IGNORE FROM info WHERE signalID=".$id;
$info_delete_result = DebugQuery($info_delete_query);
// echo "NEXT-3<br>\n";

// Then add all new ones.
if ($inf != 0)
	foreach ($info as $m)
		{
			$link_query= "INSERT INTO info(signalID, enhederID) VALUES ($id, $m)";
			// echo $link_query."<br>";
			// Performing SQL query
			$link_result = DebugQuery($link_query);
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
 *	function EkstraNoter()					*
 *  This function is used					*
 *  to show ekstra noter in a signal in the system		*
 *								*
 * This function looks for the following input:			*
 * $_REQUEST["jour_id"]						*
//**************************************************************/


function EkstraNoter()
{
	$id= $_REQUEST["jour_id"];

	// Performing SQL query
	$query = 	"SELECT note_ID"	/* 0 */
			.", note_tid"		/* 1 */
			.", note_text "		/* 2 */
			." FROM noter "
			." WHERE signal_ID=".$id
			." ORDER BY note_tid";

	// echo $query."---".$user;
	$note_result = DebugQuery($query);

	// Printing results in HTML


printf("<P><FONT class=felt>Ekstra noter.</FONT><br>");

// Change from here to while and new note. Remove update option.

	while ($note = mysql_fetch_row($note_result)) {
	$name_query =   "SELECT personel.navn ".
			"FROM personel ".
			"WHERE personel.id=".$note[0];

	$name_result = DebugQuery($name_query);
	$name = mysql_fetch_row($name_result);

	$foretag= $fore[0];
		printf("%s; %s<br>\n", $name[0], $note[1]);
		printf("<P>%-s</P>", nl2br(stripcslashes($note[2])));
		}
// Free resultset
mysql_free_result($note_result);


if (!($_SESSION["userObj"]->userRightsCheck(RIGHTS_System)) && $_SESSION["userObj"]->userMayWrite())
	{
	printf("<FORM action=\"main.php\" method=\"get\" onsubmit=\"mySetSize()\">\n");
	printf("<INPUT Type=HIDDEN NAME=\"M_action\" VALUE=\"GemNoter\">\n");
	printf("<INPUT id=\"M_height\" Type=HIDDEN NAME=\"M_height\" VALUE=\"720\">");
	printf("<INPUT Type=HIDDEN NAME=\"jour_id\" VALUE='".$id."'>\n");
	printf("<P><FONT class=felt>Ny note.</FONT><br>");
	printf("<TEXTAREA class=\"hilight\" name=\"noter\" rows=\"3\" cols=\"60\">");
	printf("</TEXTAREA>");
	printf("<input type=\"submit\" value=\"Gem Note\"></P>");
	printf("</FORM>");
	}

}


/****************************************************************
 *	function GemEkstraNoter()				*
 *  This function is used					*
 *  to save ekstra noter in a signal in the system		*
 *								*
 * This function looks for the following input:			*
 * $_SESSION["User"], $link, $_REQUEST["noter"],		*
 * $_REQUEST["jour_id"]						*
//**************************************************************/


function GemEkstraNoter()
{

	global $link;

	$user= $_SESSION["userObj"]->userNumber();

	if (!($_SESSION["userObj"]->userIsLegal()) || ($_SESSION["userObj"]->userRightsCheck(RIGHTS_System)))
	{
		/* Not Legal user; log him out, he must not make any changes. */
		$host  = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'main.php?M_action=LogUd';
		header("Location: http://$host$uri/$extra");
		print_footer(false);
		exit;
	}


if ($_SESSION["userObj"]->userMayWrite())
{
// Konstruer alle nødvendige queries og fyr dem af.

// Change to insert and insert link. Add logic to transfer state to new insert.note.





	$main_query= "INSERT INTO noter "
		."SET note_ID= '$user', "
		."note_tid= NULL, "
		."signal_ID= '".$_REQUEST["jour_id"]."', "
		."note_text= '".mysql_real_escape_string(htmlentities($_REQUEST["noter"]), $link)."' ";

// Performing SQL query
	$result = DebugQuery($main_query);
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
 *	function VisLegitimation()				*
 *  This function is used					*
 *  to show legitimation for a signal in the system		*
 *								*
 * THIS FUNCTION IS USED IN PrintHelJournal also		*
 *								*
 * This function looks for the following input:			*
 * $_REQUEST["jour_id"]						*
//**************************************************************/


function VisLegitimation($id_in)
{
	$id= $_REQUEST["jour_id"];

	// Performing SQL query
	$query = 	"SELECT signaler.legim_Q, ".	/* 0 */
			"signaler.legim_svar, ".	/* 1 */
			"signaler.legim_sig ".		/* 2 */
			"FROM signaler ".
			"WHERE signaler.id=".$id_in;

	// echo $query."---".$user;
	$result = DebugQuery($query);

	// Printing results in HTML

	$signal = mysql_fetch_row($result);

	// Free resultset
	mysql_free_result($result);

	// System 7 legitimation.
	if (strlen($signal[0]) == 0)
		printf("<FONT class=felt>Ingen legitimation.</FONT>");
	else
		{
		// find other uses of these letters
		$query = 	"SELECT signaler.legim_Q, ".	/* 0 */
			"signaler.id ".				/* 1 */
			"FROM signaler ".
			"WHERE signaler.legim_Q= '".$signal[0]."' ORDER BY signaler.dtg_stamp";

		// echo $query."---".$user;
		$result = DebugQuery($query);

		// Printing results in HTML

		$legim = mysql_fetch_row($result);


		printf("<FONT class=felt>Legitimation</FONT> for %s er: %s.<br>\n", $signal[0], $signal[1]);

		if (mysql_num_rows($result) > 1)
			{
				printf("<FONT class=felt>Denne legimitation har v&aelig;ret brugt".
					" i signalerne med l&oslash;benumre</FONT> %s;", $legim[1]);
				while ($legim = mysql_fetch_row($result)) {
					printf(" %-s;", $legim[1]);
				}
				printf("<br>\n");

				// Free resultset
				mysql_free_result($result);
			}
		}
}

/****************************************************************
 *	function VisRetLegitimation()				*
 *  This function is used					*
 *  to show legitimation for a signal in the system		*
 *								*
 * This function looks for the following input:			*
 * $_REQUEST["jour_id"]						*
//**************************************************************/


function VisRetLegitimation($id_in)
{
	// Performing SQL query
	if ($id_in != 0)
	{
		$query = 	"SELECT signaler.legim_Q, ".	/* 0 */
				"signaler.legim_svar ".		/* 1 */
				"FROM signaler ".
				"WHERE signaler.id=".$id_in;

		// echo $query."---".$user;
		$result = DebugQuery($query);

		// Printing results in HTML

		$signal = mysql_fetch_row($result);

		// Free resultset
		mysql_free_result($result);
		printf("<FONT class=felt>Legitimation</FONT> for <INPUT class=\"hilight\" type=\"text\" name=\"legim_Q\" value=\"%s\" maxlength=\"2\" size=\"2\">".
			" er: <INPUT class=\"hilight\" type=\"text\" name=\"legim_svar\" value=\"%s\" maxlength=\"2\" size=\"2\"><br>\n", $signal[0], $signal[1]);

		if(strlen($signal[0]) > 0)	// Var dette overhovedet legitimeret?
			{
			// find other uses of these letters
			$query = 	"SELECT signaler.legim_Q, ".	/* 0 */
				"signaler.id ".				/* 1 */
				"FROM signaler ".
				"WHERE signaler.legim_Q= '".$signal[0]."' ORDER BY signaler.dtg_stamp";

			// echo $query."---".$user;
			$result = DebugQuery($query);

			// Printing results in HTML

			$legim = mysql_fetch_row($result);

			if (mysql_num_rows($result) > 1)	// var der mere end dette signal med denn leg.?
				{
					printf("<FONT class=felt>Denne legimitation har v&aelig;ret brugt".
						" i signalerne med l&oslash;benumre</FONT> %s;", $legim[1]);
					while ($legim = mysql_fetch_row($result)) {
						printf(" %-s;", $legim[1]);
					}
					printf("<br>\n");

					// Free resultset
					mysql_free_result($result);
				}
			}
}
	else
	{
		printf("<FONT class=felt>Legitimation</FONT> for <INPUT type=\"text\" name=\"legim_Q\" maxlength=\"2\" size=\"2\"> er: <INPUT type=\"text\" name=\"legim_svar\" maxlength=\"2\" size=\"2\"><br>\n");
	}

}

?>
