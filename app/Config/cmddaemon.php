<?php
$config = [
	//Path to the named pipe of the daemon
	'pipe' => '/opt/openitc/nagios/var/rw/oitc.cmd',
	
	//Chmod of named pipe
	'mode' => 0660,
	
	//Sleep, between the named pipe gets read
	'sleep' => 500000,
	
	//Loglevel
	// 0 Disable log
	// 1 Log Info
	// 2 Log Warning
	// 4 Log fatal
	// 8 Log debug
	//Simply add up to combine different log level
	// 1 + 2 + 4 = 7 
	'loglevel' => 7,
	
	//Path to the logfile
	'logfile' => '/var/log/oitc_cmd.log'
];
