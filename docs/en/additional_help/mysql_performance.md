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
and update your GRUB configuration
````
update-grub
````

## my.cnf
Basically on InnoDB systems there are some very important values you should take a look at.

In this case we like to optimize a openITCOCKPIT V3 system with the following hardware components
* 16 CPU cores
* 70 GB RAM
* 250 GB HDD provided by [openATTIC](http://www.openattic.org/)


#### innodb_buffer_pool_size
We always should try to keep the *hot* data inside of the **InnoDB Buffer Pool** so that read request can be answered by data out of memory (RAM).

By default MySQL will store all index data inside of the file **/var/lib/mysql/ibdata1**

So if possible you should set the *innodb_buffer_pool_size* to the same size like the file size of /var/lib/mysql/ibdata1.

If this is possible on your system MySQL is able to respond every query that is using indexes out of memory.
````
du /var/lib/mysql/ibdata1 -sh
92G    /var/lib/mysql/ibdata1
````

In my case the ibdata1 file size is 92 GB so I can't my *innodb_buffer_pool_size* to 92G because my system has not that amount of memory.

In this case we should set the value *innodb_buffer_pool_size* to 80% of your memory.

70 GB memory * 80 / 100 = 56 GB

So for this system we set the value of *innodb_buffer_pool_size* to 5 6GB.

80% of total memory for innodb_buffer_pool_size is definitively the rule of thumb! At a starting point of mysql tuning, this is without doubt a good one. But it is just as important to increase the innodb_buffer_pool_size as large as possible, without using swap!
For smaller systems, that rule of thumb work well. As we get into larger environments, it starts to seem less sane, because much memory might be unused, if there is no corresponding increasing of the rest of the workload.
````
Example:
 Total   BufferPool   Rest
  16 GB    13 GB      3 GB
 512 GB   409 GB    103 GB
````
Furthermore it is important to know, how much memory the rest of the server does need for other processes or handling mysql connections and queries. Mostly not that amount of left memory, 100 GB in that second case.


#### innodb_buffer_pool_instances
For systems with a large amount of memory (=multi-gigabyte range of innodb_buffer_pool_size), dividing the buffer pool into separate instances by setting more than one buffer pool instance, which is default, can improve concurrency.
The rule of thumb here is setting a number of instances, that each buffer pool instance is at least 1 GB.


#### innodb_log_file_size
This is a very important parameter and it is strongly recommended to adjust it!
If MySQL flush *cold* data from **InnoDB Buffer Pool** to the disk, this operation will create a lot of random I/O.
To avoid random I/O MySQL uses the **InnoDB log files** which are handled sequential and disks love sequential I/O.

MySQL should store 1 hour of data inside of the **InnoDB log files** so you should check the timestamp `ls -la /var/lib/mysql/ib_logfile*` how often your files are getting rotated

After you changed the value of *innodb_log_file_size* you need to delete the files **ib_logfile0** and **ib_logfile1**


#### innodb_log_buffer_size
This parameter defines the size of the buffer that InnoDB uses for transaction caching. Default is 8MB.
If you have large transactions or transactions that insert, update or delete many rows, making the log buffer larger reduces disk I/O, because there is no need to write transactions to disk before there is the transaction commit.


#### innodb_io_capacity
This parameter sets an upper limit on I/O activity, is a total limit for all buffer pool instances and should be set to the effective amount of available IOPS of each system.
The default value is 200, which, for example, would be ok in a 2-disk-RAID-0 or 4-disk-RAID-10 setup using SATA 7.2k RPM hard disk drives.
These further IOPS examples only serve for orientation:
- SATA 7.2k RPM  ~=  100
- SAS 10k RPM    ~=  150
- SAS 15k RPM    ~=  250
- SSD            ~= 5000
Of course, all the values above are dependent on other parameters and the system settings.
````
Example:
RAID-5 with 5 SAS 15k RPM hard disk drives
5 disks - 1 parity = 4 data
4 x 250 IOPS = 1000 IOPS
````


#### innodb_read_io_threads
This is the counterpart of innodb_write_io_threads.
The purpose of changing this parameter is making InnoDB more scalable on high end systems.
Each background thread can handle up to 256 pending I/O requests.
The default value is 4. If you see more than 64 pending read io requests in SHOW ENGINE INNODB STATUS, you might gain performance by increasing that value.


#### innodb_write_io_threads
This is the counterpart of innodb_read_io_threads.
The purpose of changing this parameter is making InnoDB more scalable on high end systems.
Each background thread can handle up to 256 pending I/O requests.
The default value is 4. If you see more than 64 pending write io requests in SHOW ENGINE INNODB STATUS, you might gain performance by increasing that value.


#### innodb_thread_concurrency
The default value of this parameter is 0, which means infinite concurrency. Sounds good at first glance, but it may create problems if you are running other daemons on your system as well. The correct setting of this parameter again depends on the workload and environment that you are running. At that point you have to make some testing and maybe a couple of restarts of your MySQL installation, to determine best settings.
Only if there are concurrency issues on your system, a good recommendation is setting this parameter to two times the number of CPUs plus the number of disks.


#### innodb_flush_log_at_trx_commit
This parameter determines whether the database engine is flushing the log buffer directly to disk or to OS file cache - on every transaction commit or once per second.
Default value is 1.

If *innodb_flush_log_at_trx_commit* is turned on MySQL will wait on every *commit* for the successful result of the disk.
This is a save method to save data but disk are slow.
If you disable *innodb_flush_log_at_trx_commit* MySQL will not wait for the response of the disk and this will speed up your MySQL a lot!

The contents of the InnoDB log buffer are written to the log file...
0 =  ...approximately once per second, the log file is flushed to disk and no writes from the log buffer to the log file are performed at transaction commit!
1 =  ...at each transaction commit and the log file is flushed to disk!
2 =  ...after each transaction commit and the log file is flushed to disk approximately once per second!

By setting a value of "0" any mysqld process crash can erase the last second of transaction data.
By setting a value of "2" only an operating system crash or a power outage can erase the last second of transaction data.

Therefore, choose "1" if you want to have to greatest possible consistency.
Otherwise choose "2", because with this setting you gain a much faster write speed and you only lose data when the hardware fails.

**In worst case you will lose one seconds of data which is ok on a monitoring system.**


#### innodb_flush_method
This parameter defines the method which is used to flush data to data files and log files, which can affect I/O performance.
There are several settings possible. How each setting affects performance depends on hardware configuration and the workload.
The setting of this parameter depends on whether your system is "battery-backed" (BBU/BBM/BBWC) or not and if you want to be protected from the risk of losing data. Use this setting only if you have a hardware RAID controller and battery-backed write cache.
Setting this parameter to "O_DIRECT" double buffering will be avoided, which relieves I/O pressure.


#### innodb_file_per_table
This parameter is activated by default from MySQL 5.6. To avoid having a huge shared tablespace it is usually  recommended to activate this parameter even in previous version. Furthermore it allows you to reclaim space when you drop or truncate tables.


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

# just example values !!!
innodb_buffer_pool_size = 56G
innodb_log_file_size    = 1024M
innodb_log_buffer_size  = 32M
innodb_buffer_pool_instances = 56
innodb_io_capacity = 1000
innodb_read_io_threads = 8
innodb_write_io_threads = 8
innodb_thread_concurrency = 0
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
innodb_file_per_table = 1

````
Start your MySQL server:
````
rm /var/lib/mysql/ib_logfile0
rm /var/lib/mysql/ib_logfile1
service mysql start
````

