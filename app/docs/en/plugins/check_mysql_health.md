## What is check_mysql_health?
With check_mysql_health you are able to monitor your MySQL database servers.

For a full list of parameters please see the original documentation. You can find this in the Weblinks section below.

## Prepare your openITCOCKPIT server
````
cd /opt/openitc/nagios/libexec
wget https://labs.consol.de/assets/downloads/nagios/check_mysql_health-2.2.tar.gz
tar xf check_mysql_health-2.2.tar.gz 
cd check_mysql_health-2.2/
./configure
make all
cp plugins-scripts/check_mysql_health ../check_mysql_health
````

## Run a test
After you have installed check_mysql_health you are ready to run a first test.

We recommand to use the local MySQL Server for this. You can find the credentials in the file **/etc/openitcockpit/mysql.cnf**
````
su nagios
cd /opt/openitc/nagios/libexec
./check_mysql_health --hostname localhost --username openitcockpit --password $PASSWORD$ --database openitcockpit --mode qcache-hitrate
````
You should get a output similar like this
````
CRITICAL - query cache hitrate 59.07% | qcache_hitrate=59.07%;90:;80: qcache_hitrate_now=59.07% selects_per_sec=0.00
````

## Create the command using openITCOCKPIT interface
To create Host and Service checks that use check_mysql_health you need to create a [Command](/documentations/wiki/basic-monitoring/commands/en) and a [Servicetemplate](/documentations/wiki/basic-monitoring/commands/en). Please read the documentation for more inforamtion.

## Author of check_mysql_health
Gerhard Laußer <gerhard.lausser@consol.de>

check_mysql_health is published under the GNU General Public License. GPL

#Weblinks
[check_mysql_health <i class="fa fa-external-link"></i>](https://labs.consol.de/nagios/check_mysql_health/index.html)