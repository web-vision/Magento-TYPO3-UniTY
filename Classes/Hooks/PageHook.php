<?php
namespace WebVision\WvT3unity\Hooks;

use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use WebVision\WvT3unity\Xclass\SimpleDataHandlerController;

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
        $queryParams = $GLOBALS['TYPO3_REQUEST']->getQueryParams();
        // Data required for redirection into the current module after processing

        if (!empty($getData)) {$queryParams = $GLOBALS['TYPO3_REQUEST']->getQueryParams();
            $routeArray = explode('/', $getData['route']);
            $id = $queryParams['id'];

            if (str_starts_with($getData['route'], '/module/web')) {
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
            }
        }
        return $buttons;
    }
}
