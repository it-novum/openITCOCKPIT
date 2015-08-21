# h1
## h2
### h3
#### h4

**Bold**
*italic*

* Item
* Item
* Item

1. List
2. List
3. List

~~Mistaken text.~~

| First Header  | Second Header |
| ------------- | ------------- |
| Content Cell  | Content Cell  |
| Content Cell  | Content Cell  |


External link:
[Visit GitHub!](https://github.com)

Intenral link:
[Dashboards](/documentations/wiki/dashboard/dashboard)

~~~
Some quotes here
~~~


Put your images to **webroot/img/docs**:

![TabDash](/img/docs/TabDash1.gif)

## Some inline HTML tricks:
## Text left with CSS class {.text-left} 
## Text center with CSS class {.text-center} 
## Text right with CSS class  {.text-right} 
## Add a ID {#yourId} 

Center a image
<div markdown="1" align="center">![Dashboard](/img/docs/tachometer_128.png)</div>

Some php code:
````php
#!/usr/bin/php5
<?php
echo $foo = 'Bar';
if(Bar::foo()){
	$test = new NaemonWorker();
}

class Bar{
	public static function foo(){
		return true;
	}
}
````

Some javascript code:

````javascript
$('#selector').change(function(){
	//Do tha macig
});
````

Some bash code:

````bash
#!/bin/bash
echo 'this is a test script'
date | grep 2015
````

Some perl code:

````perl
#!/usr/bin/perl
my test = 'foobar';
````


**Markdown help:**

https://michelf.ca/projects/php-markdown/extra/

https://help.github.com/articles/github-flavored-markdown/

eigenes html
<p align="center"><i class="fa fa-usd"></i></p>
