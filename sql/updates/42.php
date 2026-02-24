<?php

// Rename "manufacturers" table to "brands"
// - no constraints exist

// RenameTable($OldName, $NewName)
RenameTable('manufacturers', 'brands');

UpdateDBNo(basename(__FILE__, '.php'), __('Rename manufacturers table to brands'));
