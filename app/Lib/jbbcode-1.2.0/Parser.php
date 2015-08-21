<?php

namespace JBBCode;

require_once 'ElementNode.php';
require_once 'TextNode.php';
require_once 'DefaultCodeDefinitionSet.php';
require_once 'DocumentElement.php';
require_once 'CodeDefinition.php';
require_once 'CodeDefinitionBuilder.php';
require_once 'CodeDefinitionSet.php';
require_once 'NodeVisitor.php';
require_once 'Tokenizer.php';
require_once 'visitors/NestLimitVisitor.php';
require_once 'InputValidator.php';

use JBBCode\CodeDefinition;

/**
 * BBCodeParser is the main parser class that constructs and stores the parse tree. Through this class
 * new bbcode definitions can be added, and documents may be parsed and converted to html/bbcode/plaintext, etc.
 *
 * @author jbowens
 */
class Parser
{
    /* The root element of the parse tree */
    protected $treeRoot;

    /* The list of bbcodes to be used by the parser. */
    protected $bbcodes;

    /* The next node id to use. This is used while parsing. */
    protected $nextNodeid;

    /**
     * Constructs an instance of the BBCode parser
     */
    public function __construct()
    {
        $this->reset();
        $this->bbcodes = array();
    }

    /**
     * Adds a simple (text-replacement only) bbcode definition
     *
     * @param string  $tagName      the tag name of the code (for example the b in [b])
     * @param string  $replace      the html to use, with {param} and optionally {option} for replacements
     * @param boolean $useOption    whether or not this bbcode uses the secondary {option} replacement
     * @param boolean $parseContent whether or not to parse the content within these elements
     * @param integer $nestLimit    an optional limit of the number of elements of this kind that can be nested within
     *                              each other before the parser stops parsing them.
     * @param InputValidator $optionValidator   the validator to run {option} through
     * @param BodyValidator  $bodyValidator     the validator to run {param} through (only used if $parseContent == false)
     */
    public function addBBCode($tagName, $replace, $useOption = false, $parseContent = true, $nestLimit = -1,
            InputValidator $optionValidator = null, InputValidator $bodyValidator = null)
    {
        $builder = new CodeDefinitionBuilder($tagName, $replace);

        $builder->setUseOption($useOption);
        $builder->setParseContent($parseContent);
        $builder->setNestLimit($nestLimit);

        if ($optionValidator) {
            $builder->setOptionValidator($optionValidator);
        }

        if ($bodyValidator) {
            $builder->setBodyValidator($bodyValidator);
        }

        $this->addCodeDefinition($builder->build());
    }

    /**
     * Adds a complex bbcode definition. You may subclass the CodeDefinition class, instantiate a definition of your new
     * class and add it to the parser through this method.
     *
     * @param CodeDefinition $definition the bbcode definition to add
     */
    public function addCodeDefinition( CodeDefinition $definition )
    {
        array_push($this->bbcodes, $definition);
    }

    /**
     * Adds a set of CodeDefinitions.
     *
     * @param CodeDefinitionSet $set  the set of definitions to add
     */
    public function addCodeDefinitionSet(CodeDefinitionSet $set) {
        foreach ($set->getCodeDefinitions() as $def) {
            $this->addCodeDefinition($def);
        }
    }

    /**
     * Returns the entire parse tree as text. Only {param} content is returned. BBCode markup will be ignored.
     *
     * @return a text representation of the parse tree
     */
    public function getAsText()
    {
        return $this->treeRoot->getAsText();
    }

    /**
     * Returns the entire parse tree as bbcode. This will be identical to the inputted string, except unclosed tags
     * will be closed.
     *
     * @return a bbcode representation of the parse tree
     */
    public function getAsBBCode()
    {
        return $this->treeRoot->getAsBBCode();
    }

    /**
     * Returns the entire parse tree as HTML. All BBCode replacements will be made. This is generally the method
     * you will want to use to retrieve the parsed bbcode.
     *
     * @return a parsed html string
     */
    public function getAsHTML()
    {
        return $this->treeRoot->getAsHTML();
    }

    /**
     * Accepts the given NodeVisitor at the root.
     *
     * @param nodeVisitor  a NodeVisitor
     */
    public function accept(NodeVisitor $nodeVisitor)
    {
        $this->treeRoot->accept($nodeVisitor);
    }
    /**
     * Constructs the parse tree from a string of bbcode markup.
     *
     * @param string $str the bbcode markup to parse
     */
    public function parse( $str )
    {
        /* Set the tree root back to a fresh DocumentElement. */
        $this->reset();

        $parent = $this->treeRoot;
        $tokenizer = new Tokenizer($str);

        while ($tokenizer->hasNext()) {
            $parent = $this->parseStartState($parent, $tokenizer);
            if ($parent->getCodeDefinition() && false === 
                    $parent->getCodeDefinition()->parseContent()) {
                /* We're inside an element that does not allow its contents to be parseable. */
                $this->parseAsTextUntilClose($parent, $tokenizer);
                $parent = $parent->getParent();
            }
        }

        /* We parsed ignoring nest limits. Do a O(n) traversal to remove any elements that
         * are nested beywond their CodeDefinition's nest limit. */
        $this->removeOverNestedElements();
    }

    /**
     * Removes any elements that are nested beyond their nest limit from the parse tree. This
     * method is now deprecated. In a future release its access privileges will be made 
     * protected.
     *
     * @deprecated
     */
    public function removeOverNestedElements()
    {
        $nestLimitVisitor = new \JBBCode\visitors\NestLimitVisitor();
        $this->accept($nestLimitVisitor);
    }

    /**
     * Removes the old parse tree if one exists.
     */
    protected function reset()
    {
        // remove any old tree information
        $this->treeRoot = new DocumentElement();
        /* The document element is created with nodeid 0. */
        $this->nextNodeid = 1;
    }

    /**
     * Determines whether a bbcode exists based on its tag name and whether or not it uses an option
     *
     * @param string  $tagName    the bbcode tag name to check
     * @param boolean $usesOption whether or not the bbcode accepts an option
     *
     * @return true if the code exists, false otherwise
     */
    public function codeExists( $tagName, $usesOption = false )
    {
        foreach ($this->bbcodes as $code) {
            if (strtolower($tagName) == $code->getTagName() && $usesOption == $code->usesOption()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the CodeDefinition of a bbcode with the matching tag name and usesOption parameter
     *
     * @param string  $tagName    the tag name of the bbcode being searched for
     * @param boolean $usesOption whether or not the bbcode accepts an option
     *
     * @return CodeDefinition if the bbcode exists, null otherwise
     */
    public function getCode( $tagName, $usesOption = false )
    {
        foreach ($this->bbcodes as $code) {
            if (strtolower($tagName) == $code->getTagName() && $code->usesOption() == $usesOption) {
                return $code;
            }
        }

        return null;
    }

    /**
     * Adds a set of default, standard bbcode definitions commonly used across the web.
     *
     * This method is now deprecated. Please use DefaultCodeDefinitionSet and 
     * addCodeDefinitionSet() instead.
     *
     * @deprecated
     */
    public function loadDefaultCodes()
    {
        $defaultSet = new DefaultCodeDefinitionSet();
        $this->addCodeDefinitionSet($defaultSet);
    }

    /**
     * Creates a new text node with the given parent and text string.
     *
     * @param $parent  the parent of the text node
     * @param $string  the text of the text node
     *
     * @return the newly created TextNode
     */
    protected function createTextNode(ElementNode $parent, $string)
    {
        $textNode = new TextNode($string);
        $textNode->setNodeId(++$this->nextNodeid);
        $parent->addChild($textNode);
        return $textNode;
    }

    /**
     * jBBCode parsing logic is loosely modelled after a FSM. While not every function maps
     * to a unique DFSM state, each function handles the logic of one or more FSM states.
     * This function handles the beginning parse state when we're not currently in a tag
     * name.
     *
     * @param $parent  the current parent node we're under
     * @param $tokenizer  the tokenizer we're using
     *
     * @return the new parent we should use for the next iteration.
     */
    protected function parseStartState(ElementNode $parent, Tokenizer $tokenizer)
    {
        $next = $tokenizer->next();

        if ('[' == $next) {
            return $this->parseTagOpen($parent, $tokenizer);
        } else {
            $this->createTextNode($parent, $next);
            /* Drop back into the main parse loop which will call this
             * same method again. */
            return $parent;
        }
    }

    /**
     * This function handles parsing the beginnings of an open tag. When we see a [
     * at an appropriate time, this function is entered. 
     *
     * @param $parent  the current parent node
     * @param $tokenizer  the tokenizer we're using
     *
     * @return the new parent node
     */
    protected function parseTagOpen(ElementNode $parent, Tokenizer $tokenizer)
    {

        if (!$tokenizer->hasNext()) {
            /* The [ that sent us to this state was just a trailing [, not the
             * opening for a new tag. Treat it as such. */
            $this->createTextNode($parent, '[');
            return $parent;
        }

        $next = $tokenizer->next();

        /* This while loop could be replaced by a recursive call to this same method,
         * which would likely be a lot clearer but I decided to use a while loop to
         * prevent stack overflow with a string like [[[[[[[[[...[[[.
         */
        while ('[' == $next) {
            /* The previous [ was just a random bracket that should be treated as text.
             * Continue until we get a non open bracket. */
            $this->createTextNode($parent, '[');
            if (!$tokenizer->hasNext()) {
                $this->createTextNode($parent, '[');
                return $parent;
            }
            $next = $tokenizer->next();
        }

        /* At this point $next is either ']' or plain text. */
        if (']' == $next) {
            $this->createTextNode($parent, '[');
            $this->createTextNode($parent, ']');
            return $parent;
        } else {
            /* $next is plain text... likely a tag name. */
            return $this->parseTag($parent, $tokenizer, $next);
        }
    }

    /**
     * This is the next step in parsing a tag. It's possible for it to still be invalid at this
     * point but many of the basic invalid tag name conditions have already been handled.
     *
     * @param $parent  the current parent element
     * @param $tokenizer  the tokenizer we're using
     * @param $tagContent  the text between the [ and the ], assuming there is actually a ]
     *
     * @return the new parent element
     */
    protected function parseTag(ElementNode $parent, Tokenizer $tokenizer, $tagContent)
    {

        $next;
        if (!$tokenizer->hasNext() || ($next = $tokenizer->next()) != ']') {
            /* This is a malformed tag. Both the previous [ and the tagContent
             * is really just plain text. */
            $this->createTextNode($parent, '[');
            $this->createTextNode($parent, $tagContent);
            return $parent;
        }

        /* This is a well-formed tag consisting of [something] or [/something], but
         * we still need to ensure that 'something' is a valid tag name. Additionally,
         * if it's a closing tag, we need to ensure that there was a previous matching
         * opening tag.
         */

        /* There could be an attribute. */
        $tagPieces = explode('=', $tagContent);
        $tmpTagName = $tagPieces[0];

        $actualTagName;
        if ('/' == $tmpTagName[0]) {
            /* This is a closing tag name. */
            $actualTagName = substr($tmpTagName, 1);
        } else {
            $actualTagName = $tmpTagName;
        }

        if ('/' == $tmpTagName[0]) {
            /* This is attempting to close an open tag. We must verify that there exists an
             * open tag of the same type and that there is no option (options on closing
             * tags don't make any sense). */
            $elToClose = $parent->closestParentOfType($actualTagName);
            if (null == $elToClose || count($tagPieces) > 1) {
                /* Closing an unopened tag or has an option. Treat everything as plain text. */
                $this->createTextNode($parent, '[');
                $this->createTextNode($parent, $tagContent);
                $this->createTextNode($parent, ']');
                return $parent;
            } else {
                /* We're closing $elToClose. In order to do that, we just need to return
                 * $elToClose's parent, since that will change our effective parent to be
                 * elToClose's parent. */
                return $elToClose->getParent();
            }
        }

        /* Verify that this is a known bbcode tag name. */
        if ('' == $actualTagName || !$this->codeExists($actualTagName, count($tagPieces) > 1)) {
            /* This is an invalid tag name! Treat everything we've seen as plain text. */
            $this->createTextNode($parent, '[');
            $this->createTextNode($parent, $tagContent);
            $this->createTextNode($parent, ']');
            return $parent;
        }

        /* If we're here, this is a valid opening tag. Let's make a new node for it. */
        $el = new ElementNode();
        $el->setNodeId(++$this->nextNodeid);
        $code = $this->getCode($actualTagName, count($tagPieces) > 1);
        $el->setCodeDefinition($code);
        if (count($tagPieces) > 1) {
            /* We have an attribute we should save. */
            unset($tagPieces[0]);
            $el->setAttribute(implode('=', $tagPieces));
        }
        $parent->addChild($el);
        return $el;
    }

    /**
     * Handles parsing elements whose CodeDefinitions disable parsing of element
     * contents. This function uses a rolling window of 3 tokens until it finds the
     * appropriate closing tag or reaches the end of the token stream.
     *
     * @param $parent  the current parent element
     * @param $tokenizer  the tokenizer we're using
     */
    protected function parseAsTextUntilClose(ElementNode $parent, Tokenizer $tokenizer)
    {
        /* $parent's code definition doesn't allow its contents to be parsed. Here we use
         * a sliding of window of three tokens until we find [ /tagname ], signifying the
         * end of the parent. */ 
        if (!$tokenizer->hasNext()) {
            return $parent;
        }
        $prevPrev = $tokenizer->next();
        if (!$tokenizer->hasNext()) {
            $this->createTextNode($parent, $prevPrev);
            return $parent;
        }
        $prev = $tokenizer->next();
        if (!$tokenizer->hasNext()) {
            $this->createTextNode($parent, $prevPrev);
            $this->createTextNode($parent, $prev);
            return $parent;
        }
        $curr = $tokenizer->next();
        while ('[' != $prevPrev || '/'.$parent->getTagName() != strtolower($prev) ||
                ']' != $curr) {
            $this->createTextNode($parent, $prevPrev);
            $prevPrev = $prev;
            $prev = $curr;        
            if (!$tokenizer->hasNext()) {
                $this->createTextNode($parent, $prevPrev);
                $this->createTextNode($parent, $prev);
                return $parent;
            }
            $curr = $tokenizer->next();
        }
    }

}
