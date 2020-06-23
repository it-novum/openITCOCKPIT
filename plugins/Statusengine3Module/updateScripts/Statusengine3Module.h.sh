#!/bin/bash

# Class importer for UPDATE.sh bash object emulation

Statusengine3ModulePath="."
if [ "$1" != "" ]; then
    Statusengine3ModulePath=$1
fi

Statusengine3Module(){
    . <(sed "s/Statusengine3Module/$1/g" ${Statusengine3ModulePath}/Statusengine3Module.class.sh)
}
