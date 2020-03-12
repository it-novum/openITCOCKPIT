<?php
// Copyright (C) <2020>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, version 3 of the License.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//    If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//    under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//    License agreement and license key will be shipped with the order
//    confirmation.

namespace App\Test\UnitTests\ButtonGroupHelper;


use App\View\Helper\ButtonGroupHelper;
use App\View\Helper\ButtonGroupHelperInterface;
use PHPUnit\Framework\TestCase;

class ButtonGroupHelperTest extends TestCase {


    /**
     * @var ButtonGroupHelper
     */
    private $sut;

    public function setUp(): void {
        $this->sut = new ButtonGroupHelper('unit testing');
    }

    public function test_classCanBeInitialized() {
        $this->assertInstanceOf(ButtonGroupHelperInterface::class, $this->sut);
    }

    public function test_addIconButton() {
        $iconCssSelector = 'fas fa-cog';

        $expected = $this->getExpectedOpeningButtonGroupTag()
            . '    <button class="btn btn-default " data-original-title="" data-placement="bottom" rel="tooltip" data-container="body"><i class="fas fa-cog"></i></button>'
            . $this->getExpectedClosingButtonGroupTag();

        $this->sut->addIconButton($iconCssSelector);

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected, $actual);

    }

    public function test_addIconButton_withDataOriginalTitle() {
        $iconCssSelector = 'fas fa-cog';
        $dataOriginalTitle = 'test related title';

        $expected = $this->getExpectedOpeningButtonGroupTag()
            . '    <button class="btn btn-default " data-original-title="test related title" data-placement="bottom" rel="tooltip" data-container="body"><i class="fas fa-cog"></i></button>'
            . $this->getExpectedClosingButtonGroupTag();

        $this->sut->addIconButton($iconCssSelector, $dataOriginalTitle);

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected, $actual);

    }

    public function test_addButton() {
        $cssSelector = 'btn-danger';
        $innerHtml = 'test related unimportant text';

        $expected = $this->getExpectedOpeningButtonGroupTag()
            . '    <button class="btn btn-danger">test related unimportant text</button>'
            . $this->getExpectedClosingButtonGroupTag();

        $this->sut->addButton($innerHtml, $cssSelector);

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected, $actual);
    }

    public function test_addButton_withDataOriginalTitle() {
        $cssSelector = 'btn-danger';
        $innerHtml = 'test related unimportant text';
        $dataOriginalTitle = 'test related data original title';

        $expected = $this->getExpectedOpeningButtonGroupTag()
            . '    <button class="btn btn-danger" data-original-title="test related data original title" data-placement="bottom" rel="tooltip" data-container="body">test related unimportant text</button>'
            . $this->getExpectedClosingButtonGroupTag();

        $this->sut->addButtonWithTooltip($innerHtml, $cssSelector, $dataOriginalTitle);

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected, $actual);
    }

    public function test_addButtonWithTooltipAndDisplayConditional() {
        $expected =$this->getExpectedOpeningButtonGroupTag()
            . '    <button class="btn btn-secondary" data-original-title="test related title" data-placement="bottom" rel="tooltip" data-container="body" inserted="testRelatedText>test related innerHtml</button>'
            . $this->getExpectedClosingButtonGroupTag();

        $this->sut->addButtonWithTooltipAndDisplayConditional('test related innerHtml', 'btn-secondary', "test related title",'inserted="testRelatedText');

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected,$actual);
    }

    public function test_getView_returnsHtmlTemplate() {
        $expected = $this->getExpectedOpeningButtonGroupTag()
            . $this->getExpectedClosingButtonGroupTagWithoutLineBreak();

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected, $actual);
    }

    public function test_addButtonWithTogglingMenu() {
        $expected = $this->getExpectedOpeningButtonGroupTag()
            . '    <a href="javascript:void(0);" class="btn btn-default" data-toggle="dropdown" data-original-title="test related title"  data-placement="bottom" rel="tooltip"><i class="css selector"></i></a>' . PHP_EOL
            . '    <div>attachedMenu</div>'
            . $this->getExpectedClosingButtonGroupTag();

        $this->sut->addButtonWithTogglingMenu('css selector', 'test related title', '<div>attachedMenu</div>');

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected, $actual);

    }

    public function test_addButtonWithTooltipAndSRef() {
        $expected = $this->getExpectedOpeningButtonGroupTag()
            . '    <button class="btn btn-danger" data-original-title="test related data original title" data-placement="bottom" rel="tooltip" data-container="body" ui-sref="/an/url/reference">test related text</button>'
            . $this->getExpectedClosingButtonGroupTag();

        $this->sut->addButtonWithTooltipAndSRef('test related text', 'btn-danger', 'test related data original title', '/an/url/reference');

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected, $actual);
    }

    public function test_addIconButtonWithSRef() {
        $expected = $this->getExpectedOpeningButtonGroupTag()
            . '    <button class="btn btn-default" data-original-title="test related tooltip" data-placement="bottom" rel="tooltip" data-container="body" ui-sref="/an/url/reference/for/icons" customHtmlAttribute="unimportant value><i class="fas fa-question"></i></button>'
            . $this->getExpectedClosingButtonGroupTag();

        $this->sut->addIconButtonWithSRef('fas fa-question', 'test related tooltip', '/an/url/reference/for/icons', 'customHtmlAttribute="unimportant value');

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected, $actual);
    }

    public function test_addIconButtonWithHRef() {
        $expected = $this->getExpectedOpeningButtonGroupTag()
            . '    <button class="btn btn-default" data-original-title="test related tooltip" data-placement="bottom" rel="tooltip" data-container="body"><a href="/an/url/reference/for/icons"><i class="fas fa-question"></i></a></button>'
            . $this->getExpectedClosingButtonGroupTag();

        $this->sut->addIconButtonWithHRef('fas fa-question', 'test related tooltip', '/an/url/reference/for/icons');

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return string
     */
    private function getExpectedOpeningButtonGroupTag(): string {
        return '<div class="btn-group mr-2" role="group" aria-label="unit testing">' . PHP_EOL;
    }

    /**
     * @return string
     */
    private function getExpectedClosingButtonGroupTag(): string {
        return PHP_EOL . '</div>';
    }

    /**
     * @return string
     */
    private function getExpectedClosingButtonGroupTagWithoutLineBreak(): string {
        return '</div>';
    }
}
