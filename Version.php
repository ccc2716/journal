<?php
/****************************************************************
//  File: Version.php						*
//								*
//  This file is a part of the "journal system for HJV"		*
//								*
//  Copyright Sten Carlsen 2006, 2007				*
//    								*
//  Journal-systemet is free software: you can redistribute it	*
//  and/or modify it under the terms of the GNU General Public	*
//  License as published by the Free Software Foundation, either*
//  version 3 of the License, or (at your option) any later	*
//  version.							*
//								*
//  Journal-systemet is distributed in the hope that it will be	*
//  useful, but WITHOUT ANY WARRANTY; without even the implied	*
//  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR	*
//  PURPOSE.See the GNU General Public License for more details.*
//								*
//  You should have received a copy of the GNU General Public	*
//  License along with Journal-systemet. If not, see		*
//  <http://www.gnu.org/licenses/>.				*
//    								*
//  This file holds the system version number			*
//  and some constants						*
//								*
//	$Id$							*
//								*
//**************************************************************/


/******************************************
Her gemmes Versionsnummeret.

******************************************/

define("Version",	"4.1.0");		// Version number.
define("JournalName",	"journv4");
define("JournalSecret",	"secretv4");

/*****************************************
PrintJournal constants
*****************************************/
define("PrintMelding",	1);
define("PrintLinie",	2);

/*****************************************
Menu BitMap cnstants
*****************************************/
define('iNyMelding',		0x0001);
define('iVisJournal',		0x0002);
define('iPrintJournal',		0x0004);
define('iPrintHelJournal',	0x0008);
define('iAdmin',		0x0010);
define('iNyPersonel',		0x0020);
define('iListPersonel',		0x0040);
define('iNyEnhed',		0x0080);
define('iListEnheder',		0x0100);
define('iListLog',		0x0200);
define('iBackup',		0x0400);
define('iLogOut',		0x8000);

?>
