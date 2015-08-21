<?php

require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php');

/**
 * Test cases testing the functionality of parsing bbcode and
 * retrieving a bbcode well-formed bbcode representation.
 *
 * @author jbowens
 */
class BBCodeToBBCodeTest extends PHPUnit_Framework_TestCase
{

    /**
     * A utility method for these tests that will evaluate its arguments as bbcode with
     * a fresh parser loaded with only the default bbcodes. It returns the
     * bbcode output, which in most cases should be in the input itself.
     */
    private function defaultBBCodeParse($bbcode)
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->parse($bbcode);
        return $parser->getAsBBCode();
    }

    /**
     * Asserts that the given bbcode matches the given text when
     * the bbcode is run through defaultBBCodeParse
     */
    private function assertBBCodeOutput($bbcode, $text)
    {
        $this->assertEquals($this->defaultBBCodeParse($bbcode), $text);
    }

    public function testEmptyString()
    {
        $this->assertBBCodeOutput('', '');
    }

    public function testOneTag()
    {
        $this->assertBBCodeOutput('[b]this is bold[/b]', '[b]this is bold[/b]');
    }

    public function testOneTagWithSurroundingText()
    {
        $this->assertBBCodeOutput('buffer text [b]this is bold[/b] buffer text',
                                  'buffer text [b]this is bold[/b] buffer text');
    }

    public function testMultipleTags()
    {
        $bbcode = 'this is some text with [b]bold tags[/b] and [i]italics[/i] and ' .
                  'things like [u]that[/u].';
        $bbcodeOutput = 'this is some text with [b]bold tags[/b] and [i]italics[/i] and ' .
                        'things like [u]that[/u].';
        $this->assertBBCodeOutput($bbcode, $bbcodeOutput);
    }

    public function testCodeOptions()
    {
        $code = 'This contains a [url=http://jbbcode.com]url[/url] which uses an option.';
        $codeOutput = 'This contains a [url=http://jbbcode.com]url[/url] which uses an option.';
        $this->assertBBCodeOutput($code, $codeOutput);
    }

    /**
     * @depends testCodeOptions
     */
    public function testOmittedOption()
    {
        $code = 'This doesn\'t use the url option [url]http://jbbcode.com[/url].';
        $codeOutput = 'This doesn\'t use the url option [url]http://jbbcode.com[/url].';
        $this->assertBBCodeOutput($code, $codeOutput);
    }

    public function testUnclosedTags()
    {
        $code = '[b]bold';
        $codeOutput = '[b]bold[/b]';
        $this->assertBBCodeOutput($code, $codeOutput);
    }

}
