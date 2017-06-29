=====================
Crate PDO development
=====================

Requirements
============
To be able to run the installation you need to first install vagrant (https://www.vagrantup.com/downloads.html)
and one if it's providers. Development has been done using VirtualBox (https://www.virtualbox.org/) but any provider
should work just as fine.


Installation
============
Download the project::

    git clone git@github.com:crate/crate-pdo.git

Start up the vagrant machine, when run for the first time it will also run the needed provisioning::

    vagrant up

If you are using IntelliJ/PhpStorm IDE you can `follow this guide <https://gist.github.com/mikethebeer/d8feda1bcc6b6ef6ea59>`_
to setup your remote interpreter and test environment environment.

PHP Version
-----------

There are 2 PHP versions installed in the Vagrant box: ``5.6.3`` and ``7.0.2``.
To activate a certain version you need to create a symlink to ``php5`` or ``php7``
in ``/usr/bin/``::

    sudo rm /usr/bin/php
    sudo ln -s /usr/bin/phpX /usr/bin/php

Installing dependencies
-----------------------

Get composer & install dependencies::

    vagrant ssh
    cd /vagrant
    curl -sS https://getcomposer.org/installer | php
    ./composer.phar install

or if environment is outdated::

    ./composer.phar update

Running the tests
=================

Enter the vagrant machine by standing in the project root::

    vagrant ssh

Change directory to the mounted folder::

    cd /vagrant

Execute the tests::

    ./vendor/bin/phpunit --coverage-html ./report

Contributing
============

1. Fork the project
2. Create a pull request
