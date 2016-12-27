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

class BbcodeHelper extends AppHelper
{

    /**
     * Converts BB code to HTML
     *
     * @param string $bbcode The BB Code you want to convert to HTML
     * @param bool   $nl2br  If you want to replace \n with <br>
     *
     * @return string with HTML parts
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function asHtml($bbcode, $nl2br = true)
    {
        if (!isset($this->_View->viewVars['bbparser'])) {
            throw new InternalErrorException(__('BB code parser not found! BbcodeComponent not loaded in Controller?'));
        }

        $this->bbparser = $this->_View->viewVars['bbparser'];
        $this->bbparser->parse(htmlentities($bbcode));
        if ($nl2br === true) {
            return nl2br($this->bbparser->getAsHtml());
        }

        return $this->bbparser->getAsHtml();
    }

    /**
     * Becasue nagios simply store the newline as string in the database '\n' -.-
     *
     * @param string $string You want to replace the new line char as stirng
     *
     * @return string with HTML <br> for new line
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function nagiosNl2br($string)
    {
        return str_replace(['\n', '\r\n', '\r'], '<br>', $string);
    }
}