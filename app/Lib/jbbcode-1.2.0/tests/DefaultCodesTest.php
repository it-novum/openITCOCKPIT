<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php';

/**
 * Test cases for the default bbcode set.
 *
 * @author jbowens
 * @since May 2013
 */
class DefaultCodesTest extends PHPUnit_Framework_TestCase
{

    /**
     * Asserts that the given bbcode string produces the given html string
     * when parsed with the default bbcodes.
     */
    public function assertProduces($bbcode, $html)
    {
        $parser = new \JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse($bbcode);
        $this->assertEquals($html, $parser->getAsHtml());
    }

    /**
     * Tests the [b] bbcode.
     */
    public function testBold()
    {
        $this->assertProduces('[b]this should be bold[/b]', '<strong>this should be bold</strong>');
    }

    /**
     * Tests the [color] bbcode.
     */
    public function testColor()
    {
        $this->assertProduces('[color=red]red[/color]', '<span style="color: red">red</span>');
    }

    /**
     * Tests the example from the documentation.
     */
    public function testExample()
    {
        $text = "The default codes include: [b]bold[/b], [i]italics[/i], [u]underlining[/u], ";
        $text .= "[url=http://jbbcode.com]links[/url], [color=red]color![/color] and more.";
        $html = 'The default codes include: <strong>bold</strong>, <em>italics</em>, <u>underlining</u>, ';
        $html .= '<a href="http://jbbcode.com">links</a>, <span style="color: red">color!</span> and more.';
        $this->assertProduces($text, $html);
    }

}
