#!/bin/bash

# Class importer for UPDATE.sh bash object emulation

MyTestModulePath="."
if [ "$1" != "" ]; then
    MyTestModulePath=$1
fi

MyTestModule(){
    . <(sed "s/MyTestModule/$1/g" ${MyTestModulePath}/MyTestModule.class.sh)
}
