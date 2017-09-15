<?php
namespace WebVision\WvT3unity\ViewHelpers;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Viewhelper to generate preview URL if backend user is logged in.
 * The link generates based on extension settings.
 *
 * @author Yannick Hermes <y.hermes@web-vision.de>
 */
class GeneratePreviewLinkViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    protected $extConf = null;

    protected $escapeOutput = false;

    protected $escapeChildren = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('data', 'array', 'Page data.');
    }

    /**
     * @return string
     */
    public function render() {
        if (! empty($GLOBALS['BE_USER']->user['username'])) {
            $this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wv_t3unity']);

            $previewLinkVariableName = 'previewLink';

            if ($this->templateVariableContainer->exists($previewLinkVariableName)) {
                $this->templateVariableContainer->remove($previewLinkVariableName);
            }

            $this->templateVariableContainer->add($previewLinkVariableName, $this->generatePreviewLink());

            return $this->renderChildren();
        }

        return '';
    }

    /**
     * Generate preview URL.
     *
     * @return string
     */
    protected function generatePreviewLink() {
        $url = $this->extConf['MagentoUrl'];

        $scheme = parse_url($url, PHP_URL_SCHEME);

        $urlHasProtocol = isset($scheme) && ($scheme === 'http' || $scheme === 'https');

        // Remove last slash from url as the basePath always has one as first.
        $url = rtrim($url, '/');

        $urlPathSegment = $this->arguments['data']['unity_path'];

        $previewUrl = ($urlHasProtocol ? $url : $this->getProtocol() . $url) . $urlPathSegment;

        $this->escapeOutput = false;

        $return = '';
        if(GeneralUtility::isValidUrl($previewUrl)) {
            $pattern = '<script>window.location.href = "%s"</script>' . chr(10) .
                '<a href="%s" style="font: bold 11px Arial;text-decoration: none;background-color: #EEEEEE;color: #333333;padding: 2px 6px 2px 6px;border-top: 1px solid #CCCCCC;border-right: 1px solid #333333;border-bottom: 1px solid #333333;border-left: 1px solid #CCCCCC;">Magento preview</a>';
            $return = sprintf($pattern, $previewUrl, $previewUrl);
        }

        return $return;
    }

    /**
     * Returns current protocol as string.
     *
     * @return string
     */
    protected function getProtocol() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://');
    }
}
