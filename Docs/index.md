# web-vision Mage UniTY for TYPO3

The Mage UniTY extension, developed by web-vision, allows seamless integration of the TYPO3 content management system with an existing Magento 2.x shop.
This integration enhances search service optimization, improves visitor experience, and facilitates professional content marketing for your Magento store.
Use TYPO3 as content mamagement system inside or side-by-side for your Magento sore.

Checkout the video here: https://youtu.be/q6b1Eg8bS7k


## System Requirements
- A working TYPO3 CMS v11 LTS System
- A Magento 2.4.x version with an installed Magento UniTY Extension, which can be found here: https://github.com/extendware/Magento-TYPO3-UniTY


## Installation

Easiest way to install the extension is via composer.

```sh
    composer req web-vision/wv_t3unity
```

Alternatively you can install the extension manually from Github.
Add the github repositiory to your composer.json file:

```sh
composer config repositories.t3_unity vcs git@github.com:web-vision/Magento-TYPO3-UniTY.git
```

Install the extension via composer:

```sh
composer require web-vision/wv_t3unity:dev-main
```


## Configuration

After the Extension is installed, include the Extension in static TypoScript template in your Template Module.

![TYPO3 Backend showing the Template module and the include section, with the included Unity Extension on the Left side](Docs/Images/include-ext-in-static-template.png)

## Usage

After adding content to your TYPO3 Backend you can have to select one of the Unity Backend Layouts for your page. Now you will be able to open the page with
the  UniTY parameters.

https://demo.web-vision.de/?id=106&type=3211


## Types of Rendering

### Full page

Full page rendering is done via typeNum 3211. This will render the whole page with the TYPO3 Header and Footer depending on the Layout configuration.


### Column Rendering

To Render only one colPos we use the typeNum 3212. This will render only the content of the selected colPos. The Header and Footer will not be rendered.

Demo Example:

https://demo.web-vision.de/?id=106&type=3212&colPos=0

### Rendering of a single content element

To render a single content element we use the typeNum 3213. This will render only the content element with the given uid.

https://demo.web-vision.de/?id=106&type=3213&uid=315