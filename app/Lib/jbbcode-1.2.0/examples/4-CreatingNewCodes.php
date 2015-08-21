<?php
require_once "/path/to/jbbcode/Parser.php";

$parser = new JBBCode\Parser();

$parser->addBBCode("quote", '<div class="quote">{param}</div>');
$parser->addBBCode("code", '<pre class="code">{param}</pre>', false, false, 1);
