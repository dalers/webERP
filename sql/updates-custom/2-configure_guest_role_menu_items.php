<?php

// Configure menu items for "Guest" user role
// Z_UpgradeDatabase.php?update=2-configure_guest_role_menu_items.php

// MANUALLY CREATE "Guest" USER ROLE _BEFORE_ RUNNING THIS UPDATE (otherwise the menu
// items will not be extended to it).

// The "Guest (View Only)" user role menu contains items related to viewing items,
// BOMs and Suppliers (aka sources).

// Add menu item to module menu pane (remove and re-add to add module to new user roles)

// 1. Remove Menu Item
// function RemoveMenuItem($Link, $Section, $Caption, $URL)
//   $Link = 'modulename'
//   $Section = 'Transactions' (left) | 'Reports' pane (middle) | 'Maintenance' (right)
//   $Caption = __('Short Caption')
//   $URL = '/ScriptName.php?Argument=Value'
//
// RemoveMenuItem('plm', 'Maintenance', __('Review Translated Descriptions'), '/RevisionTranslations.php');
// RemoveMenuItem('plm', 'Maintenance', __('Inventory Adjustments'), '/StockAdjustments.php?NewAdjustment=Yes');

RemoveMenuItem('stock', 'Maintenance', __('Select An Item'), '/SelectProduct.php');
// it appears $Caption is necessary although redundant at this time. Possibly including $Caption was intended to support
// removing a menu item for a specific user role in the future (i.e. a unique menu-item_user-role combination), although
// complementary functionality would also need to be added to NewMenuItem().

// 2. Add Menu Item (will be added to all user roles)
// NewMenuItem($Link, $Section, $__('Short Caption'), $URL, $Sequence);
//   $Link = 'modulename' (e.g. 'stock', 'manuf')
//   $Section = 'Transactions' (left) | 'Reports' pane (middle) } 'Maintenance' (right)
//   $__('Short Caption') = 'Costed Bill of Material Inquiry'
//   $url = '/ScriptName.php?Argument=Value'
//   $sequence = 1... (1, 2, 3, 4....)
//
// NewMenuItem('plm', 'Reports', __('Serial Item Research Tool'), '/StockSerialItemResearch.php', 1);
// NewMenuItem('plm', 'Maintenance', __('Import Manufacturers from CSV'), '/Z_ImportManufacturers.php', 14); // TODO create script
// NewMenuItem('plm', 'Maintenance', __('Import Files/URLS from CSV'), '/Z_ImportStocksFilsUrls.php', 15); // TODO create script

NewMenuItem('stock', 'Maintenance', __('Select An Item'), '/SelectProduct.php', 2);
// NOTE the sequence number will be the same for all user roles although in practise each user role may have a different sequence
// number. Finding what the sequence number is for each user role requires direct interaction with the database. If different
// user roles have different sequence numbers, the affected sequence numbers will need to be corrected manually afterwards - possibly
// using the Menu Editor if it has this functionality.

// additional menu items - Manufacturing
RemoveMenuItem('manuf', 'Reports', __('Costed Bill Of Material Inquiry'), '/BOMInquiry.php');
NewMenuItem('manuf', 'Reports', __('Costed Bill Of Material Inquiry'), '/BOMInquiry.php', 2);

RemoveMenuItem('manuf', 'Reports', __('Where Used Inquiry'), '/WhereUsedInquiry.php');
NewMenuItem('manuf', 'Reports', __('Where Used Inquiry'), '/WhereUsedInquiry.php', 3);

RemoveMenuItem('manuf', 'Reports', __('Bill Of Material Listing'), '/BOMListing.php');
NewMenuItem('manuf', 'Reports', __('Bill Of Material Listing'), '/BOMListing.php', 4);

RemoveMenuItem('manuf', 'Reports', __('Indented Bill Of Material Listing'), '/BOMIndented.php');
NewMenuItem('manuf', 'Reports', __('Indented Bill Of Material Listing'), '/BOMIndented.php', 5);

RemoveMenuItem('manuf', 'Reports', __('Indented Where Used Listing'), '/BOMIndentedReverse.php');
NewMenuItem('manuf', 'Reports', __('Indented Where Used Listing'), '/BOMIndentedReverse.php', 9);

RemoveMenuItem('manuf', 'Reports', __('Print Product Specification'), '/PDFProdSpec.php');
NewMenuItem('manuf', 'Reports', __('Print Product Specification'), '/PDFProdSpec.php', 15);

RemoveMenuItem('manuf', 'Reports', __('Print Certificate of Analysis'), '/PDFCOA.php');
NewMenuItem('manuf', 'Reports', __('Print Certificate of Analysis'), '/PDFCOA.php', 16);

// additional menu items - Payables
RemoveMenuItem('AP', 'Maintenance', __('Select Supplier'), '/SelectSupplier.php');
NewMenuItem('AP', 'Maintenance', __('Select Supplier'), '/SelectSupplier.php', 3);

// additional menu items - Purchases
// desired to add Select Supplier menu item to Purchases module as "Purchases" is more intuitive for PLM
// users than "Payables" (although preferred module name is "Purchasing" or better "Sources")
// ERRORS RUNNING UPDATE "The menu link Select Supplier could not be inserted" - see screenshot
//NewMenuItem('PO', 'Maintenance', __('Select Supplier'), '/SelectSupplier.php', 3); // new menu item in Purchases module
