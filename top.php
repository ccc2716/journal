<?php
/********************************************************
*							*
*	top.php						*
*							*
* Handles all needed functions before any output,	*
* the Header and the menu line.				*
*							*
*							*
*********************************************************/


function top($bodyfill= "")
{
	global $TemaArray;
	
	$menumask= 0;
	
//	Which menus can be shown in each function?
	switch($_REQUEST['M_action'])
	{
		case "SelUser":
			$PageTitle= "V&aelig;lg bruger fra liste:";
			$timeout= 0;
			$menumask= 0;
		break;

		case "Error":
			$PageTitle= "FEJL:";
			$timeout= 0;
			$menumask= 0;
		break;

		case "PrintJournal":
			$PageTitle= "Print alle signaler:";
			$timeout= 0;
			$menumask= NyMelding | PrintHele | VisJournal | LogUd;
		break;

		case "PrintHelJournal":
			$PageTitle= "Print alle signaler:";
			$timeout= 0;
			$menumask= NyMelding | VisHele | VisJournal | LogUd;
		break;

		case "VisJournal":
		case "Main":
			$PageTitle= "Vis Journal:";
			$timeout= 120;
			$menumask= NyMelding | VisHele | Admin | LogUd;
		break;

		case "NyMelding":
			$PageTitle= "Indtast ny melding:";
			$timeout= 0;
			$menumask= VisJournal | LogUd;
		break;

		case "NyCBRNnuc":
			$PageTitle= "Indtast ny CBRN(nuc) melding:";
			$timeout= 0;
			$menumask= VisJournal | LogUd;
		break;

		case "NyCBRNchem":
			$PageTitle= "Indtast ny CBRN(chem) melding:";
			$timeout= 0;
			$menumask= VisJournal | LogUd;
		break;

		case "VisMelding":
			$PageTitle= "Vis Journal:";
			$timeout= 0;
			$menumask= NyMelding | VisHele | VisJournal | LogUd;
		break;

		case "NyPersonel":
			$PageTitle= "Opret nyt personel:";
			$timeout= 0;
			$menumask= NyMelding | ListPers | VisJournal | Admin | LogUd;
		break;

		case "VisPersonel":
			$PageTitle= "Vis personel:";
			$timeout= 0;
			$menumask= NyMelding | NyPers | VisJournal | Admin | LogUd;
		break;

		case "VisEnPersonel":
			$PageTitle= "Vis en personel:";
			$timeout= 0;
			$menumask= NyMelding | NyPers | ListPers | VisJournal | Admin | LogUd;
		break;

		case "RetPersonel":
			$PageTitle= "Ret personel:";
			$timeout= 0;
			$menumask= NyMelding | NyPers | ListPers | VisJournal | Admin | LogUd;
		break;

		case "NyJournal":
			$PageTitle= "Opret ny journal:";
			$timeout= 0;
			$menumask= Forfra;
		break;

		case "NyEnhed":
			$PageTitle= "Opret ny enhed:";
			$timeout= 0;
			$menumask= NyMelding | ListEnh | VisJournal | Admin | LogUd;
		break;

		case "RetEnhed":
			$PageTitle= "Ret enhed:";
			$timeout= 0;
			$menumask= NyMelding | NyEnh | ListEnh | VisJournal | Admin | LogUd;
		break;

		case "VisEnhed":
			$PageTitle= "Vis enheder:";
			$timeout= 0;
			$menumask= NyMelding | NyEnh | VisJournal | Admin | LogUd;
		break;

		case "VisEnEnhed":
			$PageTitle= "Vis enhed:";
			$timeout= 0;
			$menumask= NyMelding | NyEnh | ListEnh | VisJournal | Admin | LogUd;
		break;

		case "Admin":
			$PageTitle= "Administration af personel og enheder:";
			$timeout= 0;
			$menumask= NyMelding | VisJournal | VisLog | LogUd | Backup;
		break;

		case "VisLog":
			$PageTitle= "Vis log:";
			$timeout= 0;
			$menumask= Admin | VisJournal | LogUd;
		break;

		case "Backup":
//			DownloadFile();
		break;

		case "SelDb":
			$PageTitle= "V&aelig;lg journal fra listen:";
			$timeout= 0;
			$menumask= NyJournal;
		break;

		case "LogUd":
		default:
			$PageTitle= "Journalprogram for brug i hjemmeværnet.";
			$timeout= 0;
			$menumask= 0;
		break;
	}
	
//	Remove menus if user has no write rights
	if((isset($_SESSION["userObj"])) && (!$_SESSION["userObj"]->userMayWrite()))
	{
		$menumask &= ~(NyMelding | NyPers | NyEnh);
	}
	
	if((isset($_SESSION["userObj"])) && (!$_SESSION["userObj"]->userMayWritePersonel()))
	{
		$menumask &= ~NyPers;
	}
	
	if((isset($_SESSION["userObj"])) && (!$_SESSION["userObj"]->userMayWriteEnhed()))
	{
		$menumask &= ~NyEnh;
	}
	
//	Adjust menu selection if user is system.
	if((isset($_SESSION["userObj"])) && ($_SESSION["userObj"]->userRightsCheck(RIGHTS_System)))
	{
		$menumask &= ~NyMelding;
	}
	else
	{
		$menumask &= ~(VisLog | Backup);
	}
		
	
	$journal= $_SESSION["DbSelected"];
	if(isset($_SESSION["userObj"]))
	{
		$temaf= $_SESSION["userObj"]->userTemaFile();
	}
	else
	{
		$temaf= $TemaArray["tema1"];
	}

// DOC type and <head>

	

	printf("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\"\n\"http://www.w3.org/TR/html4/strict.dtd\">\n");
	printf("<html>\n<head>\n");
	printf("<meta http-equiv=\"content-type\" content=\"text/html\">\n");
	if($timeout != 0) printf("<meta http-equiv=\"refresh\" content=\"%d\">\n", $timeout);
	printf("<title>Journal: %s</title>\n", $journal);
	printf("<meta name=\"AUTHOR\" content=\"Sten Carlsen\">\n");
	printf("<link href=\"%s\" rel=\"stylesheet\" media=\"screen\" type=\"text/css\">\n", $temaf);
	printf("<link href=\"journal-print.css\" rel=\"stylesheet\" media=\"print\" type=\"text/css\">\n");
	// printf("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF8\">");
	printf("</head>\n");
	
// <body>

	printf("<body %s>\n", $bodyfill);
	
	printf('<div class="top">'."\n");
	printf('<ul class="menu">'."\n");
	printf("<li class=menu><font class=action>%s</font></li>\n", $PageTitle);
	if($menumask & NyMelding)	printf("<li class=\"menu\"><a class=menu href=\"main.php?M_action=NyMelding\">Indtast ny melding.</a></li>\n");
	if($menumask & NyPers)		printf("<li class=\"menu\"><a class=menu href=\"main.php?M_action=NyPersonel\">Ny personel.</a></li>\n");
	if($menumask & ListPers)	printf("<li class=\"menu\"><a class=menu href=\"main.php?M_action=VisPersonel\">List personel.</a></li>\n");
	if($menumask & NyEnh)		printf("<li class=\"menu\"><a class=menu href=\"main.php?M_action=NyEnhed\">Ny enhed.</a></li>\n");
	if($menumask & ListEnh)		printf("<li class=\"menu\"><a class=menu href=\"main.php?M_action=VisEnhed\">List enheder.</a></li>\n");
	if($menumask & VisJournal)	printf("<li class=\"menu\"><a class=menu href=\"main.php?M_action=VisJournal\">Vis journal.</a></li>\n");
	if($menumask & VisHele)		printf("<li class=\"menu\"><a class=menu href=\"main.php?M_action=PrintJournal\">Vis (print) hele journalen.</a>\n");
	if($menumask & PrintHele)	printf("<li class=\"menu\"><A class=menu HREF=\"main.php?M_action=PrintHelJournal&sort=%s&TextSearch=%s&SearchMode=%s\">Print alle signaler.</A></li>\n", $_REQUEST["sort"], $_REQUEST["TextSearch"], $_REQUEST['SearchMode']);
	if($menumask & Admin)		printf("<li class=\"menu\"><a class=menu href=\"main.php?M_action=Admin\">Administration.</a></li>\n");
	if($menumask & VisLog)		printf("<li class=\"menu\"><a class=menu href=\"main.php?M_action=VisLog\">Vis log.</a></li>\n");
	if($menumask & LogUd)		printf("<li class=\"menu\"><a class=logout href=\"main.php?M_action=LogUd\">Log Out.</a></li>\n");
	if($menumask & NyJournal)	printf("<li class=\"menu\"><a class=menu href=\"main.php?M_action=NyJournal\">Opret ny journal.</a></li>\n");
	if($menumask & Forfra)		printf("<li class=\"menu\"><A class=menu HREF=\"main.php\">Forfra.</A></li>\n");
	printf('</ul>'."\n");
	printf('</div>'."\n");
	
?>
<script type="text/javascript">

function mySetSize()
{
	var hsize= window.innerHeight;
	var x=document.getElementsByName("M_height");
	for (var i=0;i<x.length;i++)
	{
		x[i].value= hsize;
	}
}


</script>

<?php	

}


/********************************************************
*							*
* Definitions of bits for all possible menu-items.	*
*							*
*							*
*							*
********************************************************/

define("NyMelding",		0x0001);
define("VisJournal",		0x0002);
define("VisHele",		0x0004);
define("PrintHele",		0x0008);
define("Admin",			0x0010);
define("LogUd",			0x0020);
define("NyPers",		0x0040);
define("ListPers",		0x0080);
define("NyEnh",			0x0100);
define("ListEnh",		0x0200);
define("VisLog",		0x0400);
define("Backup",		0x0800);
define("NyJournal",		0x1000);
define("Forfra",		0x2000);


?>