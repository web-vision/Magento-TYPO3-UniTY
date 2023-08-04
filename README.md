# Magento to TYPO3 Connector: Mage UniTY for Magento 2 and TYPO3
The Mage UniTY extension, developed by web-vision, allows seamless integration of the TYPO3 content management system with an existing Magento 2.x shop.
This integration enhances search service optimization, improves visitor experience, and facilitates professional content marketing for your Magento store.
Use TYPO3 as content mamagement system inside or side-by-side for your Magento sore.

Checkout the video here: https://youtu.be/q6b1Eg8bS7k

## System Requirements
- A working TYPO3 CMS v11 LTS System
- A Magento 2.4.x version with an installed Magento UniTY Extension, which can be found here: https://github.com/extendware/Magento-TYPO3-UniTY

## Installation

Add the github repositiory to your composer.json file:

```composer config repositories.t3_unity vcs git@github.com:web-vision/Magento-TYPO3-UniTY.git```

Install the extension via composer:

```composer require web-vision/wv_t3unity:dev-main```

## Configuration

After the Extension is installed, include the static TypoScript template in your template.

Now you can test if you have Unity ouput via typeNum 3212. Add this to `/index.php?id=1&type=3211` to the url and you should get page output
and `/index.php?id=1&type=3212colPos=0` will ouput the colPos 0.

Furter information can be found in the manual: TBD.