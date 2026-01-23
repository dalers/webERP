<?php

// drop all fk constraints

if (ConstraintExists('bom', 'bom_ibfk_1'))
	DropConstraint('bom', 'bom_ibfk_1');

var_dump();
die();

if (ConstraintExists('bom', 'bom_ibfk_2'))
	DropConstraint('bom','bom_ibfk_2');
if (ConstraintExists('contractbom', 'contractbom_ibfk_3'))
	DropConstraint('contractbom', 'contractbom_ibfk_3');
if (ConstraintExists('custitem', '` custitem _ibfk_1`'))
	DropConstraint('custitem', '` custitem _ibfk_1`');
if (ConstraintExists('locstock', 'locstock_ibfk_2'))
	DropConstraint('locstock', 'locstock_ibfk_2');
if (ConstraintExists('loctransfers', 'loctransfers_ibfk_3'))
	DropConstraint('loctransfers', 'loctransfers_ibfk_3');
if (ConstraintExists('mrpdemands', 'mrpdemands_ibfk_2'))
	DropConstraint('mrpdemands', 'mrpdemands_ibfk_2');
if (ConstraintExists('offers', 'offers_ibfk_2'))
	DropConstraint('offers', 'offers_ibfk_2');
if (ConstraintExists('orderdeliverydifferenceslog', 'orderdeliverydifferenceslog_ibfk_1'))
	DropConstraint('orderdeliverydifferenceslog', 'orderdeliverydifferenceslog_ibfk_1');
if (ConstraintExists('pickreqdetails', 'pickreqdetails_ibfk_1'))
	DropConstraint('pickreqdetails', 'pickreqdetails_ibfk_1');
if (ConstraintExists('prices', 'prices_ibfk_1'))
	DropConstraint('prices', 'prices_ibfk_1');
if (ConstraintExists('purchdata', 'purchdata_ibfk_1'))
	DropConstraint('purchdata', 'purchdata_ibfk_1');
if (ConstraintExists('recurrsalesorderdetails', 'recurrsalesorderdetails_ibfk_2'))
	DropConstraint('recurrsalesorderdetails', 'recurrsalesorderdetails_ibfk_2');
if (ConstraintExists('salescatprod', 'salescatprod_ibfk_1'))
	DropConstraint('salescatprod', 'salescatprod_ibfk_1');
if (ConstraintExists('salesorderdetails', 'salesorderdetails_ibfk_2'))
	DropConstraint('salesorderdetails', 'salesorderdetails_ibfk_2');
if (ConstraintExists('stockcheckfreeze', 'stockcheckfreeze_ibfk_1'))
	DropConstraint('stockcheckfreeze', 'stockcheckfreeze_ibfk_1');
if (ConstraintExists('stockcounts', 'stockcounts_ibfk_1'))
	DropConstraint('stockcounts', 'stockcounts_ibfk_1');
if (ConstraintExists('stockitemproperties', 'stockitemproperties_ibfk_1'))
	DropConstraint('stockitemproperties', 'stockitemproperties_ibfk_1');
if (ConstraintExists('stockitemproperties', 'stockitemproperties_ibfk_3'))
	DropConstraint('stockitemproperties', 'stockitemproperties_ibfk_3');
if (ConstraintExists('stockitemproperties', 'stockitemproperties_ibfk_5'))
	DropConstraint('stockitemproperties', 'stockitemproperties_ibfk_5');
if (ConstraintExists('stockmoves', 'stockmoves_ibfk_1'))
	DropConstraint('stockmoves', 'stockmoves_ibfk_1');
if (ConstraintExists('stockrequestitems', 'stockrequestitems_ibfk_2'))
	DropConstraint('stockrequestitems', 'stockrequestitems_ibfk_2');
if (ConstraintExists('stockrequestitems', 'stockrequestitems_ibfk_4'))
	DropConstraint('stockrequestitems', 'stockrequestitems_ibfk_4');
if (ConstraintExists('stockserialitems', 'stockserialitems_ibfk_1'))
	DropConstraint('stockserialitems', 'stockserialitems_ibfk_1');
if (ConstraintExists('woitems', 'woitems_ibfk_1'))
	DropConstraint('woitems', 'woitems_ibfk_1');
if (ConstraintExists('worequirements', 'worequirements_ibfk_2'))
	DropConstraint('worequirements', 'worequirements_ibfk_2');

// change size of explicit fk relationships to stockid


// change size of implicit fk relationships to stockid


// add back fk constraints (without spaces in custitem fk names)
//    TODO fix installer table file custitem.sql


// increase stockmaster:stockid ("StockID") to 64 char
// drop foreign key constraint from custitem:stockid to stockmaster:stockid 
//DropConstraint('custitem', 'custitem_ibfk_1');


// change size of stockid
//ChangeColumnSize('stockid', 'stockmaster', 'VARCHAR(64)', ' NOT NULL ', '', '64');
// also change size of custitem:stockid (to same)
//ChangeColumnSize('stockid', 'custitem', 'VARCHAR(64)', ' NOT NULL ', '', '64');
// add foreign key constraint again
//AddConstraint('custitem', 'custitem_ibfk_1', 'stockid', 'stockmaster', 'stockid');

// increase stockmaster:description ("Description") to 255 char
//ChangeColumnSize('description', 'stockmaster', 'VARCHAR(255)', ' NOT NULL ', '', '255');

// add note column to stockmaster table
// - resolves issue 592 "Schema is missing an Item "Notes" field" https://github.com/timschofield/webERP/issues/592
//
// the "note" column will be imported from the Parts&Vendors PN table PNNotes column,
// which is only 64KB vs 4GB/char (less if multi-byte characters),
// but the total char per row when importing is limited to 4KB in Z_ImportStocks.php.
//
// Equivalent SQL: ALTER TABLE `stockmaster` ADD `note` TEXT AFTER `actualcost`;
// 
// Note that since "longdescription" is max 65K char it _could_ be used to store the
// P&V Description _AND_ Notes fields (concatenated, 255 + 4K(?) = 5K << 65K)
// but "LongDescription" has established use (at least for a e-store product
// long desription by @pakricard) while in P&V Notes is a truly ad hoc comment.
// 
// Related discussions:
//   https://github.com/timschofield/webERP/issues/592#issuecomment-3770372715
//   https://github.com/timschofield/webERP/discussions/812#discussioncomment-15543024
//AddColumn('notes', stockmaster, text, 'NULL', '', longdescription);


// add stockrefs table for files and/or URLs
// - equivalent to P&V FIL table
//DROP TABLE IF EXISTS `stockrefs`;
//CREATE TABLE `stockrefs` (
//  `FILID` INTEGER NOT NULL AUTO_INCREMENT, 
//  `FILPNID` INTEGER DEFAULT 0, 
//  `FILPNPartNumber` VARCHAR(50), 
//  `FILFilePath` VARCHAR(255), 
//  `FILFileName` VARCHAR(255), 
//  `FILView` TINYINT(1) DEFAULT 0, 
//  `FILNotes` VARCHAR(50), 
//  INDEX (`FILID`, `FILPNID`), 
//  INDEX (`FILPNID`), 
//  INDEX (`FILPNPartNumber`), 
//  PRIMARY KEY (`FILID`)
//) ENGINE=innodb;


// add table for vendor "line card" (manufacturers sold by a particular supplier) 
// - equivalent to P&V LIN table
//DROP TABLE IF EXISTS `supplinecard`;
//CREATE TABLE `supplinecard` (
//  `LINID` INTEGER NOT NULL AUTO_INCREMENT, 
//  `LINSUID` INTEGER NOT NULL DEFAULT 0, 
//  `LINMFRID` INTEGER NOT NULL DEFAULT 0, 
//  INDEX (`LINMFRID`), 
//  INDEX (`LINSUID`), 
//  PRIMARY KEY (`LINID`)
//) ENGINE=innodb;


// add stockmfr table for manufacturer information
// - equivalent to P&V MFR table
// NOTE "manufacturers" table already exists for sales reporting (iiuc)
//DROP TABLE IF EXISTS `stockmfr`;
//CREATE TABLE `stockmfr` (
//  `MFRID` INTEGER NOT NULL AUTO_INCREMENT, 
//  `MFRMfrName` VARCHAR(50) NOT NULL, 
//  `MFRAddress` VARCHAR(255), 
//  `MFRCountry` VARCHAR(50), 
//  `MFRContact1` VARCHAR(50), 
//  `MFRContact2` VARCHAR(50), 
//  `MFRPhone1` VARCHAR(20), 
//  `MFRPhone2` VARCHAR(20), 
//  `MFRFax` VARCHAR(20), 
//  `MFRWeb` VARCHAR(255), 
//  `MFRNotes` LONGTEXT, 
//  `MFRCode` VARCHAR(20), 
//  `MFREMail1` VARCHAR(50), 
//  `MFREMail2` VARCHAR(50), 
//  `MFRNoPhonePrefix` TINYINT(1) DEFAULT 0, 
//  INDEX (`MFRCode`), 
//  UNIQUE (`MFRMfrName`), 
//  PRIMARY KEY (`MFRID`)
//) ENGINE=innodb;


// add stockmfrpn table for manufacturer part number information
// - equivalent to P&V MFRPN table
//DROP TABLE IF EXISTS `stockmfrpn`;
//CREATE TABLE `stockmfrpn` (
//  `MFRPNID` INTEGER NOT NULL AUTO_INCREMENT, 
//  `MFRPNMFRID` INTEGER DEFAULT 0, 
//  `MFRPNPart` VARCHAR(50), 
//  INDEX (`MFRPNMFRID`), 
//  INDEX (`MFRPNPart`), 
//  PRIMARY KEY (`MFRPNID`)
//) ENGINE=innodb;


// support importing BOMs 
// resolves discussion "How to Import BOMs"
// https://github.com/timschofield/webERP/discussions/591
// TODO Modify Z_ImportStocks.php? Create new script Z_ImportBOMs.php? 


if ($_SESSION['Updates']['Errors'] == 0) {
	UpdateDBNo(basename(__FILE__, '.php'), __('Do nothing'));
}
