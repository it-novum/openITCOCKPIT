#!/usr/bin/php
<?php

$API = new oITCv3APIExamples();

$API->loadCommands();

$API->createCommand([
    'name'         => 'API created',
    'command_line' => '$USER1$/check_api.php $ARG1$',
    'command_type' => 1,
]);


$API->close();

class oITCv3APIExamples {
    function __construct() {
        $this->server = "https://172.16.2.44";
        $this->username = "api@openitcockpit.org";
        $this->password = "123456789";
        $this->remember_me = 1;
        $this->_cookieFileLocation = dirname(__FILE__) . '/cookie.txt';
        $this->verbose = true;
        $this->data = [];
        error_reporting(E_ALL);
        $this->_init($this->server);
        $this->_login();
    }

    public function loadCommands() {
        $url = $this->_appendUrl('Commands/index/.json');
        $this->_sendRequest($url);
        print_r(json_decode($this->data));
    }

    public function createCommand($fields) {
        $url = $this->_appendUrl('Commands/add/');
        $query = '_method=POST&data[Command][name]=' . $fields['name'] . '&data[Command][command_line]=' . $fields['command_line'] . '&data[Command][command_type]=' . $fields['command_type'];
        $this->_sendRequest($url, $query);
    }

    private function _login() {
        $url = $this->_appendUrl('login/login');
        $fields = [
            'email'       => rawurlencode($this->username),
            'password'    => rawurlencode($this->password),
            'remember_me' => rawurlencode($this->remember_me),
        ];

        $query = '_method=POST&data[LoginUser][email]=' . $fields['email'] . '&data[LoginUser][password]=' . $fields['password'] . '&data[LoginUser][remember_me]=' . $fields['remember_me'];

        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $query);

        curl_setopt($this->ch, CURLOPT_URL, $url);
        $login = curl_exec($this->ch);
        $this->close();
    }

    private function _init($url) {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->_cookieFileLocation);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->_cookieFileLocation);
        curl_setopt($this->ch, CURLOPT_VERBOSE, $this->verbose);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false); //accept every SSL cert
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false); //dont check host
    }

    private function _appendUrl($url) {
        return $this->server . '/' . $url;
    }

    public function close() {
        curl_close($this->ch);
    }

    private function _sendRequest($url, $query = '') {
        $this->_init($url);
        if ($query !== '') {
            curl_setopt($this->ch, CURLOPT_POST, true);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $query);
        }
        $this->data = curl_exec($this->ch);
    }
}