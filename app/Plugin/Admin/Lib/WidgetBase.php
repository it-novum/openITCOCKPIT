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


abstract class WidgetBase
{
    const WIDGET_DOWNTIMEHOSTS = 1;
    const WIDGET_PIECHARTHOSTS = 2;
    const WIDGET_PARENTOUTAGES = 3;
    const WIDGET_DOWNTIMESERVICES = 4;
    const WIDGET_PIECHARTSERVICES = 5;
    const WIDGET_TRAFFICLIGHT = 6;
    const WIDGET_TACHO = 7;
    const WIDGET_WELCOME = 8;
    const WIDGET_SERVICESSTATUSLIST = 9;
    const WIDGET_HOSTSSTATUSLIST = 10;

    /** @var string */
    protected $id;
    /** @var string */
    protected $title;
    /** @var string */
    protected $color;
    /** @var int */
    protected $typeId;

    protected $iconname = '';
    protected $createName = '';
    protected $createLink = '';
    protected $bodyClasses = '';
    protected $bodyStyles = '';
    protected $viewName = '';
    protected $iconnameBootstrap = '';

    private $templateVariables = [];
    private $frontedJson = [];
    /** @var Model[] */
    private $models;

    /**
     * @param int     $id
     * @param string  $title
     * @param string  $color
     * @param int     $typeId
     * @param Model[] $models
     */
    public function __construct($id, $title, $color, $typeId, array $models)
    {
        $this->id = $id;
        $this->title = $title;
        $this->color = $color;
        $this->typeId = $typeId;
        $this->models = $models;
    }

    /**
     * @param array $templateVariables
     */
    public function setTemplateVariables(array $templateVariables)
    {
        $this->templateVariables = $templateVariables;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @throws Exception
     */
    public function setFrontedJson($key, $value)
    {
        if (isset($this->frontedJson[$key])) {
            throw new Exception('Key "'.$key.'" is already set. This is probably a mistake and not intended."');
        }
        $this->frontedJson[$key] = $value;
    }

    /**
     * Compiles and returns the variables for the template. It is supposed to use `setTemplateVariables` and
     * `setFrontedJson` within this method to set the result.
     * @return void
     */
    public abstract function compileTemplateData();

    public function getTemplateVariables()
    {
        $baseVariables = [
            'id'                => $this->id,
            'title'             => $this->title,
            'color'             => $this->color,
            'typeId'            => $this->typeId,
            'iconname'          => $this->iconname,
            'createName'        => $this->createName,
            'createLink'        => $this->createLink,
            'bodyClasses'       => $this->bodyClasses,
            'bodyStyles'        => $this->bodyStyles,
            'viewName'          => $this->viewName,
            'iconnameBootstrap' => $this->iconnameBootstrap,
        ];

        return array_merge($baseVariables, $this->templateVariables);
    }

    /**
     * Returns an iniitalized model object.
     *
     * @param $name
     *
     * @return null|Model
     */
    public function __get($name)
    {
        if (isset($this->models[$name])) {
            return $this->models[$name];
        }

        return null;
    }

    public function getFrontendJson()
    {
        return $this->frontedJson;
    }
}
