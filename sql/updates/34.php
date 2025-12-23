<?php

NewConfigValue('AutoWoNoBom', 0);

if ($_SESSION['Updates']['Errors'] == 0) {
	UpdateDBNo(basename(__FILE__, '.php'), __('Add config variable to allow WO for stock item without a BOM'));
}
