#!/bin/bash

# Class named 'NagiosModule' for UPDATE.sh bash object emulation

# property
properties=()

# properties IDs (only placeholder with incrementing array position value)
name=0
disableFileLock=1
dbIniFile=2
anOtherProperty=3

# predefined properties
properties[name]='NagiosModule'
# set 1 to disable file lock, this disables the uninstall() method and will run install() on every openitcockpit-update if the module folder exists
properties[disableFileLock]=0

# do not change this method!
NagiosModule.property() {
    if [ "$2" == "=" ]; then
        properties[$1]=$3
    else
        echo ${properties[$1]}
    fi
}

# runs on every openitcockpit-update
NagiosModule.initialize() {
    NagiosModule.installIfNeeded
}

NagiosModule.installIfNeeded() {
    echo "Run $(NagiosModule.property name) update"
    chown nagios:www-data -R /opt/openitc/nagios/etc
    chown nagios:www-data -R /opt/openitc/nagios/var
    chown nagios:www-data -R /opt/openitc/nagios/share
    system.lock $(NagiosModule.property name) "installed" $(NagiosModule.property disableFileLock)

    if [ $(NagiosModule.property disableFileLock) == 1 ]; then
        if [ $(system.moduleSrcDirExists $(NagiosModule.property name)) == 1 ]; then
            NagiosModule.install
        fi
    else
        if [ $(system.moduleSrcDirExists $(NagiosModule.property name)) == 1 ]; then
            if [ "$(system.isLocked $(NagiosModule.property name) 'installed')" != 1 ]; then
                NagiosModule.install
            fi
        else
            if [ "$(system.isLocked $(NagiosModule.property name) 'installed')" == 1 ]; then
                NagiosModule.uninstall
            fi
        fi
    fi
}

NagiosModule.install() {
    if [ -f "/opt/openitc/etc/nagios/nagios.cfg" ]; then
        echo "Start service: nagios.service"
        systemctl start nagios.service
    fi
}

NagiosModule.uninstall() {
    echo "I am the migrated postrm routine of $(NagiosModule.property name)"
    # insert custom code
    system.unlock $(NagiosModule.property name) "installed" $(NagiosModule.property disableFileLock)
}

NagiosModule.finish() {
    if [ $(system.moduleSrcDirExists $(NagiosModule.property name)) == 1 ]; then
        # Nagios/Naemon is aliased, so it works for both
        systemctl daemon-reload
        systemctl enable nagios.service
        if [ -f "/opt/openitc/etc/nagios/nagios.cfg" ]; then
            if systemctl is-active --quiet nagios.service; then
                echo "Restart service: nagios.service"
                systemctl restart nagios.service
            fi
        fi
    fi
}
