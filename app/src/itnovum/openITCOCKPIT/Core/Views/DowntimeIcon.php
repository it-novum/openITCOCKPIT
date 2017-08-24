<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Core\Views;


class DowntimeIcon {

    /**
     * @var Downtime
     */
    private $Downtime;

    /**
     * DowntimeIcon constructor.
     * @param Downtime $Downtime
     */
    public function __construct(Downtime $Downtime) {
        $this->Downtime = $Downtime;
    }

    /**
     * @return string
     */
    public function getLabel(){
        if($this->Downtime->isRunning()){
            return __('Downtime currently running');
        }

        if($this->Downtime->isExpired()){
            return __('Downtime is expired');
        }

        if($this->Downtime->wasCancelled() && $this->Downtime->wasStarted()){
            return __('Downtime was cancelled');
        }

        return __('Downtime not started yet');
    }

    /**
     * @return string
     */
    public function getIcon(){
        $basicIcon = '<i title="%s" class="fa fa-power-off fa-lg %s"></i>';
        if($this->Downtime->isRunning()){
            return sprintf($basicIcon, $this->getLabel(), 'txt-color-green');
        }
        return sprintf($basicIcon, $this->getLabel(), 'txt-color-red');
    }

}
