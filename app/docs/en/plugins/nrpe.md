## What is NRPE?
NRPE or Nagios Remote Plugin Executor provides you a easy method to execute a monitoring plugin on a remote Linux host.

## Prepare your openITCOCKPIT server
On your openITCOCKPIT server you only need to install the check_nrpe monitoring plugin.

Navigate to Administration -> Package manager and install the check_nrpe extension:

![install_check_nrpe](/img/docs/plugins/install_check_nrpe.png)

## Install NRPE daemon on the remote host
On the remote host you need to install the NRPE daemon which executes to command on the remote host. The monitoring engine on the openITCOCKPIT Server
schedules the execution of the check on the remote host due to NRPE daemon using check_nrpe.

In this example we like to monitor an Ubuntu system so we can install all additional software using the packet manager APT.
````
apt-get update
apt-get install nagios-nrpe-server
````

## Important NRPE daemon configuration options (remote host)
By default the configuration for NRPE can be found at **/etc/nagios/nrpe.cfg**

First of all you need to set the ip address of your openITCOCKPIT server. Only this address is able to communicate with NRPE and execute commands
````
allowed_hosts=10.0.0.1
````

NRPE is able to accept command arguments (parameters) and pass it to the check plugin.

This option is usefull if you work with Servicetemplates provided by openITCOCKPIT. So can easily change parameters on every system you monitor using NRPE without touching nrpe.cfg on the remote host
````
# *** ENABLING THIS OPTION IS A SECURITY RISK! *** 
dont_blame_nrpe=0
````

At the end of the file you can find a set of predefined commands you can now monitor. By default only commands with hard coded command arguments are enabled.
````
# COMMAND DEFINITIONS
# The following examples use hardcoded command arguments...
command[check_users]=/usr/lib/nagios/plugins/check_users -w 5 -c 10
command[check_load]=/usr/lib/nagios/plugins/check_load -w 15,10,5 -c 30,25,20
command[check_hda1]=/usr/lib/nagios/plugins/check_disk -w 20% -c 10% -p /dev/hda1
command[check_zombie_procs]=/usr/lib/nagios/plugins/check_procs -w 5 -c 10 -s Z
command[check_total_procs]=/usr/lib/nagios/plugins/check_procs -w 150 -c 200 
````

There is also a set of commands available that accept command arguments. As mentioned above, to use command arumgents you need to set **dont_blame_nrpe=1**
````
# COMMAND DEFINITIONS
# The following examples allow user-supplied arguments...
command[check_users]=/usr/lib/nagios/plugins/check_users -w $ARG1$ -c $ARG2$
command[check_load]=/usr/lib/nagios/plugins/check_load -w $ARG1$ -c $ARG2$
command[check_disk]=/usr/lib/nagios/plugins/check_disk -w $ARG1$ -c $ARG2$ -p $ARG3$
command[check_procs]=/usr/lib/nagios/plugins/check_procs -w $ARG1$ -c $ARG2$ -s $ARG3$
````

To apply your changes you need to restart the NRPE daemon
````
service nagios-nrpe-server restart
````

# check_nrpe quick start (openITCOCKPIT server)
If you have installed check_nrpe via openITCOCKPIT's inbuilt paket manager you can find the plugin at: **/opt/openitc/nagios/libexec/check_nrpe**

The usage is not that tricky but you should take a look at --help

The main constraints are
````
-H 192.168.1.1       => The address of the remote host
-c check_load        => The name of the command you like to execute, defined in the square brackets in nrpe.cfg
-a 15,10,5 30,25,20  => Optional command arguments, if enabled in nrpe.cfg $ARG1$ = 15,10,5 $ARG2$ = 30,25,20
````
## Run a test (openITCOCKPIT server)
After you have installed and configure NRPE we are ready to run the first test and check if everything is working like expected.

Check example with hard coded command arguments:
````
cd /opt/openitc/nagios/libexec
./check_nrpe -H 192.168.0.10 -c check_users
````
You should get a output similar like this
````
USERS OK - 2 users currently logged in |users=2;5;10;0
````

Check example with dynamic command arguments:
````
cd /opt/openitc/nagios/libexec
./check_nrpe -H 192.168.0.10 -c check_load -a 15,10,5 30,25,20
````
You should get a output similar like this
````
OK - load average: 0.00, 0.06, 0.08|load1=0.000;15.000;30.000;0; load5=0.060;10.000;25.000;0; load15=0.080;5.000;20.000;0; 
````
## Create the command using openITCOCKPIT interface
To create Host and Service checks that use NRPE you need to create a [Command](/documentations/wiki/basic-monitoring/commands/en) and a [Servicetemplate](/documentations/wiki/basic-monitoring/commands/en). Please read the documentation for more inforamtion.

#Weblinks
[NRPE <i class="fa fa-external-link"></i>](https://exchange.nagios.org/directory/Addons/Monitoring-Agents/NRPE--2D-Nagios-Remote-Plugin-Executor/details)

[NRPE GitHub <i class="fa fa-external-link"></i>](https://github.com/NagiosEnterprises/nrpe)