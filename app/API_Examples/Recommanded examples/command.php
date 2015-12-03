#!/usr/bin/php
<?php
/*
 * This example shows you how to create, update and delete a command (CRUD)
 */

require_once 'httpful/bootstrap.php';

//use Httpful\Request;
login();
function createCommand(){
	$url = "https://172.16.2.44/commands.json";
	$data = [
		'Command' => [
			'command_type' => 1, //Integer value (1,2,3)
			'name'         => 'Command over API',
			'command_line' => '$USER1$/check_api',
			'description'  => 'This command was created using the openITCOCKPIt API interface'
		]
	];
	
	//If we use a post request, cake can do its macig, and runs the add method!!
	
	$response = \Httpful\Request::get($url)
	->withoutStrictSSL()
	->useProxy('http://proxy.master.dns', '8080')
	->body(json_encode($data))
	->send();
	print_r($response);
}

function login(){
	$url = "https://172.16.2.44/login/login.json";
	$data = [
		'LoginUser' => [
			'email'       => 'api@openitcockpit.org',
			'password'    => '123456789',
			'remember_me' => 0,
		]
	];
	
	//If we use a post request, cake can do its macig, and runs the add method!!
	
	$response = \Httpful\Request::get($url)
	->withoutStrictSSL()
	->useProxy('http://proxy.master.dns', '8080')
	->body(json_encode($data))
	->expectsJson()
	->send();
	print_r($response);
}