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

use TYPO3\CMS\Backend\Tree\View\BrowseTreeView;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Extends Backend's PageTreeView to handle standalone module requests.
 */
class StandalonePageTreeView extends BrowseTreeView
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

            foreach ($params as $key => $value) {
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

    /**
     * Wrap the plus/minus icon in a link
     *
     * @param string $icon HTML string to wrap, probably an image tag.
     * @param string $cmd Command for 'PM' get var
     * @param string $bMark If set, the link will have an anchor point (=$bMark) and a name attribute (=$bMark)
     * @param bool $isOpen
     * @return string Link-wrapped input string
     * @access private
     */
    public function PM_ATagWrap($icon, $cmd, $bMark = '', $isOpen = false)
    {
        if ($this->thisScript) {
            $aUrl = $this->getThisScript() . 'PM=' . $cmd;

            return '<a class="list-tree-control ' . ($isOpen ? 'list-tree-control-open'
                    : 'list-tree-control-closed') . '" href="' . htmlspecialchars($aUrl) . '"><i class="fa"></i></a>';
        }

        return $icon;
    }
}
