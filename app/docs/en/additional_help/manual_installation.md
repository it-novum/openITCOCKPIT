## Information:
This page describes how to install openITCOCKPIT manually. This may be helps you to port openITCOCKPIT for unsupported distributions.

We use **Ubuntu 14.04** in this example. Please only follow this guide if you know what you are doing!

Keep in mind that some functions may be not work like expected, like the inbuilt "Package manager" or the update process of openITCOCKPIT itself.

## Install openITCOCKPIT using the packages:
First of all you need to install openITCOCKPIT on an Ubuntu 14.04 system by following [the official instructions!](https://github.com/it-novum/openITCOCKPIT#installation)

We will use this system later to copy all required configuration files.

## Install a second system with the distribution you like to use:
**Notcie:** openITCOCKPIT is developed for Debian based distributions. Try to install as much as possible with the package manager of your distribution!

If some packages are not available you need to download and compile them yourself :(

#### Install basic requirements:
````
apt-get install screen libgd-dev build-essential mysql-server \
php5-ldap php5-dev php5-cli mysql-server rrdtool nginx php5-fpm libssl-dev \
ssl-cert php5-rrd php5-gd php5-curl php5-xmlrpc libssh2-php \
php5-mcrypt php5-gearman gearman-job-server gearman-tools php5-mysql
````

#### Install nice to have packages:
````
apt-get install htop vim mc tmux iftop sysstat wget git
````

#### Create system user:
````
useradd -m nagios
usermod -G nagios www-data
````

#### Create required directories:
````
mkdir -p /opt/openitc/nagios
mkdir -p /usr/share/openitcockpit
mkdir -p /etc/openitcockpit
mkdir /etc/openitcockpit/nagios.cfg.d
mkdir -p /var/log/nginx/cake/
````

#### Create mysql database and user:
````
mysql -u root -p
mysql> CREATE DATABASE openitcockpit DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
mysql> CREATE USER 'openitcockpit'@'localhost' IDENTIFIED BY 'password123';
mysql> GRANT ALL ON openitcockpit.* TO 'openitcockpit'@'localhost' IDENTIFIED BY 'password123';
mysql> exit
````

Now you need to create the configuration **/etc/openitcockpit/mysql.cnf** file, that contains your mysql credentials
````
[client]
database = openitcockpit
user     = openitcockpit
password = password123
host     = localhost
port     =
````

## Install Naemon:
````
apt-get install help2man gperf
wget https://github.com/naemon/naemon-core/archive/v1.0.3.tar.gz
tar xfv v1.0.3.tar.gz
cd naemon-core-1.0.3/
./autogen.sh --prefix=/opt/openitc/nagios
make all
make install

rm -rf /opt/openitc/nagios/etc/*
mkdir /opt/openitc/nagios/etc/config
mkdir /opt/openitc/nagios/rollout
mkdir /opt/openitc/nagios/satellites
mkdir -p /opt/openitc/nagios/var/rw
mkdir -p /opt/openitc/nagios/var/spool/perfdata
mkdir /opt/openitc/nagios/var/spool/checkresults/

rm -rf /opt/openitc/nagios/var/log/
chown nagios:nagios /opt/openitc/nagios/var -R
````

Now we need to copy the naemon configuration files from the official openITCOCKPIT installation

Replace **UBUNTU_SYSTEM** with the ip address of your installation (or add it to /etc/hosts and be lazy :))
````
scp root@UBUNTU_SYSTEM:/etc/openitcockpit/nagios.cfg /etc/openitcockpit/
ln -n /etc/openitcockpit/nagios.cfg /etc/openitcockpit/naemon.cfg
ln -n /etc/openitcockpit/nagios.cfg /opt/openitc/nagios/etc/nagios.cfg

ln -s /opt/openitc/nagios/bin/naemon /opt/openitc/nagios/bin/nagios
ln -s /opt/openitc/nagios/bin/naemonstats /opt/openitc/nagios/bin/nagiostats

scp -r root@UBUNTU_SYSTEM:/etc/init.d/nagios /etc/init.d/nagios
ln -s /etc/init.d/nagios /etc/init.d/naemon

````
**Notice:** May be you need to modify the file /etc/openitcockpit/nagios.cfg for your distribution!

## Install Naemon logrotate:
Create the file **/etc/logrotate.d/naemon** with the folowing content:
````
/opt/openitc/nagios/var/nagios.log {
    daily
    rotate 365
    compress
    olddir archives
    dateext
    dateformat -%Y%m%d
    missingok
    ifempty
    postrotate
      [ -f /opt/openitc/nagios/var/nagios.lock ] && kill -s USR1 `cat /opt/openitc/nagios/var/nagios.lock`
    endscript
    create 0664 nagios www-data
}
````

## Install Monitoring Plugins:
````
apt-get install libnagios-plugin-perl
wget https://www.monitoring-plugins.org/download/monitoring-plugins-2.1.2.tar.gz
tar xfv monitoring-plugins-2.1.2.tar.gz
cd monitoring-plugins-2.1.2
./configure --prefix=/opt/openitc/nagios
make all
make install
````

## Install NDOUtils:
````
apt-get install libmysqlclient-dev
wget https://github.com/NagiosEnterprises/ndoutils/archive/ndoutils-2-0-0.tar.gz
tar xfv ndoutils-2-0-0.tar.gz
cd ndoutils-ndoutils-2-0-0/
./configure --prefix=/opt/openitc/nagios
make
make all
make install
make install-init

cp config/ndo2db.cfg-sample /etc/openitcockpit/ndo2db.cfg
cp config/ndomod.cfg-sample /etc/openitcockpit/ndomod.cfg
ln -s /etc/init.d/ndo2db /etc/init.d/ndo
chown nagios:nagios /etc/openitcockpit/ndomod.cfg
ln -s /etc/openitcockpit/ndo2db.cfg /opt/openitc/nagios/etc/ndo2db.cfg
````

Configure your mysql username and password and set all max_\*_age to zero in **/etc/openitcockpit/ndo2db.cfg**
````
db_name=openitcockpit
db_user=openitcockpit
db_pass=password123

max_timedevents_age=0
max_systemcommands_age=0
max_servicechecks_age=0
max_hostchecks_age=0
max_eventhandlers_age=0
max_externalcommands_age=0
max_notifications_age=0
max_contactnotifications=0
max_contactnotificationmethods=0
max_logentries_age=0
max_acknowledgements_age=0
````

Edit the file **/etc/openitcockpit/nagios.cfg** and remove every broker_module line.

Insert the following line:
````
broker_module=/opt/openitc/nagios/bin/ndomod.o config_file=/etc/openitcockpit/ndomod.cfg
````

## Install NPCD:
````
apt-get install librrds-perl librrdp-perl librrd-simple-perl
wget -O pnp4nagios-latest.tar.gz https://sourceforge.net/projects/pnp4nagios/files/latest
tar xfv pnp4nagios-latest.tar.gz
cd pnp4nagios-0.6.25/
./configure --prefix=/opt/openitc/nagios/3rd/pnp --with-nagios-group=nagios \
--datarootdir=/opt/openitc/nagios/share/pnp4nagios \
--sysconfdir=/etc/openitcockpit/pnp \
--with-perfdata-dir=/opt/openitc/nagios/share/perfdata \
--bindir=/opt/openitc/nagios/bin \
--sbindir=/opt/openitc/nagios/bin \
--libexecdir=/opt/openitc/nagios/libexec \
--localstatedir=/opt/openitc/nagios/var

make all

cp src/npcd /opt/openitc/nagios/bin/
mkdir -p /opt/openitc/nagios/share/perfdata
mkdir -p /opt/openitc/nagios/var/stats
chown nagios:nagios /opt/openitc/nagios/var/stats
chown nagios:nagios /opt/openitc/nagios/share/ -R
chown nagios:www-data /opt/openitc/nagios/share/perfdata -R
cp scripts/process_perfdata.pl /opt/openitc/nagios/libexec/
chown nagios:nagios /opt/openitc/nagios/libexec/process_perfdata.pl
chmod +x /opt/openitc/nagios/libexec/process_perfdata.pl
````

Now we need to copy the npcd configuration files from the official openITCOCKPIT installation

Replace **UBUNTU_SYSTEM** with the ip address of your installation
````
scp -r root@UBUNTU_SYSTEM:/etc/openitcockpit/pnp /etc/openitcockpit/
scp root@UBUNTU_SYSTEM:/etc/init.d/npcd /etc/init.d/npcd
````
**Notice:** May be you need to modify the files in /etc/openitcockpit/pnp/ for your distribution!

## Install wkhtmltopdf >= 0.12.1:
````
wget http://download.gna.org/wkhtmltopdf/0.12/0.12.2.1/wkhtmltox-0.12.2.1_linux-trusty-amd64.deb
apt-get install xfonts-base xfonts-75dpi
dpkg -i wkhtmltox-0.12.2.1_linux-trusty-amd64.deb
````

## Install openITCOCKPIT interface:
Replace the version number with the current version!

````
wget https://github.com/it-novum/openITCOCKPIT/archive/3.0.5.zip
unzip 3.0.5.zip
cd openITCOCKPIT-3.0.5/
cp -r * /usr/share/openitcockpit/
rm /usr/share/openitcockpit/openitcockpit.sql
rm -rf /usr/share/openitcockpit/app/Config
chown www-data:www-data /usr/share/openitcockpit/app/ -R
mkdir -p /etc/openitcockpit/app/Config
cp -r app/Config/* /etc/openitcockpit/app/Config/
ln -s /etc/openitcockpit/app/Config /usr/share/openitcockpit/app/Config
mv /etc/openitcockpit/app/Config/database.php_example /etc/openitcockpit/app/Config/database.php
chown www-data:nagios /etc/openitcockpit/app/Config/database.php
ln -s /usr/share/openitcockpit/app/UPDATE.sh /usr/sbin/openitcockpit-update

scp root@UBUNTU_SYSTEM:/usr/bin/oitc /usr/bin/oitc
````

Configure your mysql username and password **/etc/openitcockpit/app/Config/database.php**
````
        private $development = array(
                'datasource' => 'Database/Mysql',
                'persistent' => false,
                'host' => 'localhost',
                'login' => 'openitcockpit',
                'password' => 'password123',
                'database' => 'openitcockpit',
                'prefix' => '',
                'encoding' => 'utf8'
        );

        private $production = array(
                'datasource' => 'Database/Mysql',
                'persistent' => false,
                'host' => 'localhost',
                'login' => 'openitcockpit',
                'password' => 'password123',
                'database' => 'openitcockpit',
                'prefix' => '',
                'encoding' => 'utf8'
        );
````

## Copy openITCOCKPIT init scripts:
````
scp root@UBUNTU_SYSTEM:/etc/init.d/gearman_worker /etc/init.d/
scp root@UBUNTU_SYSTEM:/etc/init.d/sudo_server /etc/init.d/
scp root@UBUNTU_SYSTEM:/etc/init.d/oitc_cmd /etc/init.d/
````

## Copy nginx configuration:
````
scp root@UBUNTU_SYSTEM:/etc/nginx/sites-available/openitc /etc/nginx/sites-available/
rm /etc/nginx/sites-enabled/default
ln -s /etc/nginx/sites-available/openitc /etc/nginx/sites-enabled/openitc
service nginx restart
````

## Import default database:
````
mysql -uopenitcockpit -ppassword123 openitcockpit < openitcockpit.sql
````

## Set NDO kernel parameters:
Add the following lines to **/etc/sysctl.conf**:
````
kernel.msgmax = 1310720000
kernel.msgmnb = 1310720000
kernel.msgmni = 655360000
````

Enable changes:
````
sysctl -p
````

## Start NDO and NPCD:
````
service ndo start
service npcd start
````

## Configure your openITCOCKPIT installation:
````
/usr/share/openitcockpit/app/SETUP.sh
````

## Start background daemons:
````
service sudo_server start
service gearman_worker start
service oitc_cmd start

update-rc.d sudo_server defaults
update-rc.d oitc_cmd defaults
update-rc.d gearman_worker defaults

update-rc.d nagios defaults
update-rc.d ndo defaults
update-rc.d npcd defaults
````

## Add openITCOCKPIT cronjob
Create the file **/etc/cron.d/openitc** with the folowing content:
````
#Set PATH, otherwise restart-scripts won't find start-stop-daemon
PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin
#Regular cron jobs for openITCOCKPIT
* * * * *	root	sudo -g www-data /usr/share/openitcockpit/app/Console/cake cronjobs -q
````
````
service cron restart
````


## You're done :)

## Weblinks:
[Naemon <i class="fa fa-external-link"></i>](http://www.naemon.org/)

[Monitoring Plugins <i class="fa fa-external-link"></i>](https://www.monitoring-plugins.org/)

[Gearman <i class="fa fa-external-link"></i>](http://gearman.org/)

[php5-gearman <i class="fa fa-external-link"></i>](https://pecl.php.net/package/gearman)

[NDOUtils <i class="fa fa-external-link"></i>](https://github.com/NagiosEnterprises/ndoutils)

[PNP4Nagios <i class="fa fa-external-link"></i>](https://github.com/lingej/pnp4nagios)

[wkhtmltopdf <i class="fa fa-external-link"></i>](http://wkhtmltopdf.org/)
