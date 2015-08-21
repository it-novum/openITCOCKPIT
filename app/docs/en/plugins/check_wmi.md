## What is WMI?
This is a text

## Prepare your openITCOCKPIT server

To use the wmi check you have to install autoconf as root user.
````bash
#!/bin/bash
apt-get install autoconf
````

Than you can download and unzip the wmi File.
````bash
wget http://www.openvas.org/download/wmi/wmi-1.3.14.tar.bz2
tar -xvf wmi-1.3.14.tar.bz2
````

You have to open the directory and edit the GNUmakefile
````bash
cd wmi-1.3.14
vim GNUmakefile
````

Please add the following line right after the information comment:
**ZENHOME=../..**
Save and close the File.


##Compiling wmic

To compile wmic you have to perform a make with some parameters. Don’t be impatient, it will take some minutes.
````bash
make "CPP=gcc -E -ffreestanding"
cp bin wmic
````

**To Use the wmi interface, you have to create an administration user account on the clients. This user must have the permission to check and send data of the wmi service.**
````
code goes here
````

````bash
#!/bin/bash
echo "foobar"
````

#Weblinks
[Google <i class="fa fa-external-link"></i>](https://google.de)


*italic*