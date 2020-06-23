#!/bin/bash

# Class named 'Statusengine3Module' for UPDATE.sh bash object emulation

# property
properties=()

# properties IDs (only placeholder with incrementing array position value)
name=0
disableFileLock=1
dbIniFile=2
anOtherProperty=3

# predefined properties
properties[name]='Statusengine3Module'
# set 1 to disable file lock, this disables the uninstall() method and will run install() on every openitcockpit-update if the module folder exists
properties[disableFileLock]=1



# do not change this method!
Statusengine3Module.property(){
    if [ "$2" == "=" ]; then
        properties[$1]=$3
    else
        echo ${properties[$1]}
    fi
}

# runs on every openitcockpit-update
Statusengine3Module.initialize(){
    Statusengine3Module.installIfNeeded
}

Statusengine3Module.installIfNeeded(){
    if [ $(Statusengine3Module.property disableFileLock) == 1 ]; then
        if [ $(system.moduleSrcDirExists $(Statusengine3Module.property name)) == 1 ]; then
            Statusengine3Module.install
        fi
    else
        if [ $(system.moduleSrcDirExists $(Statusengine3Module.property name)) == 1 ]; then
            if [ "$(system.isLocked $(Statusengine3Module.property name) 'installed')" != 1 ]; then
                Statusengine3Module.install
            fi
        else
            if [ "$(system.isLocked $(Statusengine3Module.property name) 'installed')" == 1 ]; then
                Statusengine3Module.uninstall
            fi
        fi
    fi
}

Statusengine3Module.install(){
    # insert custom code
    system.lock $(Statusengine3Module.property name) "installed" $(Statusengine3Module.property disableFileLock)
}

Statusengine3Module.uninstall(){
    echo "I am the migrated postrm routine of $(Statusengine3Module.property name)"
    # insert custom code
    system.unlock $(Statusengine3Module.property name) "installed" $(Statusengine3Module.property disableFileLock)
}

Statusengine3Module.finish(){
    if [ $(system.moduleSrcDirExists $(Statusengine3Module.property name)) == 1 ]; then
        if systemctl is-active --quiet statusengine.service; then
            echo "Restart service: statusengine.service"
            systemctl restart statusengine.service
        fi
    fi
}
