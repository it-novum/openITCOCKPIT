#!/bin/sh
export DEBIAN_FRONTEND=noninteractive
apt-get update
apt-get install -y python-software-properties
add-apt-repository -y ppa:crate/stable
add-apt-repository -y ppa:openjdk-r/ppa
apt-get update
apt-get install -y crate git libmcrypt-dev libreadline-dev
apt-get build-dep -y php5-cli

git clone git://github.com/php-build/php-build && cd php-build && ./install.sh

php-build -i development 5.6.3 /usr/local/php/5.6.3
ln -s /usr/local/php/5.6.3/bin/php /usr/bin/php5.6
ln -s /usr/bin/php5.6 /usr/bin/php5

php-build -i development 7.0.2 /usr/local/php/7.0.2
ln -s /usr/local/php/7.0.2/bin/php /usr/bin/php7.0
ln -s /usr/bin/php7.0 /usr/bin/php7

# default to php7
ln -s /usr/bin/php7.0 /usr/bin/php

