# Magento 2 Module Lof MultiBarcode

    ``landofcoder/module-multibarcode``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)

## Main Functionalities
- Support generate barcode for multi source products
- Compatible with MSI of magento 2.3.6-2.4.x

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Composer (Recommended)

 - Install the module composer by running `composer require landofcoder/module-multibarcode`
 - enable the module by running `php bin/magento module:enable Lof_MultiBarcode`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Generate static files by running `php bin/magento setup:static-content:deploy -f`
 - Flush the cache by running `php bin/magento cache:flush`


### Type 2: Zip file

 - Unzip the zip file in `app/code/Lof`
 - Enable the module by running `php bin/magento module:enable Lof_MultiBarcode`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Generate static files by running `php bin/magento setup:static-content:deploy -f`
 - Flush the cache by running `php bin/magento cache:flush`

