<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php';

/**
 * Test cases for the code definition parameter that disallows parsing
 * of an element's content.
 *
 * @author jbowens
 */
class ParseContentTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests that when a bbcode is created with parseContent = false, 
     * its contents actually are not parsed.
     */
    public function testSimpleNoParsing()
    {

        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->addBBCode('verbatim', '{param}', false, false);

        $parser->parse('[verbatim]plain text[/verbatim]');
        $this->assertEquals('plain text', $parser->getAsHtml());

        $parser->parse('[verbatim][b]bold[/b][/verbatim]');
        $this->assertEquals('[b]bold[/b]', $parser->getAsHtml());

    }

    public function testNoParsingWithBufferText()
    {
        
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->addBBCode('verbatim', '{param}', false, false);

        $parser->parse('buffer text[verbatim]buffer text[b]bold[/b]buffer text[/verbatim]buffer text');
        $this->assertEquals('buffer textbuffer text[b]bold[/b]buffer textbuffer text', $parser->getAsHtml());
    }

    /**
     * Tests that when a tag is not closed within an unparseable tag,
     * the BBCode output does not automatically close that tag (because
     * the contents were not parsed).
     */
    public function testUnclosedTag()
    {
    
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->addBBCode('verbatim', '{param}', false, false);

        $parser->parse('[verbatim]i wonder [b]what will happen[/verbatim]');
        $this->assertEquals('i wonder [b]what will happen', $parser->getAsHtml());
        $this->assertEquals('[verbatim]i wonder [b]what will happen[/verbatim]', $parser->getAsBBCode());
    }

    /**
     * Tests that an unclosed tag with parseContent = false ends cleanly.
     */
    public function testUnclosedVerbatimTag()
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->addBBCode('verbatim', '{param}', false, false);

        $parser->parse('[verbatim]yo this [b]text should not be bold[/b]');
        $this->assertEquals('yo this [b]text should not be bold[/b]', $parser->getAsHtml());
    }

    /**
     * Tests a malformed closing tag for a verbatim block.
     */
    public function testMalformedVerbatimClosingTag()
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->addBBCode('verbatim', '{param}', false, false);
        $parser->parse('[verbatim]yo this [b]text should not be bold[/b][/verbatim');
        $this->assertEquals('yo this [b]text should not be bold[/b][/verbatim', $parser->getAsHtml());
    }

    /**
     * Tests an immediate end after a verbatim.
     */
    public function testVerbatimThenEof()
    {
        $parser = new JBBCode\Parser();
        $parser->addBBCode('verbatim', '{param}', false, false);
        $parser->parse('[verbatim]');
        $this->assertEquals('', $parser->getAsHtml());
    }

}
