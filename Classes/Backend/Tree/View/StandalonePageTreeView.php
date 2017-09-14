<?php
namespace WebVision\WvT3unity\Backend\Tree\View;

/*
 * This file is part of the wv_t3unity Extension for TYPO3 CMS.
 *
 * @WVTODO: Add license
 *
 * The TYPO3 project - inspiring people to share!
 * Copyright (c) 2017 web-vision GmbH
 */

use TYPO3\CMS\Backend\Tree\View\PageTreeView;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Extends Backend's PageTreeView to handle standalone module requests.
 */
class StandalonePageTreeView extends PageTreeView
{
    /**
     * @var bool
     */
    protected $renderStandalone = false;

    /**
     * @var string
     */
    protected $pageTreeUrl = null;

    /**
     * Wrapping $title in a-tags.
     *
     * @param string $title Title string
     * @param array $row Item record
     * @param int $bank Bank pointer (which mount point number)
     *
     * @return string
     * @access private
     */
    public function wrapTitle($title, $row, $bank = 0)
    {
        if ($this->renderStandalone) {
            return '<a href="' . $this->pageTreeUrl . $this->getJumpToParam($row) . '">' . $title . '</a>';
        }

        return parent::wrapTitle($title, $row, $bank);
    }

    /**
     * @param bool $standalone
     * 
     * @return StandalonePageTreeView
     */
    public function setStandaloneMode($standalone)
    {
        $this->renderStandalone = $standalone;

        if ($standalone) {
            $params = GeneralUtility::_GET();
            $this->pageTreeUrl = 'index.php?';
            $iterator = 0;

            foreach($params as $key => $value) {
                if ($key != 'id') {
                    $this->pageTreeUrl .= ($iterator > 0 ? '&' : '') . $key . '=' . $value;
                }

                $iterator++;
            }

            $this->pageTreeUrl .= '&id=';
        } else {
            $this->pageTreeUrl = null;
        }

        return $this;
    }
}
