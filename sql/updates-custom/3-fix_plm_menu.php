<?php

// Add PLM Module to Menu System

// Register new scripts
//NewScript('Manufacturers.php', 15));
//NewScript('SelectManufacturer.php', 15));
//NewScript('Z_ImportManufacturers.php', 15));
//NewScript('Z_ImportStockFilsUrls.php', 15));

// Register PLM Module
//NewModule('plm', 'PLM', __('Product Lifecycle Mgmt'), 8);

// Build PLM menu
// Transactions pane (left)
// The PLM does not perform Transations (although "Inventory Item Movements" is available from "Select an Item"
RemoveMenuItem('plm', 'Maintenance', __('Inventory Adjustments'), '/StockAdjustments.php?NewAdjustment=Yes', 1);
