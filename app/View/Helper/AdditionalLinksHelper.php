<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

/**
 * Renders the given additional links in different formats.
 * @author Patrick Nawracay <patrick.nawracay@it-novum.com>
 * @since  3.0
 */
class AdditionalLinksHelper extends AppHelper
{
    public $helpers = ['Html']; // Allows to use another Helper within this Helper

    /**
     * Renders the list of the given data as list items (<li> tags).
     * $additionalLinks contains a list of elements. Each element is also an array and
     * it MUST have all of the following keys: 'title', 'url', 'options' and 'confirmMessage'.
     *
     * @param string[][] $additionalLinks
     * @param int        $currentIndex The current index or ID of the current item
     *
     * @return string The list items (<li>) with the corresponding links (<a>)
     */
    public function renderAsListItems($additionalLinks, $currentIndex = -1, $addArrParam = [])
    {
        $links = $this->renderLinks($additionalLinks, $currentIndex, $addArrParam);
        $links = array_map(function ($element) {
            return '<li>'.$element.'</li>';
        }, $links);

        return implode($links);
    }

    /**
     * Renders the list of given link data as list of links (<a> tags).
     * $additionalLinks contains a list of elements. Each element is also an array and
     * it MUST have all of the following keys: 'title', 'url', 'options' and 'confirmMessage'.
     *
     * @param string[][] $additionalLinks
     * @param int        $currentIndex The current index or ID of the current item
     *
     * @return string The list of links (<a>)
     */
    public function renderAsLinks($additionalLinks, $currentIndex = -1)
    {
        $links = $this->renderLinks($additionalLinks, $currentIndex);

        return implode($links);
    }

    /**
     * @param string[][] $additionalLinks
     * @param int        $currentIndex The current index or ID of the current item.
     *
     * @return string[] The rendered <a> tags. Each element accords to one link.
     */
    protected function renderLinks($additionalLinks, $currentIndex = -1, $addArrParam = [])
    {
        $result = [];
        foreach ($additionalLinks as $link) {
            if (isset($link['callback']) && isset($addArrParam['Service']['name']) && !$link['callback']($addArrParam['Service']['name']))
                continue;

            $title = $link['title'];
            $url = $link['url'];
            if ($currentIndex !== -1) { // replace 'autoIndex' within a string

                if (is_string($url)) {
                    $url = str_replace('autoIndex', $currentIndex, $url);
                } elseif (is_array($url) && count($url)) { // replace 'autoIndex' accordingly
                    foreach ($url as $key => $value) {
                        if (!in_array($key, ['controller', 'index'], true)) {
                            $url[$key] = str_replace('autoIndex', $currentIndex, $value);
                        }
                    }
                }
            }
            $options = $link['options'];
            $confirmMessage = $link['confirmMessage'];

            $renderedLink = $this->Html->link($title, $url, $options, $confirmMessage);
            $result[] = $renderedLink;
        }

        return $result;
    }

    public function renderElements($additionalElements)
    {
        $return = '';
        foreach ($additionalElements as $element) {
            //, array(), array('plugin' => 'Contacts'));
            $elementArray = explode('.', $element);
            $return .= $this->_View->element($elementArray[1], [], ['plugin' => $elementArray[0]]);
        }

        return $return;
    }
}
