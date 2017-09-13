<?php
namespace WebVision\WvT3Unity\Backend\Routing;

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

use TYPO3\CMS\Backend\Routing\UriBuilder as BackendUriBuilder;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 */
class UriBuilder extends BackendUriBuilder
{
    /**
     * @param array $parameters An array of GET parameters
     * @param string $referenceType The type of reference to be generated (one of the constants)
     *
     * @return Uri
     */
    protected function buildUri($parameters, $referenceType)
    {
        if (GeneralUtility::_GP('standalone') == 1) {
            ArrayUtility::mergeRecursiveWithOverrule(
                $parameters,
                [
                    'standalone' => 1
                ]
            );
        }

        return parent::buildUri($parameters, $referenceType);
    }
}
