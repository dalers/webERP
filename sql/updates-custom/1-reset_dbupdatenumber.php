<?php

// Reset DBUpdateNumber to db number when the demo sql files were created trigger running updates
// Z_UpgradeDatabase.php?update=1-reset_dbupdatenumber.php

// ChangeConfigValue($ConfName, $NewConfigValue)
ChangeConfigValue("DBUpdateNumber", "68");
