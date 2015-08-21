jBBCode
=======

jBBCode is a bbcode parser written in php 5.3. It's relatively lightweight and parses
bbcodes without resorting to expensive regular expressions.

Documentation
-------------

For complete documentation and examples visit [jbbcode.com](http://jbbcode.com).

###A basic example

jBBCode includes a few optional, default bbcode definitions that may be loaded through the
`DefaultCodeDefinitionSet` class. Below is a simple example of using these codes to convert 
a bbcode string to html.

```php
<?php
require_once "/path/to/jbbcode/Parser.php";
 
$parser = new JBBCode\Parser();
$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
 
$text = "The default codes include: [b]bold[/b], [i]italics[/i], [u]underlining[/u], ";
$text .= "[url=http://jbbcode.com]links[/url], [color=red]color![/color] and more.";
 
$parser->parse($text);
 
print $parser->getAsHtml();
```

Contribute
----------

I would love help maintaining jBBCode. Look at [open issues](http://github.com/jbowens/jBBCode/issues) for ideas on
what needs to be done. Before submitting a pull request, verify that all unit tests still pass. 

#### Running unit tests 
To run the unit tests,
ensure that [phpunit](http://github.com/sebastianbergmann/phpunit) is installed, or install it through the composer
dev dependencies. Then run `phpunit ./tests` from the project directory. If you're adding new functionality, writing
additional unit tests is a great idea.

Author
------

jBBCode was written by Jackson Owens. You may reach him at [jackson_owens@brown.edu](mailto:jackson_owens@brown.edu).


License
-------

    The MIT License

    Copyright (c) 2011 Jackson Owens

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.
