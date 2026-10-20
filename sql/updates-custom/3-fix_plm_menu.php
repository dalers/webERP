<?php

// Remove menu item [ PLM > Maintenance > Inventory Adjustments ]
// (access by .../weberp/Z_UpgradeDatabase.php?update=3-fix_plm_menu.php)
//
// The PLM module menu provides for stock adjustments of a selected item (menu
//  item [ PLM > Select an Item > { select an item } > Item Transactions >
//  Quantity Adjustments ]) but ad hoc adjustments are done in the Inventory module
//  [ Inventory > Transactions > Inventory Adjustments ]
RemoveMenuItem('plm', 'Maintenance', __('Inventory Adjustments'), '/StockAdjustments.php?NewAdjustment=Yes', 1);
