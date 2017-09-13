<?php
namespace WebVision\WvT3unity\ViewHelpers;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */


use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Viewhelper to generate preview URL if backend user is logged in.
 * The link generates based on extension settings.
 *
 * @author Yannick Hermes <y.hermes@web-vision.de>
 */
class GeneratePreviewLinkViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $extConf = null;

    public function initializeArguments()
    {
        parent::initializeArguments();
        //$this->registerArgument('data', 'array', 'Page data.');
    }

    /**
     * @param array $data Page data.
     *
     * @return void
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
    }

    /**
     * Generate preview URL.
     *
     * @return string
     */
    protected function generatePreviewLink() {
        $url = $this->extConf['MagentoUrl'];

        $urlHasProtocol = (strpos($url, 'https://') > -1 ? true : (strpos($url, 'http://') > -1));

        $urlHasLastSlash = strpos(strrev($url), '/') == 0;
        // Remove last slash from url as the basePath always has one as first.
        $url = ($urlHasLastSlash ? $this->removeLastOccurence('/', '', $url) : $url);

        $urlPathSegment = $this->arguments['data']['unity_path'];

        $previewUrl = ($urlHasProtocol ? $url : $this->getProtocol() . $url) . $urlPathSegment;


        $this->escapeOutput = false;

        $return = '';
        if(GeneralUtility::isValidUrl($previewUrl)) {
            $pattern = '<script>window.location.href = "%s"</script>';
            $return = sprintf($pattern, $previewUrl);
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

    /**
     * Removes last occurence of string in a string.
     *
     * @param string $search
     * @param string $replace
     * @param string $subject
     *
     * @return string
     */
    protected function removeLastOccurence($search, $replace, $subject) {
        return strrev(
            implode(
                strrev($replace),
                explode(
                    strrev($search),
                    strrev($subject),
                    2
                )
            )
        );
    }
}
