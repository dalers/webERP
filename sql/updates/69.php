<?php

// Related Discussion
// - "Lite" PLM https://github.com/timschofield/webERP/discussions/813
// - PLM Features https://github.com/timschofield/webERP/wiki/PLM-Features

// 1. add stockfils table (documents and URLs related to a stock item)
//    - stores filename with path or URL
//    - has foreign key constraint to stockmaster.stockid
//    - equivalent to P&V FIL table

// CreateTable($Table, $SQL)
CreateTable('stockfils', "CREATE TABLE IF NOT EXISTS `stockfils` (
  `filid` INTEGER NOT NULL AUTO_INCREMENT, 
  `filstockid` VARCHAR(64),
  `filfilepath` VARCHAR(255), 
  `filefilename` VARCHAR(255), 
  `filview` TINYINT(1) DEFAULT 0,
  `filnotes` VARCHAR(50), 
  INDEX (`filid`, `filstockid`), 
  INDEX (`filstockid`), 
  PRIMARY KEY (`filid`)
)");

// foreign key constraints
// AddConstraint($Table, $Constraint, $Field, $ReferenceTable, $ReferenceField)
AddConstraint('stockfils', 'stockfils_ibfk_1', 'filstockid', 'stockmaster', 'stockid');


// 2. add supplierlin table (OEMs distributed by a particular supplier aka their "line card"`)
//    - equivalent to P&V LIN table
//    - TODO supplierlinsuid and supplierlinmfrid conform to column naming and index convention in suppliers table
    
// CreateTable($Table, $SQL)
CreateTable('supplierlin', "CREATE TABLE IF NOT EXISTS `supplierlin` (
  `supplierlinid` INTEGER NOT NULL AUTO_INCREMENT, 
  `supplierlinsuid` INTEGER NOT NULL DEFAULT 0,
  `supplierlinmfrid` INTEGER NOT NULL DEFAULT 0,
  INDEX (`supplierlinsuid`), 
  INDEX (`supplierlinmfrid`), 
  PRIMARY KEY (`supplierlinid`)
)");

// foreign key constraints
// TODO determine required foreign key constraints for supplierlin table
//   - is supplierlinsuid a fk for suppliers.supplierid?
//   - is supplierlinmfrid a fk for manufacturers.manufacturers_id?


// 3. add manufacturers table (manufacturer identification)
//    - manufacturers for specifying the OEM for an item purchased from a supplier
//    - equivalent to P&V MFR table (TODO confirm max number of characters)
//    - TODO conform with column naming and index convention in suppliers table

// CreateTable($Table, $SQL)
CreateTable('manufacturers', "CREATE TABLE IF NOT EXISTS `manufacturers` (
  `manufacturersid` INTEGER NOT NULL AUTO_INCREMENT,
  `manufacturersname` VARCHAR(50) NOT NULL, 
  `manufacturersaddress` VARCHAR(255), 
  `manufacturerscountry` VARCHAR(50), 
  `manufacturerscontact1` VARCHAR(50), 
  `manufacturerscontact2` VARCHAR(50), 
  `manufacturersphone1` VARCHAR(20), 
  `manufacturersphone2` VARCHAR(20), 
  `manufacturersfax` VARCHAR(20), 
  `manufacturersweb` VARCHAR(255), 
  `manufacturersnotes` LONGTEXT, 
  `manufacturerscode` VARCHAR(20), 
  `manufacturersmail1` VARCHAR(50), 
  `manufacturersmail2` VARCHAR(50), 
  `manufacturersnophoneprefix` TINYINT(1) DEFAULT 0, 
  INDEX (`manufacturersid`), 
  UNIQUE (`manufacturersname`), 
  PRIMARY KEY (`manufacturersid`)
)");

// foreign key constraints
// - table assumed to be master data with no fk constraints


// 4. add manufacturerspn table (manufacturers part numbers)
//    - OEM part numbers
//    - equivalent to P&V MFRPN table (TODO confirm max number of characters)
//    - TODO follow naming and index convention in suppliers table
//    - TODO confirm if manufacturerspnid is a child foreign key for stockmaster.stockid (if so add constraint)

// CreateTable($Table, $SQL)
CreateTable('manufacturerspn', "CREATE TABLE IF NOT EXISTS `manufacturerspn` (
  `manufacturerspnid` INTEGER NOT NULL AUTO_INCREMENT,
  `manufacturerspnmfrid` INTEGER DEFAULT 0, 
  `manufacturerspnpnpart` VARCHAR(50), 
  INDEX (`manufacturerspnmfrid`),
  INDEX (`manufacturerspnpnpart`),
  PRIMARY KEY (`manufacturerspnid`)
)");

// TODO CHANGE manufacturerspnid TO VARCHAR(64) IF FK TO stockmaster.stockid

// foreign key constraints
// TODO add foreign key constraints for manufacturerspn table
//  - is manufacturerspnid a fk to ??? TODO change column type to same as webERP parent column
//  - is manufacturerspnmfrid a fk to stockmaster.stockid? TODO change column type to VARCHAR(64)


// 5. cleanup
if ($_SESSION['Updates']['Errors'] == 0) {
	UpdateDBNo(basename(__FILE__, '.php'), __('Add stockitem doc/url and supply chain OEM'));
}
