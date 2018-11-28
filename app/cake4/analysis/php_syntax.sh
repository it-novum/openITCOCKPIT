#!/bin/bash

# trace ERR through pipes
set -o pipefail

# trace ERR through 'time command' and other functions
set -o errtrace

# set -u : exit the script if you try to use an uninitialised variable
set -o nounset

# set -e : exit the script if any statement returns a non-true return value
set -o errexit

error=false

while test $# -gt 0; do
    current=$1
    shift

    if [ ! -d $current ] && [ ! -f $current ] ; then
        echo "Invalid directory or file: $current"
        error=true

        continue
    fi

    while IFS= read -r -d '' file
    do
        EXTENSION="${file##*.}"

        if [ "$EXTENSION" == "php" ] || [ "$EXTENSION" == "ctp" ]
        then
            RESULTS=`php -l $file`

            if [ "$RESULTS" != "No syntax errors detected in $file" ]
            then
                echo $RESULTS
                error=true
            fi
        fi
    done <   <(find $current -print0)
done

if [ "$error" = true ] ; then
    exit 1
else
    exit 0
fi
