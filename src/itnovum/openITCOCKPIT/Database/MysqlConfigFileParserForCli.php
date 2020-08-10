<?php

class MysqlConfigFileParserForCli {
    function parse_mysql_cnf($path) {
        $contents = file_get_contents($path);
        $lines = preg_split("/\r\n|\n|\r/", $contents);
        $config = [];

        $inSection = FALSE;
        foreach($lines as $line) {
            if (preg_match('/\s*\[(.*)\]\s*/', $line, $matchSection)) {
                switch (strtolower(trim($matchSection[1]))) {
                    case 'client':
                    case 'mysql':
                        $inSection = TRUE;
                        break;
                    default:
                        $inSection = FALSE;
                        break;
                }
            }

            if ($inSection) {
                $parts = explode('=', $line, 2);
                $left = trim($parts[0]);
                switch($left)  {
                    case 'user':
                    case 'password':
                    case 'database':
                    case 'port':
                    case 'host':
                        $right = trim($parts[1]);
                        if(preg_match('/^[\'"](.*)[\'"]$/', $right, $match)) {
                            $right = $match[1];
                        }
                        $config[$left] = $right;
                        break;
                    default:
                }
            }
        }
        $shell = '';
        foreach ($config as $key => $val) {
            $var = 'MYSQL_' . strtoupper($key);
            $shell .= $var . '=' . escapeshellarg($val) . "\n";
        }
        $config['shell'] = $shell;
        return $config;
    }
}