<?php

// 1. INCREASE SIZE OF STOCK ID TO 65 CHAR (FROM 20)
// https://github.com/timschofield/webERP/discussions/812
//    - Parts&Vendors allows 50 char
//    - @pakricard OpenCart store allows 65 char 
// 
// 1.1 delete all fk constraints in child tables
//
// Use SQL query to obtain list of explicit constraints.
//
//     SELECT 
//         TABLE_NAME AS 'Referencing Table', 
//         COLUMN_NAME AS 'Foreign Key Column', 
//         CONSTRAINT_NAME AS 'Constraint Name'
//     FROM 
//         information_schema.KEY_COLUMN_USAGE
//     WHERE 
//         REFERENCED_TABLE_NAME = 'stockmaster' 
//         AND REFERENCED_COLUMN_NAME = 'stockid'
//         AND TABLE_SCHEMA = DATABASE(); -- Limits results to your current webERP database
//
//   +-----------------------------+--------------------+------------------------------------+
//   | Referencing Table           | Foreign Key Column | Constraint Name                    |
//   +-----------------------------+--------------------+------------------------------------+
//   | bom                         | parent             | bom_ibfk_1                         |
//   | bom                         | component          | bom_ibfk_2                         |
//   | contractbom                 | stockid            | contractbom_ibfk_3                 |
//   | custitem                    | stockid            | custitem_ibfk_1                    |
//   | locstock                    | stockid            | locstock_ibfk_2                    |
//   | loctransfers                | stockid            | loctransfers_ibfk_3                |
//   | mrpdemands                  | stockid            | mrpdemands_ibfk_2                  |
//   | offers                      | stockid            | offers_ibfk_2                      |
//   | orderdeliverydifferenceslog | stockid            | orderdeliverydifferenceslog_ibfk_1 |
//   | pickreqdetails              | stockid            | pickreqdetails_ibfk_1              |
//   | prices                      | stockid            | prices_ibfk_1                      |
//   | purchdata                   | stockid            | purchdata_ibfk_1                   |
//   | recurrsalesorderdetails     | stkcode            | recurrsalesorderdetails_ibfk_2     |
//   | salescatprod                | stockid            | salescatprod_ibfk_1                |
//   | salesorderdetails           | stkcode            | salesorderdetails_ibfk_2           |
//   | stockcheckfreeze            | stockid            | stockcheckfreeze_ibfk_1            |
//   | stockcounts                 | stockid            | stockcounts_ibfk_1                 |
//   | stockitemproperties         | stockid            | stockitemproperties_ibfk_1         |
//   | stockitemproperties         | stockid            | stockitemproperties_ibfk_3         |
//   | stockitemproperties         | stockid            | stockitemproperties_ibfk_5         |
//   | stockmoves                  | stockid            | stockmoves_ibfk_1                  |
//   | stockrequestitems           | stockid            | stockrequestitems_ibfk_2           |
//   | stockrequestitems           | stockid            | stockrequestitems_ibfk_4           |
//   | stockserialitems            | stockid            | stockserialitems_ibfk_1            |
//   | woitems                     | stockid            | woitems_ibfk_1                     |
//   | worequirements              | stockid            | worequirements_ibfk_2              |
//   +-----------------------------+--------------------+------------------------------------+
//   26 rows in set (0.259 sec)

//DropConstraint('bom', 'bom_ibfk_1');
//DropConstraint('bom','bom_ibfk_2');
//DropConstraint('contractbom', 'contractbom_ibfk_3');
//DropConstraint('custitem', 'custitem_ibfk_1');
//DropConstraint('locstock', 'locstock_ibfk_2');
//DropConstraint('loctransfers', 'loctransfers_ibfk_3');
//DropConstraint('mrpdemands', 'mrpdemands_ibfk_2');
//DropConstraint('offers', 'offers_ibfk_2');
//DropConstraint('orderdeliverydifferenceslog', 'orderdeliverydifferenceslog_ibfk_1');
//DropConstraint('pickreqdetails', 'pickreqdetails_ibfk_1');
//DropConstraint('prices', 'prices_ibfk_1');
//DropConstraint('purchdata', 'purchdata_ibfk_1');
//DropConstraint('recurrsalesorderdetails', 'recurrsalesorderdetails_ibfk_2');
//DropConstraint('salescatprod', 'salescatprod_ibfk_1');
//DropConstraint('salesorderdetails', 'salesorderdetails_ibfk_2');
//DropConstraint('stockcheckfreeze', 'stockcheckfreeze_ibfk_1');
//DropConstraint('stockcounts', 'stockcounts_ibfk_1');
//DropConstraint('stockitemproperties', 'stockitemproperties_ibfk_1');
//DropConstraint('stockitemproperties', 'stockitemproperties_ibfk_3');
//DropConstraint('stockitemproperties', 'stockitemproperties_ibfk_5');
//DropConstraint('stockmoves', 'stockmoves_ibfk_1');
//DropConstraint('stockrequestitems', 'stockrequestitems_ibfk_2');
//DropConstraint('stockrequestitems', 'stockrequestitems_ibfk_4');
//DropConstraint('stockserialitems', 'stockserialitems_ibfk_1');
//DropConstraint('woitems', 'woitems_ibfk_1');
//DropConstraint('worequirements', 'worequirements_ibfk_2');
// 26 constraints 

// 1.2 change size of stockid parent

ChangeColumnSize('stockid', 'stockmaster',  'VARCHAR(64)', ' NOT NULL ', '', '64');

// 1.3 change size of stockid children

//ChangeColumnSize('parent', 'bom', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('component', 'bom', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'contractbom', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'custitem', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'locstock, 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'loctransfers,  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'mrpdemands', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'offers', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'orderdeliverydifferenceslog', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'pickreqdetails', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'prices', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'purchdata', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stkcode', 'recurrsalesorderdetails', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'salescatprod', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stkcode', 'salesorderdetails', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'stockcheckfreeze', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'stockcounts', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'stockitemproperties', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'stockitemproperties', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'stockitemproperties', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'stockmoves', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'stockrequestitems', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'stockrequestitems', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'stockserialitems', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'woitems', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'worequirements', 'VARCHAR(64)', ' NOT NULL ', '', '64');
// 26 foreign key columns

// 1.4 restore foreign key constraints

// TODO something wrong here, stkcode is not listed but should appear twice

//AddConstraint('parent', 'bom_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('component', 'bom_ibfk_2', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'contractbom_ibfk_3', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'custitem_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'locstock_ibfk_2', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'loctransfers_ibfk_3', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'mrpdemands_ibfk_2', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'offers_ibfk_2', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'orderdeliverydifferenceslog_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'pickreqdetails_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'prices_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'purchdata_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stkcode', 'recurrsalesorderdetails_ibfk_2', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'salescatprod_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stkcode', 'salesorderdetails_ibfk_2', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'stockcheckfreeze_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'stockcounts_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'stockitemproperties_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'stockitemproperties_ibfk_3', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'stockitemproperties_ibfk_5', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'stockmoves_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'stockrequestitems_ibfk_2', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'stockrequestitems_ibfk_4', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'stockserialitems_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'woitems_ibfk_1', 'stockid', 'stockmaster', 'stockid');
//AddConstraint('stockid', 'worequirements_ibfk_2', 'stockid', 'stockmaster', 'stockid');
// 26 constraints

// 1.5 change size of implicit stockid foreign key columns

// 1.5.1 columns named stockid same as parent
// 
// Use SQL query to obtain list of constraints named "stockid" and then remove the explicit
// constraints found in step 1.
//
//   SELECT 
//       TABLE_NAME, 
//       COLUMN_NAME, 
//       DATA_TYPE
//   FROM 
//       information_schema.COLUMNS
//   WHERE 
//       COLUMN_NAME = 'stockid'
//       AND TABLE_SCHEMA = DATABASE()
//       AND TABLE_NAME != 'stockmaster';
//
//   +------------------------------+-------------+-----------+
//   | TABLE_NAME                   | COLUMN_NAME | DATA_TYPE |
//   +------------------------------+-------------+-----------+
//   | assetmanager                 | stockid     | varchar   |
//   | contractbom                  | stockid     | varchar   |
//   | custitem                     | stockid     | varchar   |
//   | ediitemmapping               | stockid     | varchar   |
//   | employees                    | stockid     | varchar   |
//   | lastcostrollup               | stockid     | char      |
//   | locstock                     | stockid     | varchar   |
//   | loctransfercancellations     | stockid     | varchar   |
//   | loctransfers                 | stockid     | varchar   |
//   | mrpdemands                   | stockid     | varchar   |
//   | offers                       | stockid     | varchar   |
//   | orderdeliverydifferenceslog  | stockid     | varchar   |
//   | pickreqdetails               | stockid     | varchar   |
//   | pickserialdetails            | stockid     | varchar   |
//   | pricematrix                  | stockid     | varchar   |
//   | prices                       | stockid     | varchar   |
//   | purchdata                    | stockid     | char      |
//   | relateditems                 | stockid     | varchar   |
//   | salesanalysis                | stockid     | varchar   |
//   | salescatprod                 | stockid     | varchar   |
//   | sellthroughsupport           | stockid     | varchar   |
//   | shipmentcharges              | stockid     | varchar   |
//   | stockcheckfreeze             | stockid     | varchar   |
//   | stockcounts                  | stockid     | varchar   |
//   | stockdescriptiontranslations | stockid     | varchar   |
//   | stockitemproperties          | stockid     | varchar   |
//   | stockmoves                   | stockid     | varchar   |
//   | stockrequestitems            | stockid     | varchar   |
//   | stockserialitems             | stockid     | varchar   |
//   | stockserialmoves             | stockid     | varchar   |
//   | supplierdiscounts            | stockid     | varchar   |
//   | tenderitems                  | stockid     | varchar   |
//   | woitems                      | stockid     | char      |
//   | worequirements               | stockid     | varchar   |
//   | woserialnos                  | stockid     | varchar   |
//   +------------------------------+-------------+-----------+
//   35 rows in set (0.015 sec)
//
//ChangeColumnSize('stockid', 'assetmanager',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'ediitemmapping',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'employees',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'lastcostrollup',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'loctransfercancellations',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'pickserialdetails',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'pricematrix',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'relateditems',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'salesanalysis',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'sellthroughsupport',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'shipmentcharges',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'stockdescriptiontranslations',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'stockserialmoves',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'supplierdiscounts',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'tenderitems',  'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stockid', 'woserialnos',  'VARCHAR(64)', ' NOT NULL ', '', '64');
// 16 foreign key columns

// Step 1.5.2 named stkcode (also used as an explicit fk)

// Use SQL query to obtain list of constraints named "stkcode" and remove explicit
// constraints with same name found in step 1.
//
// TODO something wrong here, why aren't the two columns named stkcode in Step 1 listed?
// 
//   SELECT 
//       TABLE_NAME, 
//       COLUMN_NAME, 
//       DATA_TYPE
//   FROM 
//       information_schema.COLUMNS
//   WHERE 
//       COLUMN_NAME = 'stkcode'
//       AND TABLE_SCHEMA = DATABASE()
//       AND TABLE_NAME != 'stockmaster';
//   	
//   +-------------------------+-------------+-----------+
//   | TABLE_NAME              | COLUMN_NAME | DATA_TYPE |
//   +-------------------------+-------------+-----------+
//   | recurrsalesorderdetails | stkcode     | varchar   |
//   | salesorderdetails       | stkcode     | varchar   |
//   +-------------------------+-------------+-----------+
//   2 rows in set (0.015 sec)
//
//ChangeColumnSize('stkcode', 'recurrsalesorderdetails', 'VARCHAR(64)', ' NOT NULL ', '', '64');
//ChangeColumnSize('stkcode', 'salesorderdetails', 'VARCHAR(64)', ' NOT NULL ', '', '64');
// 2 foreign key columns


// 2. INCREASE SIZE OF DESCRIPTION TO 255 CHAR (FROM 50)
// https://github.com/timschofield/webERP/discussions/812
// - Parts&Vendors allows 255 char
// - @pakricard OpenCart store allows 255 char 
//ChangeColumnSize('description', 'stockmaster', 'VARCHAR(255)', ' NOT NULL ', '', '255');


// 3. ADD STOCK ITEM NOTE
// https://github.com/timschofield/webERP/issues/592#issuecomment-3770372715
// https://github.com/timschofield/webERP/discussions/812#discussioncomment-15543024
// - Notes column is intended for ad hoc notes about a stock item
// - resolves issue 592 "Schema is missing an Item "Notes" field" https://github.com/timschofield/webERP/issues/592
// - Parts&Vendors allows "approximately 60K" char
//
// The "note" column will be imported from the Parts&Vendors PN table PNNotes
// field which is "approximately 60K" (from page 149 of the P&V v6 User Manual),
// presumably meaning 60,000 ASCII characters or just less than the 65K max
// for the MariaDB "text" column type. However, Z_ImportStocks.php limits the
// total char per row in a CSV to only 4KB (TODO CONFIRM)
//
// Equivalent SQL: ALTER TABLE `stockmaster` ADD `note` TEXT AFTER `actualcost`;
// 
// Also note that "longdescription" in the webERP stockmaster table has a max
// 65K char so it could be used to store the P&V Notes (perhaps pre-pending
// with the P&V part Description (255 char + 60K char < 65K)
// but longdescription use has been established (e.g. used by @pakricard for
// an e-store product description) while Notes in P&V is purely ad hoc.
// 
// Related discussions:
//AddColumn('notes', stockmaster, text, 'NULL', '', longdescription);


// 4. ADD STOCK DOCUMENT REFERENCES
// - for files and/or URLs (equivalent to P&V FIL table)
//  - Parts&Vendors allows ?? char TODO confirm number of characters
//    
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


// 5. ADD VENDOR LINE CARD REFERENCE
// - manufacturers sold by a particular supplier (equivalent to P&V LIN table)
//    
//DROP TABLE IF EXISTS `supplinecard`;
//CREATE TABLE `supplinecard` (
//  `LINID` INTEGER NOT NULL AUTO_INCREMENT, 
//  `LINSUID` INTEGER NOT NULL DEFAULT 0, 
//  `LINMFRID` INTEGER NOT NULL DEFAULT 0, 
//  INDEX (`LINMFRID`), 
//  INDEX (`LINSUID`), 
//  PRIMARY KEY (`LINID`)
//) ENGINE=innodb;


// 6. ADD MFR REFERENCE
// - stockmfr table for manufacturer information (equivalent to P&V MFR table)
//  - Parts&Vendors allows ?? char TODO confirm number of characters
//
// NOTE "manufacturers" table already exists for sales reporting (iiuc)
//
////DROP TABLE IF EXISTS `stockmfr`;
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


// 7. ADD MFR PN REFERENCE
// stockmfrpn table for mfr part number (equivalent to P&V MFRPN table)
//  - Parts&Vendors allows ?? char TODO confirm number of characters
//
//DROP TABLE IF EXISTS `stockmfrpn`;
//CREATE TABLE `stockmfrpn` (
//  `MFRPNID` INTEGER NOT NULL AUTO_INCREMENT, 
//  `MFRPNMFRID` INTEGER DEFAULT 0, 
//  `MFRPNPart` VARCHAR(50), 
//  INDEX (`MFRPNMFRID`), 
//  INDEX (`MFRPNPart`), 
//  PRIMARY KEY (`MFRPNID`)
//) ENGINE=innodb;


// JUST A REMINDER - NOTHING TO DO WITH UPGRADE
// support importing BOMs 
// resolves discussion "How to Import BOMs"
// https://github.com/timschofield/webERP/discussions/591
// TODO Modify Z_ImportStocks.php? Create new script Z_ImportBOMs.php? 


if ($_SESSION['Updates']['Errors'] == 0) {
	UpdateDBNo(basename(__FILE__, '.php'), __('Add PLM schema (WIP do nothing)'));
}
