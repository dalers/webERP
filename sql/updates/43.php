<?php

// ENHANCE PLM FEATURES
//
// Related issues and discussion
// - Supporting "lite but rigorous" PLM https://github.com/timschofield/webERP/discussions/813
// - PLM Features https://github.com/timschofield/webERP/wiki/PLM-Features


// 1. ADD stockmaster.notes FOR AD HOC STOCK ITEM NOTES
//    - Notes column is intended for ad hoc notes about a stock item
//    - Parts&Vendors allows "approximately 60K" char
//    - resolves issue 592 "Schema is missing an Item "Notes" field" https://github.com/timschofield/webERP/issues/592
//    - !! Allows max 65KB but Z_ImportStocks.php only allows 3.5K
//
//    The "notes" column will accomodate the Parts&Vendors PN table PNNotes
//    column per "approximately 60K" on pg 149 of the P&V v6 User Manual, which
//    presumably means 60,000 ASCII characters - or just less than the 65K max
//    for a MariaDB "text" column (if 8-bit characters)
// 
//    Z_ImportStocks.php LIMITS CSV IMPORT ROW TO 4KB!!!
//    - should still allow 3.5K+ for Notes
//
//    Note while stockmaster.longdescription would be large enough to store the P&V
//    Notes column, perhaps even concatenated with (prefixed by) the Detail field
//    (255 char + 60K char < 65K char), use of longdescription is already
//    established (e.g. by @pakricard for e-commerce store product description
//    https://github.com/timschofield/webERP/discussions/812#discussioncomment-15543024)).

// AddColumn($Column, $Table, $Type, $Null, $Default, $After)
AddColumn('notes', 'stockmaster', 'text', 'NULL', '', 'actualcost');


// 2. ADD TABLE "stockfils" STOCK ITEM RELATED FILE/URL TABLE
//    - for file name with path OR URL
//    - equivalent to P&V FIL table
//    - Parts&Vendors allows ?? char TODO confirm max number of characters
//    - has foreign key constraint to stockmaster.stockid

// CreateTable($Table, $SQL)
// TODO remove back ticks from definition?
//CreateTable('stockfils', 'CREATE TABLE `stockfils` (
//  `filid` INTEGER NOT NULL AUTO_INCREMENT, 
//  `filstockid` VARCHAR(64),
//  `filfilepath` VARCHAR(255), 
//  `filefilename` VARCHAR(255), 
//  `filview` TINYINT(1) DEFAULT 0, 
//  `filnotes` VARCHAR(50), 
//  INDEX (`filid`, `filstockid`), 
//  INDEX (`filstockid`), 
//  PRIMARY KEY (`filid`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

// foreign key constraint
// AddConstraint($Table, $Constraint, $Field, $ReferenceTable, $ReferenceField)
//AddConstraint('stockfils', 'stockfils_ibfk_1', 'filstockid', 'stockmaster', 'stockid');

// ALTER TABLE `stockfils` ADD CONSTRAINT `stockfils_ibfk_1` FOREIGN KEY (`filstockid`) REFERENCES `stockmaster`(`stockid`) ON DELETE RESTRICT ON UPDATE RESTRICT;
// phpMyAdmin: Error creating foreign key on filstockid (check data types)


// 3. ADD "supplierlin" SUPPLIER LINE CARD
//    - manufacturers sold by a supplier
//    - equivalent to P&V LIN table
//    - TODO follow column naming and index convention in suppliers table for supplierlinsuid and supplierlinmfrid
    
// CreateTable($Table, $SQL)
//CreateTable('supplierlin', 'CREATE TABLE `supplierlin` (
//  `supplierlinid` INTEGER NOT NULL AUTO_INCREMENT, 
//  `supplierlinsuid` INTEGER NOT NULL DEFAULT 0,
//  `supplierlinmfrid` INTEGER NOT NULL DEFAULT 0,
//  INDEX (`supplierlinsuid`), 
//  INDEX (`supplierlinmfrid`), 
//  PRIMARY KEY (`supplierlinid`)
//)');


// 4. ADD "manufacturers" TABLE
//    - manufacturers for specifying the OEM for an item purchased from a supplier
//    - equivalent to P&V MFR table
//      - Parts&Vendors allows ?? char TODO confirm number of characters
//    - TODO follow naming and index convention in suppliers table
//
//    The "manufacturers" table currently exists for storing "brands" (used for sales
//    reporting IIUC). The current table must be renamed first in a previous db update
//    (table must be renamed first in a new preceeding db update and all code references
//    revised accordingly at the same time.

// CreateTable($Table, $SQL)
//CreateTable('manufacturers', 'CREATE TABLE `manufacturers` (
//  `manufacturersid` INTEGER NOT NULL AUTO_INCREMENT,
//  `manufacturersname` VARCHAR(50) NOT NULL, 
//  `manufacturersaddress` VARCHAR(255), 
//  `manufacturerscountry` VARCHAR(50), 
//  `manufacturerscontact1` VARCHAR(50), 
//  `manufacturerscontact2` VARCHAR(50), 
//  `manufacturersphone1` VARCHAR(20), 
//  `manufacturersphone2` VARCHAR(20), 
//  `manufacturersfax` VARCHAR(20), 
//  `manufacturersweb` VARCHAR(255), 
//  `manufacturersnotes` LONGTEXT, 
//  `manufacturerscode` VARCHAR(20), 
//  `manufacturersmail1` VARCHAR(50), 
//  `manufacturersmail2` VARCHAR(50), 
//  `manufacturersnophoneprefix` TINYINT(1) DEFAULT 0, 
//  INDEX (`manufacturersid`), 
//  UNIQUE (`manufacturersname`), 
//  PRIMARY KEY (`manufacturersid`)
//)');


// 5. ADD "manufacturerspn" MANUFACTURERS PART NUMBER TABLE
//    - OEM part numbers
//    - equivalent to P&V MFRPN table
//    - Parts&Vendors allows ?? char TODO confirm number of characters
//    - TODO follow naming and index convention in suppliers table
//    - TODO confirm if manufacturerspnid is a child foreign key for stockmaster.stockid (if so add constraint)

// CreateTable($Table, $SQL)
//CreateTable('manufacturerspn', 'CREATE TABLE `manufacturerspn` (
//  `manufacturerspnid` INTEGER NOT NULL AUTO_INCREMENT,
//  `manufacturerspnmfrid` INTEGER DEFAULT 0, 
//  `manufacturerspnpnpart` VARCHAR(50), 
//  INDEX (`manufacturerspnmfrid`), 
//  INDEX (`manufacturerspnpnpart`),
//  PRIMARY KEY (`manufacturerspnid`)
//)');


// TODO ADD BOM IMPORT (JUST A REMINDER - NOTHING TO DO WITH UPGRADE) 
// resolves discussion "How to Import BOMs"
// https://github.com/timschofield/webERP/discussions/591
// TODO Modify Z_ImportStocks.php? Create new script Z_ImportBOMs.php? 


if ($_SESSION['Updates']['Errors'] == 0) {
	UpdateDBNo(basename(__FILE__, '.php'), __('Enhance data for PLM'));
}
