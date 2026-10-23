<?php

// Remove menu item [ PLM > Maintenance > Inventory Adjustments ]
// (access by .../weberp/Z_UpgradeDatabase.php?update=3-fix_plm_menu.php)
//
// The PLM module menu provides for stock adjustments of a selected item (using
//   menu item [ PLM > Select an Item > { select an item } > Item Transactions >
//     Quantity Adjustments ]) but ad hoc adjustments are done in the Inventory module
//       [ Inventory > Transactions > Inventory Adjustments ]
//RemoveMenuItem('plm', 'Maintenance', __('Inventory Adjustments'), '/StockAdjustments.php?NewAdjustment=Yes');

// The PLM module menu is not concerned with updating translations (which is a development/admin process)
RemoveMenuItem('plm', 'Maintenance', __('Review Translated Descriptions'), '/RevisionTranslations.php');
