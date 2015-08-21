<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php';
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'validators' . DIRECTORY_SEPARATOR . 'UrlValidator.php';
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'validators' . DIRECTORY_SEPARATOR . 'CssColorValidator.php';

/**
 * Test cases for InputValidators.
 *
 * @author jbowens
 * @since May 2013
 */
class ValidatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests an invalid url directly on the UrlValidator.
     */
    public function testInvalidUrl()
    {
        $urlValidator = new \JBBCode\validators\UrlValidator();
        $this->assertFalse($urlValidator->validate('#yolo#swag'));
        $this->assertFalse($urlValidator->validate('giehtiehwtaw352353%3'));
    }

    /**
     * Tests a valid url directly on the UrlValidator.
     */
    public function testValidUrl()
    {
        $urlValidator = new \JBBCode\validators\UrlValidator();
        $this->assertTrue($urlValidator->validate('http://google.com'));
        $this->assertTrue($urlValidator->validate('http://jbbcode.com/docs'));
        $this->assertTrue($urlValidator->validate('https://www.maps.google.com'));
    }

    /**
     * Tests an invalid url as an option to a url bbcode.
     *
     * @depends testInvalidUrl
     */
    public function testInvalidOptionUrlBBCode()
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse('[url=javascript:alert("HACKED!");]click me[/url]');
        $this->assertEquals('[url=javascript:alert("HACKED!");]click me[/url]',
                $parser->getAsHtml());
    }

    /**
     * Tests an invalid url as the body to a url bbcode.
     *
     * @depends testInvalidUrl
     */
    public function testInvalidBodyUrlBBCode()
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse('[url]javascript:alert("HACKED!");[/url]');
        $this->assertEquals('[url]javascript:alert("HACKED!");[/url]', $parser->getAsHtml());
    }

    /**
     * Tests a valid url as the body to a url bbcode.
     *
     * @depends testValidUrl
     */
    public function testValidUrlBBCode()
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse('[url]http://jbbcode.com[/url]');
        $this->assertEquals('<a href="http://jbbcode.com">http://jbbcode.com</a>',
                $parser->getAsHtml());
    }

    /**
     * Tests valid english CSS color descriptions on the CssColorValidator.
     */
    public function testCssColorEnglish()
    {
        $colorValidator = new JBBCode\validators\CssColorValidator();
        $this->assertTrue($colorValidator->validate('red'));
        $this->assertTrue($colorValidator->validate('yellow'));
        $this->assertTrue($colorValidator->validate('LightGoldenRodYellow'));
    }

    /**
     * Tests valid hexadecimal CSS color values on the CssColorValidator.
     */
    public function testCssColorHex()
    {
        $colorValidator = new JBBCode\validators\CssColorValidator();
        $this->assertTrue($colorValidator->validate('#000'));
        $this->assertTrue($colorValidator->validate('#ff0000'));
        $this->assertTrue($colorValidator->validate('#aaaaaa'));
    }

    /**
     * Tests valid rgba CSS color values on the CssColorValidator.
     */
    public function testCssColorRgba()
    {
        $colorValidator = new JBBCode\validators\CssColorValidator();
        $this->assertTrue($colorValidator->validate('rgba(255, 0, 0, 0.5)'));
        $this->assertTrue($colorValidator->validate('rgba(50, 50, 50, 0.0)'));
    }

    /**
     * Tests invalid CSS color values on the CssColorValidator.
     */
    public function testInvalidCssColor()
    {
        $colorValidator = new JBBCode\validators\CssColorValidator();
        $this->assertFalse($colorValidator->validate('" onclick="javascript: alert(\"gotcha!\");'));
        $this->assertFalse($colorValidator->validate('"><marquee scrollamount="100'));
    }

    /**
     * Tests valid css colors in a color bbcode.
     *
     * @depends testCssColorEnglish
     * @depends testCssColorHex
     */
    public function testValidColorBBCode()
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse('[color=red]colorful text[/color]');
        $this->assertEquals('<span style="color: red">colorful text</span>',
                $parser->getAsHtml());
        $parser->parse('[color=#00ff00]green[/color]');
        $this->assertEquals('<span style="color: #00ff00">green</span>', $parser->getAsHtml());
    }

    /**
     * Tests invalid css colors in a color bbcode.
     *
     * @depends testInvalidCssColor
     */
    public function testInvalidColorBBCode()
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse('[color=" onclick="alert(\'hey ya!\');]click me[/color]');
        $this->assertEquals('[color=" onclick="alert(\'hey ya!\');]click me[/color]',
                $parser->getAsHtml());
    }

}
