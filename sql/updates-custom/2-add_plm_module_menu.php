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
//
// The PLM module does not perform Transactions
//
// The PLM module menu provides for stock adjustments of a selected item (using
//  menu item: [ PLM > Select an Item > { select an item } > Item Transactions >
//    Quantity Adjustments ]) but ad hoc adjustments are done in the Inventory module
//      [ Inventory > Transactions > Inventory Adjustments ]


// Reports pane (middle)
// A menu item whose URL starts with '#spacer' is displayed as a blank line by index.php.
// Each spacer needs a unique caption and URL.
NewMenuItem('plm', 'Reports', __('Serial Item Research Tool'), '/StockSerialItemResearch.php', 1);
NewMenuItem('plm', 'Reports', 'Spacer 1', '#spacer1', 2);
NewMenuItem('plm', 'Reports', __('Inventory Item Movements'), '/StockMovements.php', 3);
NewMenuItem('plm', 'Reports', __('Inventory Item Status'), '/StockStatus.php', 4);
NewMenuItem('plm', 'Reports', __('Inventory Item Usage'), '/StockUsage.php', 5);
NewMenuItem('plm', 'Reports', __('Inventory Quantities'), '/InventoryQuantities.php', 6);
NewMenuItem('plm', 'Reports', 'Spacer 2', '#spacer2', 7);
NewMenuItem('plm', 'Reports', __('Costed BOM Inquiry'), '/BOMInquiry.php', 8);
NewMenuItem('plm', 'Reports', __('Where Used Inquiry'), '/WhereUsedInquiry.php', 9);
NewMenuItem('plm', 'Reports', __('Where Used Inquiry (Indented)'), '/BOMIndentedReverse.php', 10); // TODO hide if selected item cannot have BOM?
NewMenuItem('plm', 'Reports', __('BOM Listing'), '/BOMListing.php', 11); // TODO hide if selected item cannot have BOM?
NewMenuItem('plm', 'Reports', __('BOM Listing (Indented)'), '/BOMIndented.php', 12); // TODO hide if selected item cannot have BOM?
NewMenuItem('plm', 'Reports', __('List Components Required'), '/BOMExtendedQty.php', 13);
NewMenuItem('plm', 'Reports', __('List Components Not Used'), '/MaterialsNotUsed.php', 14);
NewMenuItem('plm', 'Reports', 'Spacer 3', '#spacer3', 15);
NewMenuItem('plm', 'Reports', __('Print Product Specification'), '/PDFProdSpec.php', 16);
NewMenuItem('plm', 'Reports', __('Print Certificate of Analysis'), '/PDFCOA.php', 17);
NewMenuItem('plm', 'Reports', __('Historical QA Test Results'), '/HistoricalTestResults.php', 18);
NewMenuItem('plm', 'Reports', 'Spacer 4', '#spacer4', 19);

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
