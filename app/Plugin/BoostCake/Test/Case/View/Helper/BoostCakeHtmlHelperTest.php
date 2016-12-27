<?php
App::uses('BoostCakeHtmlHelper', 'BoostCake.View/Helper');
App::uses('View', 'View');

class BoostCakeHtmlHelperTest extends CakeTestCase
{

    public function setUp()
    {
        parent::setUp();
        $View = new View();
        $this->Html = new BoostCakeHtmlHelper($View);
    }

    public function tearDown()
    {
        unset($this->Html);
    }

    public function testUseTag()
    {
        $result = $this->Html->useTag(
            'radio', 'one', 'two', ['three' => 'four'], '<label for="one">label</label>'
        );
        $this->assertTags($result, [
            'label' => ['class' => 'radio', 'for' => 'one'],
            'input' => ['type' => 'radio', 'name' => 'one', 'id' => 'two', 'three' => 'four'],
            ' label',
            '/label',
        ]);

        $result = $this->Html->useTag(
            'radio', 'one', 'two', ['class' => 'radio-inline', 'three' => 'four'], '<label for="one">label</label>'
        );
        $this->assertTags($result, [
            'label' => ['class' => 'radio-inline', 'for' => 'one'],
            'input' => ['type' => 'radio', 'name' => 'one', 'id' => 'two', 'three' => 'four'],
            ' label',
            '/label',
        ]);
    }

    public function testImage()
    {
        $result = $this->Html->image('', ['data-src' => 'holder.js/24x24']);
        $this->assertTags($result, [
            'img' => ['src' => '/', 'data-src' => 'holder.js/24x24', 'alt' => ''],
        ]);

        $result = $this->Html->image('some.jpg', ['data-src' => 'holder.js/24x24']);
        $this->assertTags($result, [
            'img' => ['src' => '/img/some.jpg', 'alt' => ''],
        ]);
    }

}
