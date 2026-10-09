<?php

// Add PLM Module to Menu System

// Register new scripts
//NewScript('Manufacturers.php', 15));
//NewScript('SelectManufacturer.php', 15));
//NewScript('Z_ImportManufacturers.php', 15));
//NewScript('Z_ImportStockFilsUrls.php', 15));

// Register PLM Module
NewModule('plm', 'PLM', __('Product Lifecycle Mgmt'), 8);

// Build PLM menu
// Transactions pane ("Transactions")
NewMenuItem('plm', 'Maintenance', __('Inventory Adjustments'), '/StockAdjustments.php?NewAdjustment=Yes', 1);

// Inquiries and Reports pane ("Reports")
NewMenuItem('plm', 'Reports', __('Serial Item Research Tool'), '/StockSerialItemResearch.php', 1);
NewMenuItem('plm', 'Reports', __('Inventory Item Movements'), '/StockMovements.php', 2);
NewMenuItem('plm', 'Reports', __('Inventory Item Status'), '/StockStatus.php', 3);
NewMenuItem('plm', 'Reports', __('Inventory Item Usage'), '/StockUsage.php', 4);
NewMenuItem('plm', 'Reports', __('Inventory Quantities'), '/InventoryQuantities.php', 5);

NewMenuItem('plm', 'Reports', __('Costed BOM Inquiry'), '/BOMInquiry.php', 6);
NewMenuItem('plm', 'Reports', __('Where Used Inquiry'), '/WhereUsedInquiry.php', 7);
NewMenuItem('plm', 'Reports', __('BOM Listing'), '/BOMListing.php', 8);
NewMenuItem('plm', 'Reports', __('BOM Listing Indented'), '/BOMIndented.php', 9);
NewMenuItem('plm', 'Reports', __('List Components Required'), '/BOMExtendedQty.php', 10);
NewMenuItem('plm', 'Reports', __('List Materials Not Used Anywhere'), '/MaterialsNotUsed.php', 11);
NewMenuItem('plm', 'Reports', __('Where Used Inquiry Indented'), '/BOMIndentedReverse.php', 12);

NewMenuItem('plm', 'Reports', __('Print Product Specification'), '/PDFProdSpec.php', 13);
NewMenuItem('plm', 'Reports', __('Print Certificate of Analysis'), '/PDFCOA.php', 14);
NewMenuItem('plm', 'Reports', __('Historical QA Test Results'), '/HistoricalTestResults.php', 15);

// Maintenance pane ("Maintenance")



// Migrate data (e.g. HRM update 53.php)
// $SQL = "INSERT INTO hremployees
// 	(employeenumber, firstname, lastname, email, createdby, hiredate)
// 	SELECT
// 		CONCAT('EMP', LPAD(id, 5, '0')) as employeenumber,
// 		firstname,
// 		surname as lastname,
// 		email,
// 		'system' as createdby,
// 		CURDATE() as hiredate
// 	FROM employees
// 	WHERE NOT EXISTS (
// 		SELECT 1 FROM hremployees WHERE hremployees.employeenumber = CONCAT('EMP', LPAD(employees.id, 5, '0'))
// 	)";
// $Result = DB_query($SQL, '', '', false, false);

// Add PLM visibility flag to existing users's modulesallowed values (e.g. HRM visibility update 54.php)
// $SQL = "SELECT userid, modulesallowed FROM www_users";
// $Result = DB_query($SQL);

// while ($MyRow = DB_fetch_array($Result)) {
// 	if (mb_strlen($MyRow['modulesallowed']) < 26) {
// 		$StringLength = mb_strlen($MyRow['modulesallowed']);
// 		$NewModulesAllowed = mb_substr($MyRow['modulesallowed'], 0, 16) . '1,' . mb_substr($MyRow['modulesallowed'], 16, ($StringLength - 9));
// 		UpdateField('www_users', 'modulesallowed', $NewModulesAllowed, 'userid="' . $MyRow['userid'] . '"');
// 	}
// }

// 5. cleanup
// unlike a project schema update, a custom update does not record its execution in the webERP update history
// TODO fix this!
//if ($_SESSION['Updates']['Errors'] == 0) {
//	UpdateDBNo(basename(__FILE__, '.php'), __('Add PLM Module to menu'));
//}
