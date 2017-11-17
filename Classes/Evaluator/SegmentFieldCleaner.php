<?php
namespace WebVision\WvT3Unity\Evaluator;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

/**
 * This class contains form field evaluator that replaces underscore by minus
 * from tx_realurl_pathsegment field on save.
 *
 * @author Yannick Hermes <y.hermes@web-vision.de>
 */
class SegmentFieldCleaner
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function evaluateFieldValue($value)
    {
        $value = str_replace('_', '-', $value);
        return $value;
    }
}
