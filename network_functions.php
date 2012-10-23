<?php
/****************************************************************
//  File: network_functions.php					*
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
//  This file holds network related				*
//  functions used in the system				*
//	$Id$							*
//								*
//**************************************************************/


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

function IP_cmp($IP_range_start, $IP_single, $IP_mask= "255.255.255.0")
{
//echo "$IP_range_start, <br> $IP_mask, <br> $IP_single <br>";
// Convert to long nunbers.
    $I_range= ip2long($IP_range_start);
//echo "I_range:$I_range <br>";
    $I_mask= ip2long($IP_mask);
//echo "I_mask: $I_mask <br>";
    $I_single= ip2long($IP_single);
//echo "I_single: $I_single <br>";
    
// Now calculate.
//    $I_lo= $I_range&$I_mask;
//$iplo= long2ip($I_lo);
//echo "I_lo: $I_lo, $iplo <br>";
//    $I_hi= $I_range| (~$I_mask);
//$iphi= long2ip($I_hi);
//echo "I_hi: $I_hi, $iphi <br>";
	$I_ar= $I_range & $I_mask;
	$I_as= $I_single & $I_mask;


// Ok, let us compare.
//$r= $I_single >= $I_lo && $I_single <= $I_hi;
//echo "Return: $r, $I_single >= $I_lo && $I_single <= $I_hi <br>";
    return($I_ar == $I_as);
}


//***************************************************************
//								*
//	IP_broadcast( <IP>, <MASK> )				*
//								*
//	Returns the broadcast address for the defined subnet.	*
//								*
//	<IP>	is ANY IP inside the subnet.			*
//	<MASK>	is the subnet mask in dotted notation.		*
//								*
//**************************************************************/

function IP_broadcast( $IP, $MASK= "255.255.255.0")
{
// Convert to long.
    $I= ip2long($IP);
    $I_mask= ip2long($MASK);

// Calculate.
    $I_hi= $I| (~$I_mask);

// Convert back.
    $IP_br= long2ip($I_hi);
//echo "I_hi: $I_hi, $IP_br <br>";
    return($IP_br);
}
?>
