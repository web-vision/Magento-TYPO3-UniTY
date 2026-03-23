<?php

declare(strict_types=1);

namespace WebVision\WvT3unity\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use WebVision\WvT3unity\Utility\Configuration;

/**
 * @todo recheck, as the class uses deprecated extends
 * This class renders all meta data as json
 */
final class ContentPostProc extends AbstractPlugin
{
    /**
     * This method get's called by the hook and will parse the html head data into a
     * json.
     *
     * @param array<array-key, mixed> $params
     * @param mixed $that
     */
    public function hookEntry(array $params, mixed &$that): void
    {
        $typoUrl = (is_array($this->loadTS(1)['lib.']['urlValue.']) ? $this->loadTS(1)['lib.']['urlValue.']['value'] : null);
        if (Configuration::isMagentoContent($params['pObj']->type, 'head')) {
            $this->removeGenerator($params['pObj']->content);
            $this->parseMetaTags($params['pObj']->content);
            $this->parseCss($params['pObj']->content);
            $this->parseJs($params['pObj']->content);

            $params['pObj']->content = preg_replace('/,\s?]/', ']', $params['pObj']->content);
            // Attaching TYPO3 baseURL to the fileadmin URLs
            if ($typoUrl != null) {
                $params['pObj']->content = preg_replace('/%BASE_URL%\/fileadmin\//', rtrim($typoUrl, '/') . '/fileadmin/', $params['pObj']->content);
            }
        }
    }

    /**
     * This method removes the meta tags with name generator.
     *
     * @param string $content The content to parse.
     */
    protected function removeGenerator(string &$content): void
    {
        $content = preg_replace('/<meta name="generator".*?>/', '', $content);
    }

    /**
     * This method parses meta tags with a name or property attribute into a json
     *
     * @param string $content The content to parse.
     */
    protected function parseMetaTags(string &$content): void
    {
        $content = preg_replace_callback(
            '/<meta (name|property)="(.*?)" content="(.*?)" ?\/?>/s',
            [$this, 'metaCallback'],
            $content
        );
    }

    /**
     * This method replaces link tags with the value of the href attribute.
     *
     * @param string $content The content to parse.
     */
    protected function parseCss(string &$content): void
    {
        $content = preg_replace('/<link rel=".*?" type=".*?" href="(.*?)" media=".*?"\s*\/{0,1}>/', '"$1",', $content);
    }

    /**
     * This method replaces script tags with the value of the src attribute.
     *
     * @param string $content The content to parse.
     */
    protected function parseJs(string &$content): void
    {
        $content = preg_replace('/<script( src="(.*?)")? type=".*?" ?\/?>(<\/script>)?/', '"$2",', $content);
    }

    /**
     * Helper method used as callback for preg_replace_callback to parse the matches
     * into a json.
     *
     * @param array<array-key, mixed> $matches The matches of the preg_replace_callback method.
     *
     * @return string The generated json.
     */
    public function metaCallback(array $matches): string
    {
        $matches[3] = str_replace(["\r\n", "\n"], ' ', $matches[3]);

        return '{"' . $matches[1] . '": "' . $matches[2] . '", "content":"' . $matches[3] . '"},';
    }

    /**
     * @todo recheck, as the method uses deprecated functionality
     * @throws Exception
     * @param int $pageUid pageuid from where TS template should be accessed
     * @return array<array-key, mixed>
     */
    public function loadTS(int $pageUid): array
    {
        $backendUtility = GeneralUtility::makeInstance(BackendUtility::class);
        $rootLine = $backendUtility->BEgetRootline($pageUid);
        $TSObj = GeneralUtility::makeInstance(TemplateService::class);
        $TSObj->tt_track = false;
        //$TSObj->init();  TODO - Need to test later whether ts setup returned correctly
        $TSObj->runThroughTemplates($rootLine);
        $TSObj->generateConfig();
        return $TSObj->setup;
    }
}
