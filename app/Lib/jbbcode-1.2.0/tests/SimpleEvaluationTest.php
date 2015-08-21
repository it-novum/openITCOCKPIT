<?php

require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php');

class SimpleEvaluationTest extends PHPUnit_Framework_TestCase
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


    public function testEmptyString()
    {
        $this->assertProduces('', '');
    }

    public function testOneTag()
    {
        $this->assertProduces('[b]this is bold[/b]', '<strong>this is bold</strong>');
    }

    public function testOneTagWithSurroundingText()
    {
        $this->assertProduces('buffer text [b]this is bold[/b] buffer text',
                              'buffer text <strong>this is bold</strong> buffer text');
    }

    public function testMultipleTags()
    {
        $bbcode = <<<EOD
this is some text with [b]bold tags[/b] and [i]italics[/i] and
things like [u]that[/u].
EOD;
        $html = <<<EOD
this is some text with <strong>bold tags</strong> and <em>italics</em> and
things like <u>that</u>.
EOD;
        $this->assertProduces($bbcode, $html);
    }

    public function testCodeOptions()
    {
        $code = 'This contains a [url=http://jbbcode.com]url[/url] which uses an option.';
        $html = 'This contains a <a href="http://jbbcode.com">url</a> which uses an option.';
        $this->assertProduces($code, $html);
    }

    /**
     * @depends testCodeOptions
     */
    public function testOmittedOption()
    {
        $code = 'This doesn\'t use the url option [url]http://jbbcode.com[/url].';
        $html = 'This doesn\'t use the url option <a href="http://jbbcode.com">http://jbbcode.com</a>.';
        $this->assertProduces($code, $html);
    }

    public function testUnclosedTag()
    {
        $code = 'hello [b]world';
        $html = 'hello <strong>world</strong>';
        $this->assertProduces($code, $html);
    }

    public function testNestingTags()
    {
        $code = '[url=http://jbbcode.com][b]hello [u]world[/u][/b][/url]';
        $html = '<a href="http://jbbcode.com"><strong>hello <u>world</u></strong></a>';
        $this->assertProduces($code, $html);
    }

}
