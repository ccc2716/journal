<?php

class activeUser implements iUser
{
	private $userRights;
	private $userDir;	//newest "top"; newest "bottom".
	private $userNumber;
	private $userEgenEnhed;
	private $AccessArray;
	private $RetteArray;
	private $DirArray;
	private $Tema;
	private $TemaFile;
	private $TemaArray;

	//***************************************************************
	//								*
	//	IP_cmp( <IP>, <IP_single>, <MASK> );			*
	//								*
	//	Used for comparing a subnet with an IP.			*
	//	<IP>		is ANY address within the subnet.	*
	//	<MASK>		is the subnet mask in dotted notation.	*
	//	<IP_single>	is the address you want know is inside	*
	//			or outside the subnet.			*
	//								*
	//	MASK defaults to class C.				*
	//	Returns true if IP_single is in the subnet defined by	*
	//	IP and MASK						*
	//								*
	//**************************************************************/

	private function IP_cmp($IP_range_start, $IP_single, $IP_mask= "255.255.255.0")
	{
		// Convert to long nunbers.
		$I_range= ip2long($IP_range_start);
		$I_mask= ip2long($IP_mask);
		$I_single= ip2long($IP_single);

		// Now calculate.
		$I_ar= $I_range & $I_mask;
		$I_as= $I_single & $I_mask;

		if ($_SESSION["Debug"]->debugActive(512))
		{
			$d= sprintf("IP_cmp: start: %x, single: %x, mask: %x, I_ar: %x, I_as: %x, ret: %s\n", $I_range, $I_single, $I_mask, $I_ar, $I_as, (($I_ar == $I_as)?"True":"False"));
			$_SESSION["Debug"]->debugFileWrite($d);
		}
		// Ok, let us compare.
		return($I_ar == $I_as);
	}



	function __construct($iArray)
	{	
		$this->DirArray= $iArray["display"];
		$this->RetteArray= $iArray["rettelser"];
		$this->AccessArray= $iArray["access"];
		$this->TemaArray= $iArray["Tema"];

		if(isset($_SESSION['User']))
		{
			$this->userNumber= ($_SESSION['User']);
		}
		else
		{
			$this->userNumber= 0;
		}

		if(isset($_SESSION['EgenEnhed']))
		{
			$this->userEgenEnhed= ($_SESSION['EgenEnhed']);
		}
		else
		{
			$this->userEgenEnhed= 0;
		}
		
		$this->TemaFile= $this->TemaArray["tema1"];

		if($this->userIsLegal())
		{
			// Performing SQL query
			$query = 'SELECT admin, direction, tema FROM personel WHERE personel.id='.$this->userNumber;
			// echo $query;
			$result = DebugQuery($query);
			$line = mysql_fetch_row($result);
			// Free resultset
			mysql_free_result($result);

			if ($_SESSION["Debug"]->debugActive(16))
				{
					$d= "Rights: ".$line[0]."\n";
					$_SESSION["Debug"]->debugFileWrite($d);
				}
			$this->userRights= $line[0]+0;
			if(strlen($line[1]) == 0)
			{
				$this->userSetDirection($this->DirArray['Direction']);
			}
			else
			$this->userDir= $line[1];
			
			$this->userTemaSet($line[2]);
			switch($this->Tema)
			{
				case 3:
					$this->TemaFile= $this->TemaArray["tema3"];
					break;
				
				case 2:
					$this->TemaFile= $this->TemaArray["tema2"];
					break;
				
				default:
				case 1:
					$this->TemaFile= $this->TemaArray["tema1"];
					break;
			}
		}
		else
		{
			$this->userRights= 0;
			$this->userDir= ($this->DirArray['Direction']);
		}

/*************************************
SESSION:
User
EgenEnhed
DbSelected
direction
**************************************/
	}

	public function userRightsCheck($rights)
	{
		return $this->userRights & $rights;
	}


	public function userIsAdmin()
	{
		return $this->userRights & RIGHTS_Admin;
	}

	public function userIsLegal()		// $user <> 0; and user has a session = logged in.
	{
		return($this->userNumber <> 0);
	}

	/****************************************************************
	//								*
	//  function userMayWrite()					*
	//								*
	//  Function to answer:						*
	//	is this client allowed to write to the journal		*
	//								*
	//***************************************************************/

	public function userMayWrite(){
	$rights= $this->userRights;

	$WR=  ($rights & RIGHTS_Admin) || ($rights & RIGHTS_ReadWrite);

	switch($this->AccessArray["Access"]){
		case "all":
			$MayWr= $WR;
			break;

		case "same":
		default:
			if ($_SESSION["Debug"]->debugActive(512))
			{
				$d= "userMayWrite, ".$_SERVER["SERVER_ADDR"].", ".$_SERVER["REMOTE_ADDR"].", ".$this->AccessArray["IpSeg"]."\n";
				$_SESSION["Debug"]->debugFileWrite($d);
			}


			if ( $this->IP_cmp($_SERVER["SERVER_ADDR"], $_SERVER["REMOTE_ADDR"], $this->AccessArray["IpSeg"]))
			{
				// Client is in the same subnet as server, this gives write access.
				$MayWr= $WR;
			}
			else
			{
				// Client is NOT in the same subnet as server, this gives readonly access.
				$MayWr= False;
			}
			break;
		}

		if(!($this->userIsLegal())) $MayWr= False;

	if ($_SESSION["Debug"]->debugActive(512))
	{
		$d= "userMayWrite(return), ".($MayWr?"True":"False")."\n";
		$_SESSION["Debug"]->debugFileWrite($d);
	}
	return $MayWr;
	}

	public function userDirection()		// User prefers newest at top or bottom.
	{
		return $this->userDir;
	}
	
	public function userSetDirection($dir)	// User prefers newest at top or bottom.
	{
		$this->userDir= $dir;
		$user= $this->userNumber;
		if($this->userIsLegal())
		{
			// Performing SQL query
			$query = 'UPDATE personel SET direction= \''.$dir.'\' WHERE personel.id='.$user;
			$result = DebugQuery($query);
			// mysql_free_result($result);

		}
	}
	
	public function userNumber()		// Returns $user (users number)
	{
		return $this->userNumber;
	}

	public function userEgenEnhed()		// Returns $user (users Enhed)
	{
		return $this->userEgenEnhed;
	}
	
	
	public function userCanChangeMessage($key_op, $indtast_tid)	// Time and user allow correction of message
	{
		$user= $this->userNumber;
		$rights= $this->userRights;
		
		$UserOk= false;
		$TimeOk= false;
	// Check if user is allowed to correct this signal. Administrator can not change anything.
		switch($this->RetteArray["RetteAnsvar"]){
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
		if ($this->RetteArray["RetteTid"] == 0)
			$TimeOk= true;
		else
			if ($this->RetteArray["RetteTid"] < 0)
				$TimeOk= false;
			else
				$TimeOk= $TimeGone <= $this->RetteArray["RetteTid"];

		return($UserOk && $TimeOk);
	}

	public function userMayWriteEnhed()
	{
		return (((($this->userRights & RIGHTS_Admin) || ($this->userRights & RIGHTS_Enheder)) && $this->userMayWrite())
			|| ($this->userRights & RIGHTS_System));
		
	}
	
	public function userMayWritePersonel()
	{
		return (((($this->userRights & RIGHTS_Admin) || ($this->userRights & RIGHTS_Personel)) && $this->userMayWrite())
			|| ($this->userRights & RIGHTS_System));
	}

	public function userTemaSet($tema)	// Set tema $tema is int.
	{
		$this->Tema= $tema;
		
		$temaQuery= "UPDATE personel".
			" SET tema = '$tema'".
			" WHERE id = '$this->userNumber';";
		
		$result = DebugQuery($temaQuery);
		
		switch($tema)
		{
			case 2:
				$this->TemaFile= $this->TemaArray["tema2"];
				break;

			case 3:
				$this->TemaFile= $this->TemaArray["tema3"];
				break;

			case 1:
			default:
				$this->TemaFile= $this->TemaArray["tema1"];
				break;
		}
	}
	
		public function userTemaFile()		// Get tema-> css-filename
	{
		return $this->TemaFile;
	}
	
		public function userTema()		// Get tema-> css-filename
	{
		return $this->Tema;
	}
	
}

?>