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

class BrowserMiscHelper extends AppHelper
{

    /**
     * Returns the Fontawesome class based on the Containertype ID
     *
     * @param  Integer $containertype_id The Containertype id
     *
     * @return string $faClass           the Fontawesome class with the icon
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @since  3.0.2
     */
    public function containertypeIcon($containertype_id = null)
    {
        $faClass = '';
        switch ($containertype_id) {
            case 1:
                $faClass = '';
                break; // ?
            case 2:
                $faClass = 'fa-home';
                break;
            case 3:
                $faClass = 'fa-location-arrow';
                break;
            case 4:
                $faClass = 'fa-cloud';
                break; // ?
            case 5:
                $faClass = 'fa-link';
                break;
            case 6:
                $faClass = 'fa-users';
            case 7:
                $faClass = 'fa-sitemap';
                break;
            case 8:
                $faClass = 'fa-cogs';
                break;
            case 9:
                $faClass = 'fa-pencil-square-o';
                break;
            default:
                $faClass = 'fa-question';
                break;
        }

        return $faClass;
    }

    /**
     * return the Link for the controller action based on the Containertype ID
     *
     * @param  Integer $containertype_id The Containertype id
     *
     * @return string  $link             the link to the new browser page
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @since  3.0.2
     */
    public function browserLink($containertype_id = null)
    {
        $link = '';
        switch ($containertype_id) {
            case 1:
                break;
            case 2:
                $link = 'tenantBrowser';
                break;
            case 3:
                $link = 'locationBrowser';
                break;
            //case 4:	$link = 'devicegroupBrowser'; break;
            case 5:
                $link = 'nodeBrowser';
                break;
            case 7:
                $link = 'hostgroupBrowser';
                break;
            case 8:
                $link = 'servicegroupBrowser';
                break;
            default:
                $link = 'index';
                break;
        }

        return $link;
    }
}