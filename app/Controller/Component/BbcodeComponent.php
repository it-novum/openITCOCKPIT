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

use itnovum\openITCOCKPIT\Core\Views\BBCodeParser;

class BbcodeComponent extends Component {

    /**
     * @var BBCodeParser
     */
    private $parser;

    /**
     * @var JBBCode\Parser
     */
    private $bbparser;

    /**
     * initialize the BB Code Component
     *
     * @param Controller $controller The current CakePHP controller instance
     *
     * @set    object $bbparser to the current view for BbcodeHelper
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function initialize(Controller $controller) {
        $this->Controller = $controller;

        require_once OLD_APP . "Lib/jbbcode-1.2.0/Parser.php";
        $bbparser = new JBBCode\Parser();
        $bbparser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $builder = new JBBCode\CodeDefinitionBuilder('left', '<p align="left">{param}</p>');
        $bbparser->addCodeDefinition($builder->build());
        $builder = new JBBCode\CodeDefinitionBuilder('right', '<p align="right">{param}</p>');
        $bbparser->addCodeDefinition($builder->build());
        $builder = new JBBCode\CodeDefinitionBuilder('center', '<p align="center">{param}</p>');
        $bbparser->addCodeDefinition($builder->build());
        $builder = new JBBCode\CodeDefinitionBuilder('justify', '<p align="justify">{param}</p>');
        $bbparser->addCodeDefinition($builder->build());
        $builder = new JBBCode\CodeDefinitionBuilder('xx-small', '<span style="font-size: xx-small;">{param}</span>');
        $bbparser->addCodeDefinition($builder->build());
        $builder = new JBBCode\CodeDefinitionBuilder('x-small', '<span style="font-size: x-small;">{param}</span>');
        $bbparser->addCodeDefinition($builder->build());
        $builder = new JBBCode\CodeDefinitionBuilder('small', '<span style="font-size: small;">{param}</span>');
        $bbparser->addCodeDefinition($builder->build());
        $builder = new JBBCode\CodeDefinitionBuilder('xx-large', '<span style="font-size: xx-large;">{param}</span>');
        $bbparser->addCodeDefinition($builder->build());
        $builder = new JBBCode\CodeDefinitionBuilder('x-large', '<span style="font-size: x-large;">{param}</span>');
        $bbparser->addCodeDefinition($builder->build());
        $builder = new JBBCode\CodeDefinitionBuilder('large', '<span style="font-size: large;">{param}</span>');
        $bbparser->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('code', '<pre style="line-height: 12px;">{param}</pre>');
        $bbparser->addCodeDefinition($builder->build());
        $this->Controller->set('bbparser', $bbparser);
        $this->bbparser = $bbparser;
    }

    /**
     * Converts BB code to HTML
     *
     * @param string $bbcode The BB Code you want to convert to HTML
     * @param bool $nl2br If you want to replace \n with <br>
     *
     * @return string with HTML parts
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function asHtml($bbcode, $nl2br = true) {
        return $this->parser->asHtml($bbcode, $nl2br);
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
    public function nagiosNl2br($str) {
        return $this->parser->nagiosNl2br($str);
    }
}