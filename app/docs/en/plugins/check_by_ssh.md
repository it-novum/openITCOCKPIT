## What is check_by_ssh?
With the check_by_ssh monitoring plugin you can execute a plugin on a remote host using SSH

## Security notice
In this example we use **useradd** on the remote system to create a user without a password, so you can only login using the private key!

If you use the command **adduser** you can specify a password and use
````
ssh-copy-id -i /home/nagios/.ssh/id_rsa.pub nagios@$REMOTE-SYSTEM-ADDRESS
````
to copy your public key from the openITCOCKPIT server to your remote system. This is a lazy method but you need to set a strong password to keep your system secure!
Additionally you can run **usermod -L nagios** on the remote system, to lock the user after you copied the ssh key.

## Prepare the remote host
On the remote host you need to create a new user to connect using SSH and execut the command.

In this example we like to monitor an Ubuntu system so we can install all additional software using the packet manager APT.
````
useradd -m nagios
mkdir /home/nagios/.ssh
touch /home/nagios/.ssh/authorized_keys
chown nagios:nagios /home/nagios/ -R
chmod 600 /home/nagios/.ssh/authorized_keys
apt-get update
apt-get install nagios-plugins
````

## Prepare your openITCOCKPIT server
The monitoring plugin check_by_ssh is installed by default.

We recommend to use a SSH key pair to handle the authentication:
````
su nagios
ssh-keygen
````

## Copy the your public SSH key to the remote system
Copy the content of your public key file (generated in the last step) located at **/home/nagios/.ssh/id_rsa.pub** (openITCOCKPIT Server) to the file **/home/nagios/.ssh/authorized_keys** on your remote host.

Your file /home/nagios/.ssh/authorized_keys (remote system) should now looks like this:
````
ssh-rsa AAAAB3Nz<shortened cryptic stuff>PdpIh1IkxvcS/oYzJ7tXmI5OEM745U75 nagios@openITCOCKPIT
````
Now you can test to establish a SSH connection to the remote host. The system should you log in using the SSH key and don't ask for a password.
````
nagios@openITCOCKPIT:/opt/openitc/nagios/libexec$ ssh nagios@192.168.1.1
The authenticity of host '192.168.1.1 (192.168.1.1)' can't be established.
ECDSA key fingerprint is c6:ef:1e:ad:18:b6:d0:d9:6a:14:4e:53:20:fb:1b:e7.
Are you sure you want to continue connecting (yes/no)? yes
Warning: Permanently added '192.168.1.1' (ECDSA) to the list of known hosts.
Welcome to Ubuntu 14.04.1 LTS (GNU/Linux 3.13.0-44-generic x86_64)

The programs included with the Ubuntu system are free software;
the exact distribution terms for each program are described in the
individual files in /usr/share/doc/*/copyright.

Ubuntu comes with ABSOLUTELY NO WARRANTY, to the extent permitted by
applicable law.

$ whoami
nagios
Connection to 192.168.1.1 closed.
````

## Run check_by_ssh (openITCOCKPIT server)
````
su nagios
/opt/openitc/nagios/libexec/check_by_ssh -H 192.168.1.1 -i /home/nagios/.ssh/id_rsa -C "/usr/lib/nagios/plugins/check_load -w 15,10,5 -c 30,25,20"
OK - load average: 0.00, 0.01, 0.05|load1=0.000;15.000;30.000;0; load5=0.010;10.000;25.000;0; load15=0.050;5.000;20.000;0;
````

## Create the command using openITCOCKPIT interface
To create Host and Service checks that use check_by_ssh you need to create a [Command](/documentations/wiki/basic-monitoring/commands/en) and a [Servicetemplate](/documentations/wiki/basic-monitoring/commands/en). Please read the documentation for more inforamtion.

#Weblinks
[check_by_ssh <i class="fa fa-external-link"></i>](https://www.monitoring-plugins.org/doc/man/check_by_ssh.html)