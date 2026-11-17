<?php

// Reset DBUpdateNumber to update 68 (time of demo sql files) to trigger updating
// Z_UpgradeDatabase.php?update=1-reset_dbupdatenumber.php

// ChangeConfigValue($ConfName, $NewConfigValue)
ChangeConfigValue("DBUpdateNumber", "68");
