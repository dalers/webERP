<?php

$GPLV2 = file_get_contents(__DIR__ . '/../../doc/LICENSE.txt');

if ($_SESSION['Installer']['License_Agreed'] == True) {
	$Checked = ' checked=true ';
} else {
	$Checked = '';
}

echo '<form id="license_form" action="index.php?Next=Yes">
		<label>', _('webERP is released under the GNU GPL v2 license'), ' :</label>
		<textarea id="license" rows="20" readonly="true" value="" >', $GPLV2, '</textarea><br  />
		<span id="license_agree">
			<input onclick="toggle_button(this)" id="agreed" type="checkbox"" ', $Checked, ' />', _('I have read the license and agree to the terms and conditions within it'), '
		</span>';
