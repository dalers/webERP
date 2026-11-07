-- Add PLM Supply Chain Schema
--
-- Add manufacturers, manufacturerspn, stockfils and supplierlin tables for PLM supply chain management
-- (the stockfils table has a constraint on stockid in stockmaster but does not affect deleting)
-- Inspired by "Parts&Vendors"
-- Related webERP project discussions
-- - "Lite" PLM https://github.com/timschofield/webERP/discussions/813
-- - PLM Features https://github.com/timschofield/webERP/wiki/PLM-Features


-- 1. add stockfils table for documents and URLs related to a stock item
--    - stores filename with path or URL
--    - has foreign key constraint to stockmaster.stockid
--    - equivalent to P&V FIL table
--    - expect will be used only temporarily for importing documents into DCS
DROP TABLE IF EXISTS `stockfils`;
CREATE TABLE `stockfils` (
  `filid` int(11) NOT NULL AUTO_INCREMENT,
  `filstockid` varchar(64) DEFAULT NULL,
  `filfilepath` varchar(255) DEFAULT NULL,
  `filefilename` varchar(255) DEFAULT NULL,
  `filview` tinyint(1) DEFAULT 0,
  `filnotes` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`filid`),
  KEY `filid` (`filid`,`filstockid`),
  KEY `filstockid` (`filstockid`),
  CONSTRAINT `stockfils_ibfk_1` FOREIGN KEY (`filstockid`) REFERENCES `stockmaster` (`stockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- 2. add supplierlin table (OEMs distributed by a particular supplier aka their "line card"`)
--    - equivalent to P&V LIN table
--    - TODO supplierlinsuid and supplierlinmfrid conform to column naming and index convention in suppliers table
--    - foreign key constraints to be determined 
--      - is supplierlinsuid a fk for suppliers.supplierid?
--      - is supplierlinmfrid a fk for manufacturers.manufacturers_id?
DROP TABLE IF EXISTS `supplierlin`;
CREATE TABLE `supplierlin` (
  `supplierlinid` int(11) NOT NULL AUTO_INCREMENT,
  `supplierlinsuid` int(11) NOT NULL DEFAULT 0,
  `supplierlinmfrid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`supplierlinid`),
  KEY `supplierlinsuid` (`supplierlinsuid`),
  KEY `supplierlinmfrid` (`supplierlinmfrid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- 3. add manufacturers table (manufacturer identification)
--    - manufacturers for specifying the OEM for an item purchased from a supplier
--    - equivalent to P&V MFR table (TODO confirm max number of characters)
--    - TODO conform with column naming and index convention in suppliers table
--    - foreign key constraints
--      - table is assumed to be master data and as such has no fk constraints
DROP TABLE IF EXISTS `manufacturers`;
CREATE TABLE `manufacturers` (
  `manufacturersid` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturersname` varchar(50) NOT NULL,
  `manufacturersaddress` varchar(255) DEFAULT NULL,
  `manufacturerscountry` varchar(50) DEFAULT NULL,
  `manufacturerscontact1` varchar(50) DEFAULT NULL,
  `manufacturerscontact2` varchar(50) DEFAULT NULL,
  `manufacturersphone1` varchar(20) DEFAULT NULL,
  `manufacturersphone2` varchar(20) DEFAULT NULL,
  `manufacturersfax` varchar(20) DEFAULT NULL,
  `manufacturersweb` varchar(255) DEFAULT NULL,
  `manufacturersnotes` longtext DEFAULT NULL,
  `manufacturerscode` varchar(20) DEFAULT NULL,
  `manufacturersmail1` varchar(50) DEFAULT NULL,
  `manufacturersmail2` varchar(50) DEFAULT NULL,
  `manufacturersnophoneprefix` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`manufacturersid`),
  UNIQUE KEY `manufacturersname` (`manufacturersname`),
  KEY `manufacturersid` (`manufacturersid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- 4. add manufacturerspn table (manufacturers part numbers)
--    - OEM part numbers
--    - equivalent to P&V MFRPN table (TODO confirm max number of characters)
--    - TODO follow naming and index convention in suppliers table
--    - TODO confirm if manufacturerspnid is a child foreign key for stockmaster.stockid (if so add constraint)
--    - TODO change manufacturerspnid TO VARCHAR(64) (for consistency with stockid or required if FK TO stockmaster.stockid?)
--    - foreign key constraints to be determined
--      - is manufacturerspnid a fk to ??? TODO change column type to same as webERP parent column
--       - is manufacturerspnmfrid a fk to stockmaster.stockid? TODO change column type to VARCHAR(64)
DROP TABLE IF EXISTS `manufacturerspn`;
CREATE TABLE `manufacturerspn` (
  `manufacturerspnid` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturerspnmfrid` int(11) DEFAULT 0,
  `manufacturerspnpnpart` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`manufacturerspnid`),
  KEY `manufacturerspnmfrid` (`manufacturerspnmfrid`),
  KEY `manufacturerspnpnpart` (`manufacturerspnpnpart`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
