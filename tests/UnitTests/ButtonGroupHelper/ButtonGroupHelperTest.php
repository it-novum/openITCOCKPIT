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
        $templateData = [];
        $templateData['GroupAriaLabel'] = 'unit testing';
        $templateData['GroupElement']['first']['class'] = 'test-added-css-class';
        $templateData['GroupElement']['first']['innerHTML'] = 'unimportant text';

        $this->sut = new ButtonGroupHelper($templateData);
    }

    public function test_classCanBeInitialized() {
        $this->assertInstanceOf(ButtonGroupHelperInterface::class, $this->sut);
    }

    public function test_addIconButton() {
        $iconCssSelector = 'fas fa-cog';

        $expected = '<div class="btn-group btn-group-xs mr-2" role="group" aria-label="unit testing">'. PHP_EOL .
            '    <button class="btn btn-default" data-original-title="" data-placement="bottom" rel="tooltip" data-container="body"><i class="fas fa-cog"></i></button>' . PHP_EOL .
            '    <button
        class="btn test-added-css-class"
    >unimportant text</button>
</div>';

        $this->sut->addIconButton($iconCssSelector);

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected,$actual);

    }

    public function test_addIconButton_withDataOriginalTitle() {
        $iconCssSelector = 'fas fa-cog';
        $dataOriginalTitle = 'test related title';

        $expected = '<div class="btn-group btn-group-xs mr-2" role="group" aria-label="unit testing">'. PHP_EOL .
            '    <button class="btn btn-default" data-original-title="test related title" data-placement="bottom" rel="tooltip" data-container="body"><i class="fas fa-cog"></i></button>' . PHP_EOL .
            '    <button
        class="btn test-added-css-class"
    >unimportant text</button>
</div>';

        $this->sut->addIconButton($iconCssSelector,$dataOriginalTitle);

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected,$actual);

    }

    public function test_addButton() {
        $cssSelector = 'btn-danger';
        $innerHtml = 'test related unimportant text';

        $expected = '<div class="btn-group btn-group-xs mr-2" role="group" aria-label="unit testing">'. PHP_EOL .
            '    <button class="btn btn-danger">test related unimportant text</button>' .PHP_EOL .
            '    <button
        class="btn test-added-css-class"
    >unimportant text</button>
</div>';

        $this->sut->addButton($innerHtml, $cssSelector);

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected,$actual);
    }

    public function test_addButton_withDataOriginalTitle() {
        $cssSelector = 'btn-danger';
        $innerHtml = 'test related unimportant text';
        $dataOriginalTitle = 'test related data original title';

        $expected = '<div class="btn-group btn-group-xs mr-2" role="group" aria-label="unit testing">'. PHP_EOL .
            '    <button class="btn btn-danger" data-original-title="test related data original title" data-placement="bottom" rel="tooltip" data-container="body">test related unimportant text</button>' .PHP_EOL .
            '    <button
        class="btn test-added-css-class"
    >unimportant text</button>
</div>';

        $this->sut->addButtonWithData($innerHtml, $cssSelector, $dataOriginalTitle);

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected,$actual);
    }


    public function test_getView_returnsHtmlTemplate() {
        $expected = '<div class="btn-group btn-group-xs mr-2" role="group" aria-label="unit testing">'. PHP_EOL .
'    <button
        class="btn test-added-css-class"
    >unimportant text</button>
</div>';

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected,$actual);
    }

    public function test_addButtonWithTogglingMenu() {
        $expected = '<div class="btn-group btn-group-xs mr-2" role="group" aria-label="unit testing">
    <a href="javascript:void(0);" class="btn btn-default" data-toggle="dropdown" data-original-title="test related title"  data-placement="bottom" rel="tooltip"><i class="css selector"></i></a><div>attachedMenu</div>
    <button
        class="btn test-added-css-class"
    >unimportant text</button>
</div>';
        $this->sut->addButtonWithTogglingMenu('css selector','test related title', '<div>attachedMenu</div>');

        $actual = $this->sut->getHtml();

        $this->assertEquals($expected,$actual);

    }
}
