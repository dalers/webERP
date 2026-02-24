<?php

// Rename "manufacturers" table to "brands"
// - no constraints exist
// - resolves https://github.com/timschofield/webERP/issues/678
// - also see https://github.com/timschofield/webERP/wiki/PLM-Features-Rename-manufacturers-table

// RenameTable($OldName, $NewName)
RenameTable('manufacturers', 'brands');

// rename columns
// ChangeColumnName($OldName, $Table, $Type, $Null, $Default, $NewName, $AutoIncrement = '')
ChangeColumnName('manufacturers_id', 'brands', 'INT(11)', 'NO', '', 'brands_id', 'AUTO_INCREMENT');
ChangeColumnName('manufacturers_name', 'brands', 'VARCHAR(32)', 'NO', '', 'brands_name', '');
ChangeColumnName('manufacturers_url', 'brands', 'VARCHAR(50)', 'NO', '', 'brands_url', '');
ChangeColumnName('manufacturers_image', 'brands', 'VARCHAR(64)', 'NO', '', 'brands_image', '');

UpdateDBNo(basename(__FILE__, '.php'), __('Rename manufacturers table to brands'));
