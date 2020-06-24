#!/bin/bash

# Class importer for UPDATE.sh bash object emulation

NagiosModulePath="."
if [ "$1" != "" ]; then
    NagiosModulePath=$1
fi

NagiosModule(){
    . <(sed "s/NagiosModule/$1/g" ${NagiosModulePath}/NagiosModule.class.sh)
}
