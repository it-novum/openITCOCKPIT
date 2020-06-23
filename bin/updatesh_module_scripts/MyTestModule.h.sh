#!/bin/bash

# Class importer for UPDATE.sh bash object emulation

path="."
if [ "$1" != "" ]; then
    path=$1
fi

MyTestModule(){
    . <(sed "s/MyTestModule/$1/g" ${path}/MyTestModule.class.sh)
}
