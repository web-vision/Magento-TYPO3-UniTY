<?php
namespace WebVision\WvT3unity\Xclass;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 *
 * @author Anu Bhuvanendran Nair <anu@web-vision.de>
 */
class SimpleDataHandlerController extends \TYPO3\CMS\Backend\Controller\SimpleDataHandlerController
{

     /**
     * Injects the request object for the current request or subrequest
     * As this controller goes only through the processRequest() method, it just redirects to the given URL afterwards.
     *
     * @param ServerRequestInterface $request the current request
     * @return ResponseInterface the response with the content
     */
    public function mainAction(ServerRequestInterface $request): ResponseInterface
    {
        // Identifying the magento cache clear command
        if ($this->cacheCmd == 'magento') {
            $this->clearCache();
        }
        return parent::mainAction($request);
    }
    

    /**
     * Clear magento cache via the API call...
     */
    public function clearCache()
    {
        // Collecting the magento URL saved in TS object
        $magUrl = $this->getMagUrl();

        if ($magUrl == null) {
            echo 'The magento URL is not saved in TypoScript settings.';
        } else {
            $url = rtrim($magUrl, "/") . '/rest/V1/unity/clearAllCaches/cacheType/all';
            $result = file_get_contents($url);
            if ($result != 'true') {
                echo 'false';
            }
        }
    }

    /**
     * Collect magento baseURL from TS
     */
    public function getMagUrl()
    {
        // Initializing configuration manager for reading
        // TS settings
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
        $tsSetting = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        // Collecting the magento URL saved in TS object
        $magUrl = $tsSetting['lib.']['magurlValue.']['value'];
        return $magUrl;
    }
}
