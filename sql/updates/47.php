<?php

// Rename "manufacturers" table to "brands"
// - no constraints exist
// - resolves https://github.com/timschofield/webERP/issues/678
// - also see https://github.com/timschofield/webERP/wiki/PLM-Features-Rename-manufacturers-table

// 1. make schema changes

// RenameTable($OldName, $NewName)
RenameTable('manufacturers', 'brands');

// rename brands table columns
// URL and image are not required when inserting data
// ChangeColumnName($OldName, $Table, $Type, $Null, $Default, $NewName, $AutoIncrement = '')
ChangeColumnName('manufacturers_id', 'brands', 'INT(11)', 'NOT NULL', '', 'brands_id', 'AUTO_INCREMENT');
ChangeColumnName('manufacturers_name', 'brands', 'VARCHAR(32)', 'NOT NULL', '', 'brands_name', '');
ChangeColumnName('manufacturers_url', 'brands', 'VARCHAR(50)', 'NULL', '', 'brands_url', '');
ChangeColumnName('manufacturers_image', 'brands', 'VARCHAR(64)', 'NULL', '', 'brands_image', '');

// TODO search for and rename any informal fk child columns (likely only "manufacturers_id")
// TODO don't forget to search for old column names in code (not only in the new Brands.php)


// 2. de-register script "Manufacturers.php" and register script "Brands.php"

// RemoveScript($ScriptName)
RemoveScript('Manufacturers.php');

// NewScript($ScriptName, $PageSecurity)
NewScript('Brands.php', 15);  // Security Token: User Management and System Admistration - TODO reasign as appropriate


// 3. update menu menu item [Inventory > Maintenance > Brands Management]
// RemoveMenuItem($Link, $Section, $Caption, $URL)
RemoveMenuItem('stock', 'Maintenance', __('Brands Maintenance'), '/Manufacturers.php');

// NewMenuItem($Link, $Section, $Caption, $URL, $Sequence)
NewMenuItem('stock', 'Maintenance', __('Brands Maintenance'), '/Brands.php', 5);


// 4. Wrap-up
UpdateDBNo(basename(__FILE__, '.php'), __('Rename Manufacturers to Brands Pt 2'));
