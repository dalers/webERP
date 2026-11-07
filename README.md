# [webERP-PLM (webERP with PLM Extensions)](https://dalescott.net)

This fork is for the development of a "PLM" module for webERP, guided by a desire to easily navigate from one item to another using BOMs as a network of highways between them. A related project is the workflow for managing drawings, documents, test reports and other quality documents and records using the existing webERP Wiki Knowledge Base interface and WackoWiki.

The PLM module may one day be included in the webERP repository similar to other modules or possibly alternatively a standard module interface could be defined for custom modules such as the PLM module.

## Modules

It would benefit the webERP community to have a standard workflow for adding custom modules, while still keeping the rest of a webERP site updated with the project, including security enhancements, bug fixes and new features (including the possibility of new modules).

"Modules" in webERP are a user interface metaphor grouping scripts into general domains of work (e.g. Purchasing, Inventory, Manufacturing, Asset Magement, HR...). Visibility of a module is controlled by the user record and menu entries are filtered by the user's assigned role (e.g. System Administrator, Accounts Payable, PLM Author, PLM Viewer...)

## webERP Module Mental Model

Core Modules

- Receivables
- Payables
- Inventory
- General Lededger
- Petty Cash
- Setup
- Utilities

Add-On Modules

- Manufacturing
- Sales
- Purchases
- Forecast
- Asset Manager
- HR
- PLM (added new here)

Core and several Add-On modules are included with webERP. A custom Add-On module can be developed and then installed by a webERP site system administrator (by extracting a zip archive or referencing an online repo and also manually running a custom-update file). 

## Other Notes

### Strive for the Fewest Menu Items

Recent demos have suggested a novice or casual user benefits from a simple menu system with fewer and more obvious menu items (which implies complication is managed within the menu-item script). This practise is already followed in:

- SelectProduct.php by not rendering menu items which would be irrelevent (e.g. menu items related to BOMs when the selected item cannot have a BOM)

- SelectProduct.php does not show price, supplier or cost unless the user has token 12 (Prices Security), 9 (Supplier centre – Supplier access only) or 18 (Cost authority). SelectProduct.php itself only requires token 2 (Basic Reports and Inquiries...).

Now consider there are several BOM-related menu items (aka scripts) and a user must chose the appropriate one based on their need and security role. Instead there could be one BOMs script that limits what the user is shown or what actions they can perform based on their security role. This would save two menu items, making the first choice simpler for the user and taking less screen space. Reducing the number of scripts also reduces testing, maintenance and system validation.

### Custom Modules, the GPL and the SaaS Loophole

IANAL and the following is my personal opinion.

Given that webERP is licensed according to the GPL, a fork must also be licensed according to the GPL but it is expected for sites to include custom scripts developed seperate from webERP and so long as no distribution takes place the custom scripts do not need to be distributed.

The GPL includes the "SaaS Loophole" which allows a SaaS service provider to create their own custom modules, modify webERP in general and fix issues the webERP project would benefit from, and sell the service to users, without having to publish any of their changes because the GPL does not consider serving an application to a user as "distributing".


# [webERP - Accounting and Business Administration ERP System](https://www.weberp.org/)

[![Download](https://img.shields.io/sourceforge/dm/web-erp.svg)](https://sourceforge.net/projects/web-erp/files/latest/download)
[![Download](https://img.shields.io/sourceforge/dt/web-erp.svg)](https://sourceforge.net/projects/web-erp/files/latest/download)
[![Build Status](https://github.com/timschofield/webERP/actions/workflows/ci.yaml/badge.svg)](https://github.com/timschofield/webERP/actions/workflows/ci.yaml)
[![GitHub last commit (master)](https://img.shields.io/github/last-commit/timschofield/webERP/master.svg)](https://github.com/timschofield/webERP/commits/master/)
[![GitHub pull requests](https://img.shields.io/github/issues-pr-raw/timschofield/webERP.svg)](https://github.com/timschofield/webERP/pulls)

## Introduction

webERP is a free open-source ERP system, providing best practise, multi-user business administration and accounting tools
over the web. For further information and for a full list of features, please visit the support site at: https://www.weberp.org/

## Demo

A live webERP demo using the latest code is available at https://www.weberp.org/demo/ where you can log in and experiment
with all the features of webERP.

## Download Now

Downloading the latest release [from GitHub](https://github.com/timschofield/webERP/releases) is recommended for new installations. If you prefer,
clone the project and follow the stable branch.

You can download a snapshot of the active development branch [from GitHub](https://github.com/timschofield/webERP/archive/refs/heads/master.zip) (or fork or clone the repo).

## Requirements
- Web server (webERP has been tested on Apache, Nginx, Lighthttpd, and Hiawatha).
- PHP version 8.1 and above (the latest supported release is recommended).
- Either MySQL version 5.7.5+ or MariaDB version 5.8+ (if MariaDb, v10 is recommended but v10-specific features are not used).
- Web browser with HTML5 compatibility.

Further information about hardware and software requirements can be found in [webERP documentation](https://www.weberp.org/Documentation.html)
and elsewhere on the website.

## Installation

### New installation

1. Download the latest webERP source code [from GitHub](https://github.com/timschofield/webERP/releases).
2. Extract the top level folder from the downloaded file.
3. Everything inside the extracted folder needs to be uploaded/copied to your webserver, for example, into your
   `public_html` or `www` or `html` folder (the folder will already exist on your webserver).
4. Create an empty database, taking note of your username, password, hostname, and database name.
   NB: the database user must have sufficient permissions to create triggers and functions.
5. In your browser, enter the address to your site, such as: www.example.com (or if you uploaded it into another subdirectory
   such as _foldername_ use www.example.com/foldername)
6. Follow the instructions that appear in your browser for installation.

### Upgrading

1. Download the latest webERP source code [from GitHub](https://github.com/timschofield/webERP/releases).
2. Extract the top level folder from the downloaded file.
3. Backup the `config.php` script and `companies/` directory from your previous installation.
4. Everything inside the folder you unzipped needs to be uploaded/copied to your webserver, overwriting your previous installation.
5. Verify that the `config.php` script and `companies/` directory are intact, and if not, restore them from your backup.
6. In your browser, enter the address to your site, such as: www.example.com (or if you uploaded it into another subdirectory such as foldername use www.example.com/foldername).
7. After you log-in, if any database upgrades are required, you will be prompted to install them.

Further information about installation and upgrading is available in the [documentation](https://www.weberp.org/demo/ManualContents.php?ViewTopic=GettingStarted).

## Documentation

The webERP "Manual" is a guide for both users and developers. This is intentional to save time finding information, avoid duplication and encourage
consistency. If the information is too detailed for your needs, you're diving deeper than you need to.

The Manual is part of webERP. Users access the Manual by clicking on the `Manual` button in the top menu bar (also available in
the [live demo.](https://www.weberp.org/demo/ManualContents.php)) (the manual first displays content specific to the current script).

The Manual is supplemented by detailed developer documentation when warranted. Refer to the webERP `./doc/developers/` folder or browse
online at https://github.com/timschofield/webERP/tree/master/doc/developers

## Support

Free support is available 24/7 from an enthusiastic community of webERP users, integrators, developers and supporters.

The primary means of communication and support is the GitHub repository, which includes a [discussions forum](https://github.com/timschofield/webERP/discussions), an [issue tracker](https://github.com/timschofield/webERP/issues) and a [Wiki](https://github.com/timschofield/webERP/wiki). Generally start with a discussion and elevate when and if appropriate to either an issue (specific defective or missing functionality) or a Wiki page (summarized conclusion for understanding and guidance).

A low-volume mailing list used mostly for notices can be joined at: https://sourceforge.net/projects/web-erp/lists/web-erp-users

Communication in the community is encouraged but before posting please do a quick search in the project on GitHub or a general web search in case your question has already been answered. The mailing list archive can be searched at: https://sourceforge.net/p/web-erp/mailman/

## Contribute to the webERP project

Contributions of code and documentation including How-Tos with screenshots etc... are very much appreciated. Perhaps you could share training
slides or process information created by you or your business for the benefit of others. Contributing bug reports, testing code or providing
feedback or suggestions in an Issue, Discussion or Wiki page in the repository are other ways to help the project.

General guidelines for contributing code can be found at: https://www.weberp.org/Development.html

The webERP Code of Conduct, Coding Standards and a detailed Development Workflow can be found at https://github.com/timschofield/webERP/tree/master/doc/developers

Contributors need to read those document carefully and follow the guidelines therein. Code standards and conventions are rigorously applied
in the interests of consistency and readability.

## Legal

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

A copy of the GNU General Public License is included in the doc directory along with this program; if not, write to the
Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

Copyright © 2003-2026 The webERP Contributors - Contact: info@weberp.org
