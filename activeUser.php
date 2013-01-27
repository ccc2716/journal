<?php

interface iUser
{
	public function userRightsCheck($rights);
	public function userIsAdmin();
	public function userIsLegal();		// $user <> 0; and user has a session = logged in..
	public function userMayWrite();		// Does this user have write access?
	public function userMayWriteEnhed();
	public function userMayWritePersonel();
	public function userDirection();	// User prefers newest at top or bottom.
	public function userSetDirection($dir);	// User prefers newest at top or bottom.
	public function userNumber();		// Returns $user (users number)
	public function userEgenEnhed();	// Returns $user (users Enhed)
	public function userCanChangeMessage($key_op, $indtast_tid);	// Time and user allow correction of message
	public function userTemaSet($tema);	// Set tema
	public function userTemaFile();		// Get tema-> css-filename
	public function userTema();		// Get tema-> index
}

require_once("activeUserImplement.php");
?>
