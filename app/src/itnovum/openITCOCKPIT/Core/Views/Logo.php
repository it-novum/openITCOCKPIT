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


class Logo {
    private $logoName = 'logo.png';
    private $smallLogoName = 'logo_small.png';

    private $logoBasePath = '%swebroot/img/%s';

    private $customLogoName = 'logo_custom.png';
    private $customSmallLogoName = 'logo_small_custom.png';


    /**
     * @return string
     */
    public function getLogoName() {
        if ($this->isCustomLogo()) {
            return $this->customLogoName;
        }

        return $this->logoName;
    }

    /**
     * @return string
     */
    public function getSmallLogoName() {
        if ($this->isCustomSmallLogo()) {
            return $this->customSmallLogoName;
        }

        return $this->smallLogoName;
    }

    /**
     * @return string
     */
    public function getLogoForHtmlHelper() {
        return $this->getLogoName();
    }

    /**
     * @return string
     */
    public function getSmallLogoForHtmlHelper() {
        return $this->getSmallLogoName();
    }

    /**
     * @return bool
     */
    public function isCustomLogo() {
        $file = sprintf($this->logoBasePath, OLD_APP, $this->customLogoName);
        return file_exists($file);
    }

    /**
     * @return bool
     */
    public function isCustomSmallLogo() {
        $file = sprintf($this->logoBasePath, OLD_APP, $this->customSmallLogoName);
        return file_exists($file);
    }

    /**
     * @return string
     */
    public function getLogoDiskPath() {
        return sprintf($this->logoBasePath, OLD_APP, $this->getLogoName());
    }

    /**
     * @return string
     */
    public function getSmallLogoDiskPath() {
        return sprintf($this->logoBasePath, OLD_APP, $this->getSmallLogoName());
    }

    /**
     * @return string
     */
    public function getDefaultLogoDiskPath() {
        return sprintf($this->logoBasePath, OLD_APP, $this->logoName);
    }

    /**
     * @return string
     */
    public function getDefaultSmallLogoDiskPath() {
        return sprintf($this->logoBasePath, OLD_APP, $this->smallLogoName);
    }

    /**
     * @return string
     */
    public function getCustomLogoDiskPath() {
        return sprintf($this->logoBasePath, OLD_APP, $this->customLogoName);
    }

    /**
     * @return string
     */
    public function getCustomSmallLogoDiskPath() {
        return sprintf($this->logoBasePath, OLD_APP, $this->customSmallLogoName);
    }


    /**
     * @return string
     */
    public function getLogoPdfPath() {
        return sprintf('%s/img/%s', WWW_ROOT, $this->getLogoName());
    }

    /**
     * @return string
     */
    public function getSmallLogoPdfPath() {
        return sprintf('%s/img/%s', WWW_ROOT, $this->getSmallLogoName());
    }

}
