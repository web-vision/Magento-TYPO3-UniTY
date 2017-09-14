<?php
namespace WebVision\WvT3Unity\Backend\Routing;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use TYPO3\CMS\Backend\Routing\UriBuilder as BackendUriBuilder;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Extends Backend's UriBuilder to append standalone parameter
 * to generated module URLs.
 */
class UriBuilder extends BackendUriBuilder
{
    /**
     * @param array $parameters An array of GET parameters
     * @param string $referenceType The type of reference to be generated (one of the constants)
     *
     * @return string
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
