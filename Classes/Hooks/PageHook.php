<?php
namespace WebVision\WvT3unity\Hooks;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2020 web-vision GmbH
 */

use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WebVision\WvT3unity\Xclass\SimpleDataHandlerController;
use TYPO3\CMS\Backend\Routing\UriBuilder;

/**
 * This class is a hook class which adds a custom button for magento block cache clearing
 *
 */
class PageHook
{
    /**
     * Injects custom button configuration into the buttonBar object
     *
     * @param array $params Parameters from the base class.
     * @param ButtonBar $buttonBar ButtonBar object.
     *
     * @return array
     */
    public function render(array $params, ButtonBar $buttonBar)
    {
        $buttons = $params['buttons'];
        // Data required for redirection into the current module after processing
        $getData = GeneralUtility::_GET();
        $routeArray = explode('/', $getData['route']);
        $id = $getData['id'];
        $moduleName = $routeArray[2] . '_' . $routeArray[3];
        // Configure new button with custom data
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $button = $buttonBar->makeLinkButton();
        $button->setIcon($iconFactory->getIcon(
            'actions-system-cache-clear-impact-medium',
            Icon::SIZE_SMALL
        ));
        $button->setTitle('Clear Magento Block Cache');
        $button->setShowLabelText(true);
        //create link/route and set book uid
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $uri = $uriBuilder->buildUriFromRoutePath(
            '/mageblockcache',
            [
                'page' => $id,
                'redirectModule' => $moduleName
            ]
        );
        $button->setHref($uri);
        //register button
        $buttons[ButtonBar::BUTTON_POSITION_RIGHT][2][] = $button;
        return $buttons;
    }
}
