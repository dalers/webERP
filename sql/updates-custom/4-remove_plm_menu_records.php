<?php

// Remove the obsolete PLM module and its menu items from all security roles.
// Z_UpgradeDatabase.php?update=4-remove_plm_menu_records.php

DeleteRecords('menuitems', "modulelink = 'plm'");
DeleteRecords('modules', "modulelink = 'plm'");
