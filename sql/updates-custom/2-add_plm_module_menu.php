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
NewMenuItem('plm', 'Reports', __('Where Used Inquiry (Indented)'), '/BOMIndentedReverse.php', 12);
NewMenuItem('plm', 'Reports', __('BOM Listing'), '/BOMListing.php', 8);
NewMenuItem('plm', 'Reports', __('BOM Listing (Indented)'), '/BOMIndented.php', 9);
NewMenuItem('plm', 'Reports', __('List Components Required'), '/BOMExtendedQty.php', 10);
NewMenuItem('plm', 'Reports', __('List Components Not Used'), '/MaterialsNotUsed.php', 11);

NewMenuItem('plm', 'Reports', __('Print Product Specification'), '/PDFProdSpec.php', 13);
NewMenuItem('plm', 'Reports', __('Print Certificate of Analysis'), '/PDFCOA.php', 14);
NewMenuItem('plm', 'Reports', __('Historical QA Test Results'), '/HistoricalTestResults.php', 15);

// Maintenance pane (right)
