<?php

ChangeColumnType('reference', 'debtortrans', 'varchar(50)', ' NOT NULL ', '');

UpdateDBNo(basename(__FILE__, '.php'), _('Change size of reference field to 50 characters for consistency'));
