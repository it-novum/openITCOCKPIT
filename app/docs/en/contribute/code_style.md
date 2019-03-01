## Why is a code style guide so important?

openITCOCKPIT is a huge project with hundreds of PHP and JavaScript files.

To ensure that every developer is able to read and understand the code we created a few simple rules you should follow.

## Editors

At the end of the day, it's up to you which editor you like to use for coding. These are just our personal recommendations:

[PhpStorm](https://www.jetbrains.com/phpstorm/) [Windows, Linux, macos]

[TextMate](https://macromates.com/) [macOS]

[Sublime Text](http://www.sublimetext.com/) [Windows, Linux, macOS]

[Atom](https://atom.io/) [Windows, Linux, macos]

## Editor settings

##### 4 Spaces for indention
Use 4 spaces for indenting your code and never - realy never - mix tabs and spaces

##### Newlines
Use UNIX-style newlines [\n]

##### No trailing whitespaces
Check your files for trailing whitespaces befor save and commit

##### 100 Characters per line

##### Use UTF-8 as your default charset!
And set UTF-8 as your default system charset for Terminal (like PuTTY), your Editor, etc...

## PHP style

##### Never write a closing PHP tag in plain PHP files

##### Use single quotes (excluding JSON code)
Right:
````php
<?php
$foo = 'bar';
````
Wrong:
````php
<?php
$foo = "bar";
?>
````

##### Don’t let PHP do the dirty job for you
Right:
````php
echo 'Hi my name is' . $name;
````
Wrong:
````php
echo "Hi my name is $name";
````

##### Opening braces go in the same line as the statement
Right:
````php
if(true){
    echo 'Good job';
}
````
Wrong:
````php
if(false) 
{
    echo 'Bad job'; 
}
````

##### Closing else braces go in the same line as the else
Right:
````php
if(true){
    //true
}else{
    //false
}
````
Wrong:
````php
if(false){
    //true
}
else{
    //false
}
````

##### Think twice about your code
Right:
````php
echo '<img src="status_' . $status .'.png" />';
````

Wrong:
````php
echo '<img src="' . getStatus($status) .'" />';
function getStatus($status){
    if($status == 1){
        return 'status_1.png';
    }

    if($status == 2){
        return 'status_2.png';
    }

    if($status == 3){
        return 'status_3.png';
    }
````

##### Use the === operator
PHP is very lazy with data types. To avoid confusions use strict type checking as often as possible

Right:
````php
if($foobar === 15){
    echo 'Good job';
}
````

Wrong:
````php
if($foobar == 15){
   echo 'Not that good';
}
````

Wrong:
````php
if($foobar == '15'){
   echo 'Seriously?';
}
````

##### Use PHP template syntax if you need to mix PHP and HTML
Right:
````php
<?php foreach($foo as $bar): ?>
    <input type="text" value="<?php echo $bar['text']; ?>" />
<?php endforeach; ?>
````

Wrong:
````php
<?php foreach($foo as $bar){ ?>
    <input type="text" value="<?php echo $bar['text']; ?>" />
<?php } ?>
````

Examples:
````php
while(true):
    //loop
endwhile;

for($foo; $bar; $foo++):
    //loop
endfor;

if(true):
    //Is ture
else:
    //Is false
endif;
````
##### Create human readable Arrays and use short arry syntax
Right:
````php
$foobar = [
    'Host' => [
        'name'    => 'localhost',
        'address' => '127.0.0.1,
    ],
    'Service => [
        //More code
    ],
];
`````
Wrong:
````php
$foobar = ['Host' = ['name' => 'localhost', 'address' => '127.0.01], 'Service' => []];
````

##### Let your methods return as quickly as possible
Right:
````php
if(isTrue()){
    return true;
}
return false;
````
Wrong:
````php
if(isTrue()){
    return true;
}else{
    return false;
}
````

##### Use lowerCamelCase for variables, properties
Right:
````
$allHosts = $HostsTable->find()->all();
```` 
Wrong:
````
$all_hosts = $HostsTable->find()->all();
````

##### Use lower_underscored for paginate() results
Right:
````
$all_hosts = $this->Paginator->paginate();
````
Wrong:
````
$allHosts = $this->Paginator->paginate();
````

##### Use UpperCamelCase for class names
Right:
````
class HostsController{}
```` 
Wrong:
````php
class hosts_controller{}
````

##### Use UpperCamelCase for instantiated classes
Right:
````
$HostFilter = new HostFilter();
```` 
Wrong:
````
$hostFilter = new HostFilter();
//or
$host_filter = new HostFilter();
````

##### Use UPPERCASE for constants
Right:
````
define('FOO', 'bar');
````
Wrong:
````
define('foo', 'bar');
````

##### Load external files (Always use require_once without braces)
Right:
````
require_once APP . DS . 'lib' . DS . foobar.php';
```` 
Wrong:
````
require_once(APP . DS . 'lib' . DS . foobar.php');
````

##### Use CakePHP's h() to escape HTML to avoid JavaScript injection
Right:
````
echo h($host['Host']['name']);
//Imagen - the hostname could be something evil like <script src="hack.js"></script>
```` 
Wrong:
````php
echo $host['Host']['name'];
````

##### Executable PHP scripts (Command line)
Right
````
#!/usr/bin/env php
<?php
echo 'Hello world'.PHP_EOL;
````
Call:
````
chmod +x vim /tmp/example.php
/tmp/example.php
````
Wrong:
````php
<?php
echo 'Hello world'.PHP_EOL;
````
Call:
````
php5 /tmp/example.php
````

##### Forbidden PHP functions
We all know that these functions are evil! Do NOT use them!
````php
eval();
exec();
shell_exec();
$dir `ls -la`;
`````

## JavaScript style
##### Default parameter, as you would do it in PHP
Right:
````javascript
function foobar(foo){
    var foo = foo || {};
    this.foo = foo.foo || 'foo';
    this.int = foo.int || 1;
    //Do some cool stuff
}
```` 
Wrong:
````javascript
function foobar(foo, int){
    //Do some cool stuff
}
````

##### Chained method calls
Right:
````javascript
$('.selector')
    .addClass('foo')
    .children()
        .addClass('bar');
}
```` 
Wrong:
````javascript
$('.selector').addClass('foo').children().addClass('bar');
````

##### Attempts to keep the PHP style

## CSS style
##### Create useful CSS classes
Right:
````
<div class="red radius bold"></div>>
.red{
    color: red;
}
.radius{
    border-radius: 5px;
}
.bold{
    font-weight: bold;
}
````
Wrong:
````
<div class="myDiv"></div>
.myDiv{
    color: red;
    border-radius: 5px;
    font-weight: bold;
}
````

##### Only use default CSS commands
Right:
````
.radius{
    border-radius: 5px;
}
````
Wrong:
````
.radius{
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}
````

##### Code human readable CSS
Right:
````
.myDiv{
    border-radius: 5px;
    background-color: #FFFFFF;
    color: #000000;
}
````
Wrong:
````
.myDiv{border-radius: 5px;background-color: #FFFFFF;color: #000000;}
````

## No Denglisch!
We try to document everything in english and name every variable by english names.

Please follow our example :-)

Right:
````php
$result = getTenantShorthand();
````
Wrong:
````php
$result = getMandantenKuerzel();
````

## Weblinks
[Denglisch <i class="fa fa-external-link"></i>](https://en.wikipedia.org/wiki/Denglisch)