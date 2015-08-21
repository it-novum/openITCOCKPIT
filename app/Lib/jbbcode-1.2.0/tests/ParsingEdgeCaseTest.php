<?php

require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php');

/**
 * A series of test cases for various potential parsing edge cases. This
 * includes a lot of tests using brackets for things besides genuine tag
 * names.
 *
 * @author jbowens
 *
 */
class ParsingEdgeCaseTest extends PHPUnit_Framework_TestCase
{

    /**
     * A utility method for these tests that will evaluate
     * its arguments as bbcode with a fresh parser loaded
     * with only the default bbcodes. It returns the
     * html output.
     */
    private function defaultParse($bbcode)
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse($bbcode);
        return $parser->getAsHtml();
    }

    /**
     * Asserts that the given bbcode matches the given html when
     * the bbcode is run through defaultParse.
     */
    private function assertProduces($bbcode, $html)
    {
        $this->assertEquals($html, $this->defaultParse($bbcode));
    }

    /**
     * Tests attempting to use a code that doesn't exist.
     */
    public function testNonexistentCodeMalformed()
    {
        $this->assertProduces('[wat]', '[wat]');
    }

    /**
     * Tests attempting to use a code that doesn't exist, but this
     * time in a well-formed fashion.
     *
     * @depends testNonexistentCodeMalformed
     */
    public function testNonexistentCodeWellformed()
    {
        $this->assertProduces('[wat]something[/wat]', '[wat]something[/wat]');
    }

    /**
     * Tests a whole bunch of meaningless left brackets.
     */
    public function testAllLeftBrackets()
    {
        $this->assertProduces('[[[[[[[[', '[[[[[[[[');
    }

    /**
     * Tests a whole bunch of meaningless right brackets.
     */
    public function testAllRightBrackets()
    {
        $this->assertProduces(']]]]]', ']]]]]');
    }

    /**
     * Intermixes well-formed, meaningful tags with meaningless brackets.
     */
    public function testRandomBracketsInWellformedCode()
    {
        $this->assertProduces('[b][[][[i]heh[/i][/b]',
                              '<strong>[[][<em>heh</em></strong>');
    }

    /**
     * Tests an unclosed tag within a closed tag.
     */
    public function testUnclosedWithinClosed()
    {
        $this->assertProduces('[url=http://jbbcode.com][b]oh yeah[/url]',
                              '<a href="http://jbbcode.com"><strong>oh yeah</strong></a>');
    }

    /**
     * Tests half completed opening tag.
     */
    public function testHalfOpenTag()
    {
        $this->assertProduces('[b', '[b');
        $this->assertProduces('wut [url=http://jbbcode.com',
                              'wut [url=http://jbbcode.com');
    }

    /**
     * Tests half completed closing tag.
     */
    public function testHalfClosingTag()
    {
        $this->assertProduces('[b]this should be bold[/b',
                              '<strong>this should be bold[/b</strong>');
    }

    /**
     * Tests lots of left brackets before the actual tag. For example:
     * [[[[[[[[b]bold![/b]
     */
    public function testLeftBracketsThenTag()
    {
        $this->assertProduces('[[[[[b]bold![/b]',
                              '[[[[<strong>bold!</strong>');
    }
}
