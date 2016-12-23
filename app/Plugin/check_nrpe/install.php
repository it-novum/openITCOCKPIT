<?php
//Default packetmanager install.php
$this->exec('apt-get install --no-install-recommends -y -q nagios-nrpe-plugin');
$this->exec('ln -s /usr/lib/nagios/plugins/check_nrpe /opt/openitc/nagios/libexec/check_nrpe');
$this->exec('Done');