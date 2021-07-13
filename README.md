# PayZen for OpenMage

PayZen for OpenMage is an open source plugin that links e-commerce websites based on OpenMage to PayZen secure payment gateway developed by [Lyra Network](https://www.lyra.com/).

## Installation & upgrade

If plugin is already installed, search for Payzen in your OpenMage root directory and delete found files before upgrade.

- If an older version of the plugin is installed, remove folder app/code/community/Lyranetwork.
- Unzip plugin files to your OpenMage root directory.
- Refresh OpenMage cache by deleting all files from [OPENMAGE]/var/cache/ or through OpenMage admin panel (System / Cache management menu).

## Configuration

In the OpenMage admin panel:

- Go to System > Configuration menu.
- Open SALES > Payment methods section.
- If your OpenMage installation has several websites, shops or views, change the current configuration scope in the upper-left corner to the desired context.
- Click to expand the PayZen section.
- If you installed an older version of the PayZen payment module, click "Reset" to take into account the new module features.

## License

Each PayZen payment module source file included in this distribution is licensed under the Open Software License (OSL 3.0).

Please see LICENSE.txt for the full text of the OSL 3.0 license. It is also available through the world-wide-web at this URL: https://opensource.org/licenses/osl-3.0.php.