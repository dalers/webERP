<?php

// Add PLM Module to Menu System

// Register new scripts
//NewScript('Manufacturers.php', 15));
//NewScript('SelectManufacturer.php', 15));
//NewScript('Z_ImportManufacturers.php', 15));
//NewScript('Z_ImportStockFilsUrls.php', 15));

// Register PLM Module
NewModule('plm', 'PLM', __('Product Lifecycle Mgmt'), 8);

// Build PLM menu
// Transactions pane (left)
NewMenuItem('plm', 'Maintenance', __('Inventory Adjustments'), '/StockAdjustments.php?NewAdjustment=Yes', 1);

// Reports pane (middle)
NewMenuItem('plm', 'Reports', __('Serial Item Research Tool'), '/StockSerialItemResearch.php', 1);
NewMenuItem('plm', 'Reports', __('Inventory Item Movements'), '/StockMovements.php', 2);
NewMenuItem('plm', 'Reports', __('Inventory Item Status'), '/StockStatus.php', 3);
NewMenuItem('plm', 'Reports', __('Inventory Item Usage'), '/StockUsage.php', 4);
NewMenuItem('plm', 'Reports', __('Inventory Quantities'), '/InventoryQuantities.php', 5);

NewMenuItem('plm', 'Reports', __('Costed BOM Inquiry'), '/BOMInquiry.php', 6);
NewMenuItem('plm', 'Reports', __('Where Used Inquiry'), '/WhereUsedInquiry.php', 7);
NewMenuItem('plm', 'Reports', __('Where Used Inquiry (Indented)'), '/BOMIndentedReverse.php', 8); // TODO hide if selected item cannot have BOM?
NewMenuItem('plm', 'Reports', __('BOM Listing'), '/BOMListing.php', 9); // TODO hide if selected item cannot have BOM?
NewMenuItem('plm', 'Reports', __('BOM Listing (Indented)'), '/BOMIndented.php', 10); // TODO hide if selected item cannot have BOM?
NewMenuItem('plm', 'Reports', __('List Components Required'), '/BOMExtendedQty.php', 11);
NewMenuItem('plm', 'Reports', __('List Components Not Used'), '/MaterialsNotUsed.php', 12);

NewMenuItem('plm', 'Reports', __('Print Product Specification'), '/PDFProdSpec.php', 13);
NewMenuItem('plm', 'Reports', __('Print Certificate of Analysis'), '/PDFCOA.php', 14);
NewMenuItem('plm', 'Reports', __('Historical QA Test Results'), '/HistoricalTestResults.php', 15);

// Maintenance pane (right)
NewMenuItem('plm', 'Maintenance', __('Add a New Item'), '/Stocks.php', 1);
NewMenuItem('plm', 'Maintenance', __('Select an Item'), '/SelectProduct.php', 2);

NewMenuItem('plm', 'Maintenance', __('Add a Supplier'), '/Suppliers.php', 3);
NewMenuItem('plm', 'Maintenance', __('Select a Supplier'), '/SelectSupplier.php', 4);

NewMenuItem('plm', 'Maintenance', __('Add a Manufacturer'), '/Manufacturers.php', 5); // TODO create script
NewMenuItem('plm', 'Maintenance', __('Select a Manufacturer'), '/SelectManufacturer.php', 6); // TODO create script

NewMenuItem('plm', 'Maintenance', __('Manage BOM'), '/BOMs.php', 7); // TODO hide if selected item cannot have BOM?
NewMenuItem('plm', 'Maintenance', __('Copy BOM'), '/CopyBOM.php', 8); // TODO hide if selected item cannot have BOM?

NewMenuItem('plm', 'Maintenance', __('Work Centre'), '/WorkCentres.php', 9);
NewMenuItem('plm', 'Maintenance', __('Skills Catalog'), '/HRSkills.php', 10);

NewMenuItem('plm', 'Maintenance', __('Quality Tests Maintenance'), '/QATests.php', 11);
NewMenuItem('plm', 'Maintenance', __('Product Specifications'), '/ProductSpecs.php', 12);
NewMenuItem('plm', 'Maintenance', __('Product Spec Groups Maintenance'), '/ProdSpecGroups.php', 13);

NewMenuItem('plm', 'Maintenance', __('Import Manufacturers from CSV'), '/Z_ImportManufacturers.php', 14); // TODO create script
NewMenuItem('plm', 'Maintenance', __('Import Files/URLS from CSV'), '/Z_ImportStocksFilsUrls.php', 15); // TODO create script

NewMenuItem('plm', 'Maintenance', __('Review Translated Descriptions'), '/RevisionTranslations.php', 16);
