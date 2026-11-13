<?php

// CUSTOM UPDATE TEMPLATE

// NAME - SHORT DESCRIPTION
//
// PARAGRAPH DESCRIPTION OF UPDATE, INCLUDE RELATED LINKS, ETC.
//    - BULLET POINT

// SECTION NAME AND SHORT DESCRIPTION
//    - BULLET POINT

// Refer to UpgradeDB include file e.g. UpgradeDB_mariadb.php for available functions

// Register new script and provide access to users with administrator security role
// NewScript($ScriptName, $PageSecurity, $Description)
//   $Description = __('HR management dashboard')
//   $PageSecurity = 15 (admin security role)

// Create new tables and constraints
// CreateTable($Table, $SQL)
// AddConstraint($Table, $Constraint, $Field, $ReferenceTable, $ReferenceField)

// Create new module
// NewModule($Link, $Report, $Name, $Sequence)

// Add (or remove and add again) menu item to module menu pane

// 1. Remove Menu Item
// function RemoveMenuItem($Link, $Section, $Caption, $URL)
//   $Link = 'modulename'
//   $Section = 'Transactions' (left) | 'Reports' pane (middle) | 'Maintenance' (right)
//   $Caption = __('Short Caption')
//   $URL = '/ScriptName.php?Argument=Value'
//
// RemoveMenuItem('plm', 'Maintenance', __('Review Translated Descriptions'), '/RevisionTranslations.php');
// RemoveMenuItem('plm', 'Maintenance', __('Inventory Adjustments'), '/StockAdjustments.php?NewAdjustment=Yes');

// 2. Add Menu Item (will be added to all user roles)
// NewMenuItem($Link, $Section, $__('Short Caption', $URL, $Sequence);
//   $Link = 'modulename'
//   $Section = 'Transactions' (left) | 'Reports' pane (middle) } 'Maintenance' (right)
//   $url = '/ScriptName.php?Argument=Value'
//   $sequence = 1... (1, 2, 3, 4....)

// Migrate data to new tables
// (if required, e.g. 53.php)

// Extend user's modules allowed string for them to have access to the new module
// e.g. 54.php (or manually update users through UI, which should have been udpated to include the new module)
