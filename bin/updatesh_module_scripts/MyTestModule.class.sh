#!/bin/bash

# Class named 'MyTestModule' for UPDATE.sh bash object emulation

# property
properties=()

# properties IDs (only placeholder with incrementing array position value)
name=0
disableFileLock=1
dbIniFile=2
anOtherProperty=3

# predefined properties
properties[name]='MyTestModule'
# set 1 to disable file lock, this disables the uninstall() method and will run install() on every openitcockpit-update if the module folder exists
properties[disableFileLock]=1



# do not change this method!
MyTestModule.property(){
    if [ "$2" == "=" ]; then
        properties[$1]=$3
    else
        echo ${properties[$1]}
    fi
}

# runs on every openitcockpit-update
MyTestModule.initialize(){
    MyTestModule.installIfNeeded
}

MyTestModule.installIfNeeded(){
    if [ $(MyTestModule.property disableFileLock) == 1 ]; then
        if [ $(system.moduleSrcDirExists $(MyTestModule.property name)) == 1 ]; then
            MyTestModule.install
        fi
    else
        if [ $(system.moduleSrcDirExists $(MyTestModule.property name)) == 1 ]; then
            if [ "$(system.isLocked $(MyTestModule.property name) 'installed')" != 1 ]; then
                MyTestModule.install
            fi
        else
            if [ "$(system.isLocked $(MyTestModule.property name) 'installed')" == 1 ]; then
                MyTestModule.uninstall
            fi
        fi
    fi
}

MyTestModule.install(){
    echo "I am the migrated postinstall routine of $(MyTestModule.property name)"
    # insert custom code
    system.lock $(MyTestModule.property name) "installed" $(MyTestModule.property disableFileLock)
}

MyTestModule.uninstall(){
    echo "I am the migrated postrm routine of $(MyTestModule.property name)"
    # insert custom code
    system.unlock $(MyTestModule.property name) "installed" $(MyTestModule.property disableFileLock)
}

MyTestModule.finish(){
    if [ $(system.moduleSrcDirExists $(MyTestModule.property name)) == 1 ]; then
        # insert custom code
    fi
}
