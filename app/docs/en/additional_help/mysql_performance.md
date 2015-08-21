# Warning
Please only change your system configuration if you know what you are doing!

Create a backup of every file you touch and request a maintenance window for your server.

**If you do something wrong you maybe destroy your system!**

## I/O scheduler
openITCOCKPIT's database schema is designed for InnoDB so it's highly recommended to set **noop** or **deadline** as your I/O scheduler.

You can check your I/O scheduler with the following command:
````
cat /sys/block/sda/queue/scheduler
````
The output should looks like this:
````
noop [deadline] cfq 
````
The current used scheduler is marketed with square brackets, in the case: [deadline]

## Change I/O scheduler
To temporarily change the I/O scheduler execute the following command:
````
echo deadline > /sys/block/sda/queue/scheduler
````

If you like to change your I/O scheduler permanently and your system is using *GRUB 2* you need to modify the file **/etc/default/grub**
````
GRUB_CMDLINE_LINUX="elevator=deadline"
````
and update your GRUP configuration
````
update-grub
````

## my.cnf
Basically on InnoDB systems there are three very important values you should take a look at.

In this case we like to optimize a openITCOCKPIT V3 system with the following hardware components
* 16 CPU cores
* 70 GB RAM
* 250 GB HDD provided by [openATTIC](http://www.openattic.org/)

#### innodb_buffer_pool_size
We alway should try to keep the *hot* data inside of the **InnoDB Buffer Pool** so that read request can be answered by data out of memory (RAM)

By default MySQL will store all index data inside of the file **/var/lib/mysql/ibdata1**

So if possible you should set the *innodb_buffer_pool_size* to the same size like the file size of /var/lib/mysql/ibdata1.

If this is possible on your system MySQL is able to respond every query that is using indexes out of memory
````
du /var/lib/mysql/ibdata1 -sh
92G    /var/lib/mysql/ibdata1
````

In my case the ibdata1 file size is 92 GB so I can't my *innodb_buffer_pool_size* to 92G because my system has not that amount of memory.

In this case we should set the value *innodb_buffer_pool_size* to 70% of your memory.

70 GB memory * 70% / 100 = 49GB

So for this system we set the value of *innodb_buffer_pool_size* to 48GB

#### innodb_log_file_size
If MySQL flush *cold* data from **InnoDB Buffer Pool** to the disk, this operation will create a lot of random I/O.

To avoid randome I/O MySQL uses the **InnoDB log files** which are handled sequential and disks love sequential I/O.

MySQL should store 1 hour of data inside of the **InnoDB log files** so you should check the timestamp `ls -la /var/lib/mysql/ib_logfile*` how often your files are getting rotated

After you changed the value of *innodb_log_file_size* you need to delete the files **ib_logfile0** and **ib_logfile1**

#### innodb_flush_log_at_trx_commit
If *innodb_flush_log_at_trx_commit* is turned on MySQL will wait on every *commit* for the successful result of the disk.

This is a save method to save data but disk are slow.

If you disable *innodb_flush_log_at_trx_commit* MySQL will not wait for the response of the disk and this will speed up your MySQL a lot!

**In worst case you will lose one seconds of data which is ok on a monitoring system.**

#### Query Cache
MySQL will create a hash of every query you run to respond to the same query faster. If you increase your cache limit every INSERT or UPDATE statement needs to update the Query Cache.

Tables like hoststatus or servicestatus will be updated very often so a big Query Cache isn't a good idea.

To update the Query Cache your MySQL process requests a [Mutex](https://en.wikipedia.org/wiki/Mutual_exclusion) so every other query needs to wait until the update is done!

**Changing these values carefully otherwise maybe your performance will decrease.**

#### Binary Log
Usually you will not need MySQL binary logs on your openITCOCKPIT server

#### Update your my.cnf
First of all you need to stop your MySQL server
````
service mysql stop
````

**Notice: Ubuntu systems are using upstart or systemd. DO NOT MIX `/etc/init.d/mysql start` and `service mysql start`!**

This is how the my.cnf of this example looks like now:
````
# * InnoDB
#
# InnoDB is enabled by default with a 10MB datafile in /var/lib/mysql/.
# Read the manual for more InnoDB related options. There are many!
#
# * Security Features
#
# Read the manual, too, if you want chroot!
# chroot = /var/lib/mysql/
#
# For generating SSL certificates I recommend the OpenSSL GUI "tinyca".
#
# ssl-ca=/etc/mysql/cacert.pem
# ssl-cert=/etc/mysql/server-cert.pem
# ssl-key=/etc/mysql/server-key.pem

innodb_buffer_pool_size = 48G
innodb_log_file_size    = 512M
innodb_flush_log_at_trx_commit = 0
````
Start your MySQL server:
````
rm /var/lib/mysql/ib_logfile0
rm /var/lib/mysql/ib_logfile1
service mysql start
````