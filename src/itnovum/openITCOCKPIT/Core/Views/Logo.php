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

    private $logoBasePath = '%s/img/logos/%s';
    private $logoBaseForAbsolutePath = '%simg/logos/%s';
    private $logoPdfPath = '%s/img/%s';

    private $customLogoName = 'logo_custom.png';
    private $customSmallLogoName = 'logo_small_custom.png';

    private $headerLogoName = 'logo_header.png';
    private $customHeaderLogoName = 'logo_custom_header.png';


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
    public function getLogoForHtml() {
        return sprintf($this->logoBasePath, '', $this->getLogoName());
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
    public function getSmallLogoForHtml() {
        return sprintf($this->logoBasePath, '', $this->getSmallLogoName());
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
        $file = sprintf($this->logoBaseForAbsolutePath, WWW_ROOT, $this->customLogoName);
        return file_exists($file);
    }

    /**
     * @return bool
     */
    public function isCustomSmallLogo() {
        $file = sprintf($this->logoBaseForAbsolutePath, WWW_ROOT, $this->customSmallLogoName);
        return file_exists($file);
    }

    /**
     * @return string
     */
    public function getLogoDiskPath() {
        return sprintf($this->logoBasePath, APP, $this->getLogoName());
    }

    /**
     * @return string
     */
    public function getSmallLogoDiskPath() {
        return sprintf($this->logoBasePath, WWW_ROOT, $this->getSmallLogoName());
    }

    /**
     * @return string
     */
    public function getDefaultLogoDiskPath() {
        return sprintf($this->logoBasePath, APP, $this->logoName);
    }

    /**
     * @return string
     */
    public function getDefaultSmallLogoDiskPath() {
        return sprintf($this->logoBasePath, APP, $this->smallLogoName);
    }

    /**
     * @return string
     */
    public function getCustomLogoDiskPath() {
        return sprintf($this->logoBaseForAbsolutePath, WWW_ROOT, $this->customLogoName);
    }

    /**
     * @return string
     */
    public function getCustomSmallLogoDiskPath() {
        return sprintf($this->logoBaseForAbsolutePath, WWW_ROOT, $this->customSmallLogoName);
    }

    /**
     * @return string
     */
    public function getPdfLogoName() {
        if ($this->isCustomPdfLogo()) {
            return $this->customLogoName;
        }
        return $this->logoName;
    }

    /**
     * @return string
     */
    public function getSmallPdfLogoName() {
        if ($this->isCustomSmallPdfLogo()) {
            return $this->customSmallLogoName;
        }
        return $this->smallLogoName;
    }

    /**
     * @return bool
     */
    public function isCustomPdfLogo() {
        $file = sprintf($this->logoPdfPath, WWW_ROOT, $this->customLogoName);
        return file_exists($file);
    }

    /**
     * @return bool
     */
    public function isCustomSmallPdfLogo() {
        $file = sprintf($this->logoPdfPath, WWW_ROOT, $this->customSmallLogoName);
        return file_exists($file);
    }

    /**
     * @return string
     */
    public function getLogoPdfForHtml() {
        return sprintf($this->logoPdfPath, '', $this->getPdfLogoName());
    }

    /**
     * @return string
     */
    public function getSmallLogoPdfForHtml() {
        return sprintf($this->logoPdfPath, '', $this->getSmallPdfLogoName());
    }

    /**
     * @return string
     */
    public function getLogoPdfPath() {
        return sprintf($this->logoPdfPath, WWW_ROOT, $this->getPdfLogoName());
    }

    /**
     * @return string
     */
    public function getCustomLogoPdfPath() {
        return sprintf($this->logoPdfPath, WWW_ROOT, $this->customLogoName);
    }

    /**
     * @return string
     */
    public function getSmallLogoPdfPath() {
        return sprintf($this->logoPdfPath, WWW_ROOT, $this->getSmallPdfLogoName());
    }

    /**
     * @return string
     */
    public function getCustomSmallLogoPdfPath() {
        return sprintf($this->logoPdfPath, WWW_ROOT, $this->customSmallLogoName);
    }

    /**
     * @return string
     */
    public function getHeaderLogoName() {
        if ($this->isCustomHeaderLogo()) {
            return $this->customHeaderLogoName;
        }
        return $this->headerLogoName;
    }

    /**
     * @return bool
     */
    public function isCustomHeaderLogo() {
        $file = sprintf($this->logoBasePath, WWW_ROOT, $this->customHeaderLogoName);
        return file_exists($file);
    }

    /**
     * @return string
     */
    public function getHeaderLogoForHtml() {
        return sprintf($this->logoBasePath, '', $this->getHeaderLogoName());
    }
}
