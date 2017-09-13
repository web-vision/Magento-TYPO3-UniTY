<?php
namespace WebVision\WvT3unity\Backend\Tree\View;

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

use TYPO3\CMS\Backend\Tree\View\PageTreeView;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
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
