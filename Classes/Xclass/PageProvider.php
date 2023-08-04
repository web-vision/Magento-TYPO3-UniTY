<?php
namespace WebVision\WvT3unity\Xclass;

use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

/**
 * Class responsible for providing click menu items for db records which don't have custom provider (as e.g. pages)
 */
class PageProvider extends \TYPO3\CMS\Backend\ContextMenu\ItemProviders\PageProvider
{
    /**
     * Additional attributes for the 'view' item
     *
     * @return array
     */
    protected function getViewAdditionalAttributes(): array
    {
        $attributes = [];
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        // Collecting the extension option to check whether redirect have to made
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('wv_t3unity');
        $magePreview = $extensionConfiguration['magPagePreview'];
        // Magento preview enabled
        if ($magePreview == 1) {
            $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
            $tsSetting = $configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
            );
            // Collecting the magento URL saved in TS object
            $magUrl = $tsSetting['lib.']['magurlValue.']['value'];
            $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
            $urlSegment = $pageRepository->getPage($this->getPreviewPid())['slug'];

            if ($urlSegment != null) {
                // Writing the redirect URL with magento baseURL and with a trailing slash
                $viewLink = rtrim($magUrl, "/") . $urlSegment . '/';
            } else {
                $viewLink = $this->getViewLink();
            }
        } else {
            // Magento preview disabled and vanilla URL generation
            $viewLink = $this->getViewLink();
        }
        if ($viewLink) {
            $attributes += [
                'data-preview-url' => htmlspecialchars($viewLink),
            ];
        }
        return $attributes;
    }
}
