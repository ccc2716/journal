<?php
/****************************************************************
//  File: PrintHelJournal.php					*
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
//  								*
//  Journal-systemet is distributed in the hope that it will be	*
//  useful, but WITHOUT ANY WARRANTY; without even the implied	*
//  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR	*
//  PURPOSE. See the GNU General Public License for more	*
//  details.							*
//  								*
//  You should have received a copy of the GNU General Public	*
//  License along with Journal-systemet. If not, see		*
//  <http://www.gnu.org/licenses/>.				*
//								*
//  This file holds the function used				*
//  to draw the "Print hele journalen" page in the system	*
//	$Id$							*
//								*
//**************************************************************/


/****************************************************************
 * 								*
 * function PrintHelJournal($DetailLevel)			*
 * 								*
 * This function is looking for the following input:		*
 * $_REQUEST["sort"], $_REQUEST['TextSearch']			*
 ***************************************************************/

/*
function PrintHelJournal($DetailLevel)
{
Setup ...
if ($detail == 2) print("<PRE>Header details");
while ($Signal = get row(...))
	{
	switch($detail)
		{
		case 1:
		PrintMelding($Signal);
		break;

		case 2:
		PrintMeldingLinie($Signal, $l_no);
		break;

		default:
		print("Error happened");
		}
	}
if ($detail == 2) print("</PRE>");
}
*/


function PrintHelJournal($DetailLevel)
{
$l_no= 0;

top();
?>

	<div class="midt">
<table class="search" border="3" >
			<TR>
			<TD>
				<P><FONT class=felt>Sorter eller udv&aelig;lg efter:</FONT></P>
			</TD>
			<TD>
				<P><FONT class=felt><ACRONYM TITLE= "S&oslash;geordet (-ene) skal minimum v&aelig;re 4 tegn lange. * kan bruges som wildcard.">S&oslash;g efter tekst i overskrift, tekst eller noter:</ACRONYM></FONT></P>
			</TD>
			</TR>
			<TR>
			<TD>
				<FORM action="main.php" method="get" onsubmit="mySetSize()">
					<INPUT Type=HIDDEN NAME="M_action" VALUE="PrintJournal">
					<INPUT Type=HIDDEN NAME="M_height" VALUE="720">
						<SELECT name="sort">
							<OPTION selected value="1">dtg</OPTION>
							<OPTION value="2">L&oslash;benummer</OPTION>
							<OPTION value="3">Afsender</OPTION>
							<OPTION value="17">Fortrinsret: Forh&oslash;jet</OPTION>
							<OPTION value="18">Legitimeret</OPTION>
							<OPTGROUP LABEL="Signalmiddel">
								<OPTION value="4">Signalmiddel: TLF</OPTION>
								<OPTION value="5">Signalmiddel: RDO</OPTION>
								<OPTION value="6">Signalmiddel: ORD</OPTION>
								<OPTION value="7">Signalmiddel: Fiin</OPTION>
								<OPTION value="8">Signalmiddel: email</OPTION>
								<OPTION value="19">Signalmiddel: SINE</OPTION>
								<OPTION value="9">Signalmiddel: Andet</OPTION>
								<OPTION value="10">Signalmiddel: Lokalt</OPTION>
							</OPTGROUP>
							<OPTGROUP LABEL="Foretaget">
								<OPTION value="11">Foretaget: Foresat</OPTION>
								<OPTION value="12">Foretaget: Sideordnet</OPTION>
								<OPTION value="13">Foretaget: Underordnet</OPTION>
								<OPTION value="14">Foretaget: Journal bilag</OPTION>
								<OPTION value="15">Foretaget: f&oslash;rt p&aring; Kort</OPTION>
								<OPTION value="16">Foretaget: Rundsendt</OPTION>
							</OPTGROUP>
						</SELECT>
					<INPUT type="submit" value="Vis ny sortering">
				</FORM>
			</TD>
			<TD>
			<FORM action="main.php" method="get" onsubmit="mySetSize()">
				<INPUT Type=HIDDEN NAME="M_action" VALUE="PrintJournal">
				<INPUT Type=HIDDEN NAME="sort" VALUE="search">
				<INPUT Type=HIDDEN NAME="M_height" VALUE="720">
				<input class="hilight" type="text" name="TextSearch" value="">
				<INPUT type="submit" value="Vis s&oslash;gning">
			</FORM>
			</TD>
			</TR>

</TABLE>

<?php
// sorter efter $order; vis sorteret efter $order2. Select=<empty>: sorter;
switch ($_REQUEST["sort"]){
	case "2":
		$order= "id";
		$action= "Sorteret efter:";
		$order2= "L&oslash;benummer";
		$select= "";
		$vis_type= 0;
	break;

	case "3":
		$order= "afsenderID";
		$action= "Sorteret efter:";
		$order2= "Afsender";
		$select= "";
		$vis_type= 0;
	break;

	case "4":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Signalmiddel: TLF";
		$select= "TLF";
		$vis_type= 1;
	break;

	case "5":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Signalmiddel: RDO";
		$select= "RDO";
		$vis_type= 1;
	break;

	case "6":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Signalmiddel: ORD";
		$select= "ORD";
		$vis_type= 1;
	break;

	case "7":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Signalmiddel: Fiin";
		$select= "Fiin";
		$vis_type= 1;
	break;

	case "8":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Signalmiddel: email";
		$select= "email";
		$vis_type= 1;
	break;

	case "19":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Signalmiddel: SINE";
		$select= "SINE";
		$vis_type= 1;
	break;

	case "9":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Signalmiddel: Andet";
		$select= "Andet";
		$vis_type= 1;
	break;

	case "10":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Signalmiddel: Lokalt";
		$select= "Lokalt";
		$vis_type= 1;
	break;

	case "11":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Foretaget: Foresat";
		$select= "over_bool";
		$vis_type= 2;
	break;

	case "12":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Foretaget: Sideordnet";
		$select= "side_bool";
		$vis_type= 2;
	break;

	case "13":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Foretaget: Underordnet";
		$select= "under_bool";
		$vis_type= 2;
	break;

	case "14":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Foretaget: Journal bilag";
		$select= "journ_bool";
		$vis_type= 2;
	break;

	case "15":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Foretaget: F&oslash;rt p&aring; kort";
		$select= "sitkort_bool";
		$vis_type= 2;
	break;

	case "16":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Foretaget: Rundsendt";
		$select= "rund_bool";
		$vis_type= 2;
	break;

	case "17":
		$order= "dtg_stamp";
		$action= "Udvalgt efter:";
		$order2= "Fortrinsret: Forh&oslash;jet";
		$select= "rund_bool";
		$vis_type= 3;
	break;

	case "18":
		$order= "id";
		$action= "Udvalgt efter:";
		$order2= "Legitimeret";
		$select= "rund_bool";
		$vis_type= 5;
	break;

	case "search":
		$order= "";
		$action= "S&oslash;gning efter:";
		$order2= $_REQUEST['TextSearch'];
		$select= "";
		$vis_type= 4;
	break;

	default:
	case "1":
		$order= "dtg_stamp";
		$action= "Sorteret efter:";
		$order2= "dtg";
		$select= false;
		$vis_type= 0;
	break;
}

printf("<P><FONT class=felt>%s</FONT> <I>%s</I></P>\n", $action, $order2);

// Lav listen med brug af MySql udtræk.



// Select direction of sorting.
$dir= ($_SESSION["userObj"]->userDirection() == "top")?"DESC":"ASC";

$QueryList= 'signaler.id, '.			// 0
		'signaler.priority, '.		// 1
		'signaler.dtg, '.		// 2
		'enheder.navn, '.		// 3
		'signaler.overskrift, '.	// 4
		'signaler.kort_text, '.		// 5
		'signaler.keyopID, '.		// 6
		'signaler.indtast_tid, '.	// 7
		'signaler.Rettelse, '.		// 8
		'signaler.signalmiddel ';	// 9

// Performing SQL query
switch($vis_type){
	case '1':
		$query = 'SELECT '.$QueryList.
		'FROM signaler, enheder WHERE signaler.afsenderID=enheder.id and signaler.signalmiddel="'.$select.'"
		ORDER BY signaler.'.$order.' '.$dir.', signaler.id '.$dir;
	break;

	case '2':
		$query = 'SELECT DISTINCT '.$QueryList
		.'FROM signaler, enheder, foretaget '
		.'WHERE signaler.afsenderID=enheder.id and '
		.'foretaget.signal_ID=signaler.id and foretaget.'.$select.'="1" '
		.'ORDER BY signaler.'.$order.' '.$dir.', signaler.id '.$dir;
	break;

	case '3':
		$query = 'SELECT '.$QueryList.
		'FROM signaler, enheder WHERE signaler.afsenderID=enheder.id and signaler.priority>"0"
		ORDER BY signaler.'.$order.' '.$dir.', signaler.id '.$dir;
	break;

	case '5':
		$query = 'SELECT '.$QueryList.
		'FROM signaler, enheder WHERE signaler.afsenderID=enheder.id and signaler.legim_Q IS NOT NULL
		ORDER BY signaler.'.$order.' '.$dir.', signaler.id '.$dir;
	break;

	case '4':
		$query = 'SELECT DISTINCT '.$QueryList
		.'FROM signaler, enheder, noter '
		.'WHERE enheder.id=signaler.afsenderID and noter.signal_ID=signaler.ID '
		.'AND MATCH(signaler.overskrift, signaler.kort_text, noter.note_text) '
		.'AGAINST(\''.mysql_real_escape_string($_REQUEST['TextSearch']).'\' IN BOOLEAN MODE) '
		.'ORDER BY signaler.dtg_stamp';
	break;

	default:
	case '0':
		$query = 'SELECT '.$QueryList.
		'FROM signaler, enheder WHERE signaler.afsenderID=enheder.id
		ORDER BY signaler.'.$order.' '.$dir.', signaler.id '.$dir;
	break;
}

// echo $query;
$result = DebugQuery($query);

if($DetailLevel == PrintLinie)
{
	echo "<table class=\"felt2\">";


	printf("<TR><TH>%8s<TH>%16s<TH>%12s<TH>%12s<TH>%6s<TH>%50s\n"
	, "L&oslash;be nr.", "DTG", "Fra", "Til"
	, "<ACRONYM TITLE=\"Foresat, Sideordnet, Underordnet, Journalbilag, Kortf&oslash;rt, Rundsendt\">FSUJKR</ACRONYM>"
	, "Overskrift");
}

while ($line = mysql_fetch_row($result)) {


	switch($DetailLevel)
		{
		case PrintMelding:
			PrintMelding($line);
		break;

		case PrintLinie:
			PrintMeldingLinie($line, $l_no++);
		break;

		default;
			print("Wrong parameter: $DetailLevel<br>\n");
		break;
		}
	}

if($DetailLevel == PrintLinie)
{
	printf("</TABLE>");
}


// Free resultset
mysql_free_result($result);
?>

		</div>
<?php
bottom();
}


/****************************************************************
 * 								*
 * function PrintMeldingLinie($Signal, $l_no)			*
 * 								*
 * This function is looking for the following input:		*
 * $Signal, $l_no <lineno>					*
 ***************************************************************/


function PrintMeldingLinie($Signal, $l_no)
{	
	// Performing SQL query
	$modt_query = 	"SELECT enheder.id, enheder.navn ".
			"FROM enheder, modtagere ".
			"WHERE modtagere.signalID=".$Signal[0]." AND enheder.id=modtagere.enhederID";

	// echo $modt_query."---".$Signal[0]."<br>";
	$result_2 = DebugQuery($modt_query);
	
	$egenenhed= $_SESSION["userObj"]->userEgenEnhed();
	$egennavn= "";
	$modtnavn= "";
	$allemodtagere= "";
	$antalmodt= 0;
	while ($modt = mysql_fetch_row($result_2)) {
		$allemodtagere.= ((strlen($allemodtagere) == 0)?"":"; ").$modt[1];
		$antalmodt++;
		if (stripcslashes($modt[0]) == $egenenhed)
			$egennavn= stripcslashes($modt[1]);
		if(strlen($modtnavn) == 0) $modtnavn= $modt[1];
		}
	// echo $egennavn."-A-".$modtnavn."-B-".$modtnavn2."<br>";
	// Free resultset
	mysql_free_result($result_2);

	if (($l_no % 2) != 0)
		$line_class= "odd";
	else
		$line_class= "even";
		
// Find latest foretaget.

// Performing SQL query. Find all foretaget for this signal.
	$fore_query = 	"SELECT "
			.' over_bool'			// 0
			.', side_bool'			// 1
			.', under_bool'			// 2
			.', journ_bool'			// 3
			.', sitkort_bool'		// 4
			.', rund_bool '			// 5
			." FROM foretaget "
			." WHERE signal_ID=".$Signal[0]
			." ORDER BY foretaget_tid DESC";

	$fore_result = DebugQuery($fore_query);
	$f_res = mysql_fetch_row($fore_result);

	$Foretaget= ($f_res[0]?"F":"-").($f_res[1]?"S":"-").($f_res[2]?"U":"-").($f_res[3]?"J":"-").($f_res[4]?"K":"-").($f_res[5]?"R":"-");
// echo "---$Foretaget\n";

$printformat= "%-8s  %-16.16s  %-12.12s  %-12.12s  %-12.12s  %-6s  %-50s";

				
	printf("<TR class=%s><TD><A class= norma HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">%8s</A>"
		."<TD><A class= norma HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">%16s</A>"
		."<TD><A class= norma HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">%12s"
		."<TD><A class= norma HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">"
		.(($antalmodt >= 2)?"<ACRONYM TITLE=\"%s\">%12s</ACRONYM>":"%s%12s")
		."<TD><A class= norma HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">%6s"
		."<TD><A class= norma HREF=\"main.php?M_action=VisMelding&amp;jour_id=%s\">%50s\n",
		$line_class, $Signal[0], $Signal[0]
		, $Signal[0], stripcslashes($Signal[2])
		, $Signal[0], stripcslashes($Signal[3])
		, $Signal[0], (($antalmodt >= 2)?$allemodtagere:""), ($egennavn=="")?$modtnavn:$egennavn
		, $Signal[0], $Foretaget
		, $Signal[0],  stripcslashes($Signal[4]));
		

}


/****************************************************************
 * 								*
 * function PrintMelding($Signal)				*
 * 								*
 * This function is looking for the following input:		*
 * $Signal							*
 ***************************************************************/


function PrintMelding($Signal)
{

/*
Løbenummer: xxx
Fortrinsret: Rutine DTG: 211212Aokt07
Fra: xxxxxxxxxx
Til: xxxxxxxxx; xxxxxxxxx; xxxxxxxxxx; xccxxdxxx;
Info: dsfdsfds; dsfadsfdasf; dsfdsfds; sgfadfg;
Overskrift: "hgb jhgbj hbgjhbg jhg jhg jhg jhg jgjhg jhg j"
Tekst: "jhkjh jkh jkh kjh kjh kj hkj hkj hk"
Indtastet af: jhvkjfdhvjk tid
Noter rettet af xxx tid
"jgjhghjgjhghjg"
Foretaget: FSUJKR Senest rettet af: jhghjgfhfhjgf tid
O: hjhgghfhgfhjgh
S: hjhgghfhgfhjgh
U: hjhgghfhgfhjgh
J: hjhgghfhgfhjgh
F: hjhgghfhgfhjgh
R: hjhgghfhgfhjgh
*/
//	print_r($Signal);
	printf("<P class=adresser><FONT class=felt>L&oslash;benummer:</FONT> %s</P>\n", $Signal[0]);

	switch($Signal[1])
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

	printf("<P class=adresser><FONT class=felt>Fortrinsret:</FONT> %s <FONT class=felt>DTG:</FONT> %s</P>\n", $T_prio, stripcslashes($Signal[2]));
	printf("<P class=adresser><FONT class=felt>Fra:</FONT> %s</P>\n", nl2br(stripcslashes($Signal[3])));
	printf("<P class=adresser><FONT class=felt>Til:</FONT> ");

	// Performing SQL query
	$modt_query = 	"SELECT enheder.navn ".
			"FROM enheder, modtagere ".
			"WHERE modtagere.signalID=$Signal[0] AND enheder.id=modtagere.enhederID";

	$modt_result = DebugQuery($modt_query);

	while ($modt= mysql_fetch_row($modt_result))
	{
		printf("%s; ", nl2br(stripcslashes($modt[0])));
	}

	printf("</P>\n");
	printf("<P class=adresser><FONT class=felt>Info:</FONT> ");

// Performing SQL query
	$info_query = 	"SELECT enheder.navn ".
			"FROM enheder, info ".
			"WHERE info.signalID=$Signal[0] AND enheder.id=info.enhederID";

	$info_result = DebugQuery($info_query);

	while ($info= mysql_fetch_row($info_result))
	{
		printf("%s; ", nl2br(stripcslashes($info[0])));
	}

	printf("</P>\n");

	printf("<P><FONT class=felt>Overskrift:</FONT> %s</P>\n", nl2br(stripcslashes($Signal[4])));
	printf("<P><FONT class=felt>Tekst:</FONT> %s</P>\n", nl2br(stripcslashes($Signal[5])));

	if ($Signal[6] != 0)
		{
		// Performing SQL query
		$indt_query = 	"SELECT personel.navn ".
				"FROM personel ".
				"WHERE personel.id=".$Signal[6];
		// echo $indt_query."---".$keyop;
		$result = DebugQuery($indt_query);
		$indt = mysql_fetch_row($result);

		$indtastnavn= stripcslashes($indt[0]);

		// Free resultset
		mysql_free_result($result);
		}

	printf("<P><FONT class=felt>Signalmiddel:</FONT> %s <FONT class=felt>Indtastet af:</FONT> %-s; %s; %s</P>\n"
		, $Signal[9], $indtastnavn, $Signal[7], $Signal[8]!=0?"Rettet ".$Signal[8]." gange.":"Ikke rettet.");



	// Performing SQL query
	$query = 	"SELECT note_ID"		/* 0 */
			.", note_tid"		/* 1 */
			.", note_text "		/* 2 */
			." FROM noter "
			." WHERE signal_ID=".$Signal[0]
			." ORDER BY note_tid";

	// echo $query."---".$user;
	$note_result = DebugQuery($query);

	// Printing results in HTML


printf("<P><FONT class=felt>Ekstra noter.</FONT><br>");

	while ($note = mysql_fetch_row($note_result)) {
	$name_query =   "SELECT personel.navn ".
			"FROM personel ".
			"WHERE personel.id=".$note[0];
	// echo $indt_query."---".$keyop;
	$name_result = DebugQuery($name_query);
	$name = mysql_fetch_row($name_result);

	$foretag= $fore[0];
		printf("%s; %s<br>\n", $name[0], $note[1]);
		printf("<P>%-s</P>", nl2br(stripcslashes($note[2])));
		}
// Free resultset
mysql_free_result($note_result);



	VisLegitimation($Signal[0]);


// Performing SQL query
	$query = 	"SELECT foretagetID"			/* 0 */
			.", foretaget_tid"			/* 1 */
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
			." WHERE signal_ID=".$Signal[0]
			." ORDER BY foretaget_tid";

	// echo $query."---".$user;
	$fore_result = DebugQuery($query);

	// Printing results in HTML


printf("<P><FONT class=felt>Foretaget.</FONT><br>");


	while ($foretag = mysql_fetch_row($fore_result)) {
	$name_query =   "SELECT personel.navn ".
			"FROM personel ".
			"WHERE personel.id=".$foretag[0];
	// echo $indt_query."---".$keyop;
	$name_result = DebugQuery($name_query);
	$name = mysql_fetch_row($name_result);

	printf("%s; %s<br>\n", stripcslashes($name[0]), $foretag[1]);
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
	}
// Free resultset
mysql_free_result($fore_result);

	printf("<HR>\n\n");

// Free resultset
	mysql_free_result($modt_result);
	mysql_free_result($info_result);
}

?>
