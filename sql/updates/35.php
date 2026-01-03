<?php

// add "stocknote" column to stockmaster TABLE
// resolves issue "Schema is missing an Item "Notes" field"
// https://github.com/timschofield/webERP/issues/592
// equivalent to P&V PN:PNNotes except 64KG instead of 4GB bytes (fewer chars if multi-byte)
// note however that the total characters per row when importing CSV is 4K Bytes
// SQL: ALTER TABLE `stockmaster` ADD `stocknote` TEXT AFTER `actualcost`;


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


// cleanup - set DBUpdateNumber
if ($_SESSION['Updates']['Errors'] == 0) {
	UpdateDBNo(basename(__FILE__, '.php'), __('Add PLM features'));
}
