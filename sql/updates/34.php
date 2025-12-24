<?php

// switch to allow creating a WO for a manufactured stock item without a BOM
// resolves issue "Work orders cannot be made for parts without BOMs"
// https://github.com/timschofield/webERP/issues/793

NewConfigValue('AllowWoNoBom', 0);

if ($_SESSION['Updates']['Errors'] == 0) {
	UpdateDBNo(basename(__FILE__, '.php'), __('Add config variable to allow WO for stock item without a BOM'));
}
