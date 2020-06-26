#!/bin/bash

# Class named 'system' for UPDATE.sh bash object emulation

# property
systemProperties=()

# properties IDs
lockDir=0
modulesDir=1

# do not change this method!
system.property(){
    if [ "$2" == "=" ]; then
        systemProperties[$1]=$3
    else
        echo ${systemProperties[$1]}
    fi
}

system.stdout.printValue(){
    echo $($@)
}
system.stdout.printString(){
    echo $@
}
system.isLocked(){
    result=0
    if [[ "$1" != "" && "$2" != "" ]]; then
        for entry in "$(system.property lockDir)"*
        do
            if [[ $entry =~ /${1}.${2}.lock$ ]]; then
                result=1
            fi
        done
    fi
    echo $result
}
system.lock(){
    if [[ "$1" != "" && "$2" != "" && "$3" != 1 ]]; then
        if [ ! -f $(system.property lockDir)${1}.${2}.lock ]; then
            touch $(system.property lockDir)${1}.${2}.lock
        fi
    fi
}
system.unlock(){
    if [[ "$1" != "" && "$2" != "" && "$3" != 1 ]]; then
        if [ -f $(system.property lockDir)${1}.${2}.lock ]; then
            rm $(system.property lockDir)${1}.${2}.lock
        fi
    fi
}
system.moduleSrcDirExists(){
    if [ "$1" != "" ]; then
        if [ -d "$(system.property modulesDir)${1}/src" ]; then
            echo 1
        else
            echo 0
        fi
    fi
}
system.initialize(){
    mkdir -p $(system.property lockDir)
}
