<?php

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'Parser.php';

/**
 * Test cases for CodeDefinition nest limits. If an element is nested beyond
 * its CodeDefinition's nest limit, it should be removed from the parse tree.
 *
 * @author jbowens
 * @since May 2013
 */
class NestLimitTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests that when elements have no nest limits they may be
     * nested indefinitely.
     */
    public function testIndefiniteNesting()
    {
        $parser = new JBBCode\Parser();
        $parser->addBBCode('b', '<strong>{param}</strong>', false, true, -1);
        $parser->parse('[b][b][b][b][b][b][b][b]bold text[/b][/b][/b][/b][/b][/b][/b][/b]');
        $this->assertEquals('<strong><strong><strong><strong><strong><strong><strong><strong>' .
                'bold text' .
                '</strong></strong></strong></strong></strong></strong></strong></strong>',
                $parser->getAsHtml());
    }

    /**
     * Test over nesting.
     */
    public function testOverNesting()
    {
        $parser = new JBBCode\Parser();
        $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
        $parser->addBBCode('quote', '<blockquote>{param}</blockquote>', false, true, 2);
        $bbcode = '[quote][quote][quote]wut[/quote] huh?[/quote] i don\'t know[/quote]';
        $parser->parse($bbcode);
        $expectedBbcode = '[quote][quote] huh?[/quote] i don\'t know[/quote]';
        $expectedHtml = '<blockquote><blockquote> huh?</blockquote> i don\'t know</blockquote>';
        $this->assertEquals($expectedBbcode, $parser->getAsBBCode());
        $this->assertEquals($expectedHtml, $parser->getAsHtml());
    }

}
