<?php
//Default packetmanager uninstall.php
$this->exec('apt-get purge -y nagios-nrpe-plugin');
$this->exec('rm -f /opt/openitc/nagios/libexec/check_nrpe');