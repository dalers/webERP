<?php

// Add the PLM module display flag to existing users
// (access by .../weberp/Z_UpgradeDatabase.php?update=4-add_plm_modulesallowed.php)
//
// The user's modulesallowed field is a comma separated list of 1/0 flags, one per module
// (see WWW_Users.php). The PLM flag is the 14th and last flag. Existing users with the
// standard 13 flags are given PLM = 1 (the PLM module is still only shown if their
// security role has the PLM module).

$SQL = "SELECT userid, modulesallowed FROM www_users";
$Result = DB_query($SQL);

while ($MyRow = DB_fetch_array($Result)) {
	$Flags = explode(',', rtrim($MyRow['modulesallowed'], ','));
	if (count($Flags) == 13) {
		$NewModulesAllowed = implode(',', $Flags) . ',1,';
		UpdateField('www_users', 'modulesallowed', $NewModulesAllowed, 'userid="' . $MyRow['userid'] . '"');
	}
}
