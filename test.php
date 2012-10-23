<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html"; charset=utf-8">
	<title>Test af journal.ini</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Sten Carlsen">
	<!-- Date: 2007-01-13 -->
</head>
<body>
	<?php
	if(strlen($s0= $_REQUEST["TString"]) > 0)
	{
		$pp= $_REQUEST["TPattern"];
		$s0= strtolower($s0);
		
//		$s1= preg_grep($pp, $s0);
		$s2= preg_match_all($pp, $s0, $matches2);
		$s3= preg_match($pp, $s0, $matches3, PREG_OFFSET_CAPTURE);
//		$s4= ereg($pp, $s0);
		
		echo "<p>Input: ".$s0."</p>";
		echo "<p>Pattern: ".$pp."</p>";
//		echo "<p>Output(preg_grep): ".$s1."</p>";
		echo "<p>Output(preg_match_all): ".$s2.htmlentities(print_r($matches2, true))."</p>";
		echo "<p>Output(preg_match): ".$s3.htmlentities(print_r($matches3, true))."</p>";
//		echo "<p>Output(ereg): ".$s4."</p>";
		// print_r($matches);
	}
	?>
	<form action="test.php" method="get" accept-charset="utf-8" onsubmit="mySetSize()">
		<input type="text" name="TString" value="" id="TString">
		<input type="text" name="TPattern" value="<?php echo $pp; ?>" id="TPattern">

		<p><input type="submit" value="Continue &rarr;"></p>
	</form>
	<PRE>
	<?php
	require_once("TimeFunctions.php");


	// Parse with sections
	$ini_array = parse_ini_file("journal.ini", true);
	print_r($ini_array);

$dtg="140247cjan07";
printf("DTG: %s ===> %s\n", $dtg, t_stamp($dtg));

$dtg= t_dtg("UTC");
echo $dtg."-----------\n";
printf("DTG: %s ===> %s\n", $dtg, t_stamp($dtg));

$dtg="132347Zjan07";
printf("DTG: %s ===> %s\n", $dtg, t_stamp($dtg));

	?>
	</PRE>
</body>
</html>
