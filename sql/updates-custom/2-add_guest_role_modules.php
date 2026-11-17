<?php

// Add modules to the "Guest" user role (secroleid 10)
// Z_UpgradeDatabase.php?update=2-add_guest_role_modules.php

// Writes the module rows for security role 10 to the modules table. Roles
// already present for the role/module are skipped.

$GuestModules = array(
	array(10, 'AP', 'ap', 'Payables', 4),
	array(10, 'AR', 'ar', 'Receivables', 2),
	array(10, 'FA', 'fa', 'Asset Manager', 9),
	array(10, 'for', 'Fcst', 'Forecasting', 7),
	array(10, 'GL', 'gl', 'General Ledger', 8),
	array(10, 'manuf', 'man', 'Manufacturing', 6),
	array(10, 'PC', 'pc', 'Petty Cash', 10),
	array(10, 'PO', 'prch', 'Purchases', 3),
	array(10, 'Sales', 'ord', 'Sales', 1),
	array(10, 'stock', 'inv', 'Inventory', 5),
	array(10, 'system', 'sys', 'Setup', 11),
	array(10, 'Utilities', 'util', 'Utilities', 12)
);

foreach ($GuestModules as $Module) {
	InsertRecord(
		'modules',
		array('secroleid', 'modulelink'),
		array($Module[0], $Module[1]),
		array('secroleid', 'modulelink', 'reportlink', 'modulename', 'sequence'),
		$Module
	);
}
