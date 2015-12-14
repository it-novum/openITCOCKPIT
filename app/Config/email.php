<?php
class EmailConfig {
	public $default = array(
		"transport" => "Smtp",
		"host" => "127.0.0.1", 
		"port" => 25,
		"username" => "",
		"password" => "",
	);

	public $fast = array(
		"from" => "you@localhost",
		"sender" => null,
		"to" => null,
		"cc" => null,
		"bcc" => null,
		"replyTo" => null,
		"readReceipt" => null,
		"returnPath" => null,
		"messageId" => true,
		"subject" => null,
		"message" => null,
		"headers" => null,
		"viewRender" => null,
		"template" => false,
		"layout" => false,
		"viewVars" => null,
		"attachments" => null,
		"emailFormat" => null,
		"transport" => "Smtp",
		"host" => "localhost",
		"port" => 25,
		"timeout" => 30,
		"username" => "user",
		"password" => "secret",
		"client" => null,
		"log" => true
	);

}
