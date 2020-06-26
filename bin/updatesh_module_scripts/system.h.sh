#!/bin/bash

# Global system class importer for UPDATE.sh bash object emulation

path=.
if [ "$1" != "" ]; then
    path=$1
fi
. ${path}/system.class.sh
