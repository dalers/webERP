# [webERP-PLM (webERP with PLM Extensions)](https://dalescott.net)

This fork is to development a top-level "PLM" module for webERP, guided by a vision for effectively navigating to and managing the items within the system, including supporting documents and quality records stoted in an Wiki Knowledge Base. It is not clear yet if any new menu item scripts will be required and initially the PLM module will be a collection of existing scripts.

It has been suggested a novice or casual user needs a simple menu system with fewer and more obvious menu items (which an experienced user can easily skip through). This implies complication must be managed within a menu-item script. E.g. there are several scripts for viewing a BOM and a user must chose the appropriate script based on their need and security role. Instead there could be one BOMs script that takes into consideration the user's security profile and allows the user to see and/or do only what their security role allows (at least one script does this now although its name escapes me). In this example, the number of BOM-related menu items could be reduced from 3 to 1 providing a significant improvement in screen space for clarity. An added benefit is reducing the number of scripts which reduces work for testing, development, user configuration and system validation.

The PLM module may one day be included in the webERP repository similar to other modules or alternatively a standard plugin interface could be defined for modules and the PLM module the first to use it (which could be as simple as simply being located in a common "plugins" directory).

## Proposed Plugin System

It would benefit the webERP community to have a standard method of customizing webERP that simplifies the work to maintaining a site and keep it updated with security enhancements, bug fixes and new features from the webERP project.

"Modules" in webERP are a user interface metaphor to group scripts into general workflow domains. I.e. the HR module is where people are managed, the Manufacturing module is where manufacturing is initiated and managed, the Asset Manager module is for managing assets and the PLM module is for managing items, their relationships, supporting documents and quality records.

## webERP Mental Model

Core

- Receivables
- Payables
- Inventory
- General Lededger
- Setup
- Utilities

Modules

- Manufacturing
- Sales
- Purchases
- Forecast
- Asset Manager
- HR
- Petty Cash _(maybe, if not Petty Cash is included in "Core")_
- PLM (new)

Modules can be included with webERP or a "custom" modules can be developed outside of the webERP project and installed by a system administrator - by extracting from a zip archive, creating a git sub-project, installing using Composer, or some other method. The PLM module could be such a module. Existing Manufacturing, Sales, Purchases, Forecast, Asset Manager, HR and maybe Petty Cash modules could be migrated to the standardized system to standardize code, improve clarity and reduce project development and maintenance as well as user custom  development and maintenance.

## License Thoughts

IANAL and this is my personal opinion. webERP is licensed according to the GPL and any development on webERP is licensed according to the GPL, including PLM module here. It is possible to develop a plug-in seperate from webERP provided using any license, including no license at all (i.e. a fully proprietary module), but the resulting integration is licensed according to the GPL.

The GPL includes the "SaaS Loophole" (which the AGPL was created to avoid). The "SaaS" loophole would allow a SaaS service provider to develop their own product using webERP, possibly with their own plug-ins and possibly including bug fixes, or improvements that webERP would benefit from, and not have to publish their changes (the GPL does not consider providing SaaS as distributing). I don't have an opinion on the impact of this on webERP and whether it's good or bad, it's just the way it is.

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
