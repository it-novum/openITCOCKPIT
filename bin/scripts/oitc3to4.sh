#!/bin/bash

############### configuration

minFreeSpaceGB=10
betterFreeSpaceGB=20
enableLog=1
v3MysqlIni="/etc/openitcockpit/mysql.cnf"

#irgnore root rights and mysql configuration file
testing=0

############## initialization

declare -a oks
declare -a warnings=()
declare -a errors=()
declare -a hints=()

okCount=0
hintCount=0
warningCount=0
errorCount=0

okSign=$'\U2713'
warningSign=$'\U26A0'
errorSign=$'\U2718'
hintSign='>'

Red=$'\e[1;31m'
Green=$'\e[1;32m'
Yellow=$'\e[1;33m'
Blue=$'\e[1;34m'

isAptWorking=0
satellitesCount=0
satellitesEntries=""

############# helping methods

if [[ "$(id -u)" != "0" && $testing == 0 ]]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi

if [[ ! -f "$v3MysqlIni" && $testing == 0 ]]; then
    echo "Error: Could not find openITCOCKPIT Version 3.x database configuration $v3MysqlIni"
    exit 1;
fi

debug_log(){
    if [ $enableLog == 1 ]; then
        echo "${Blue}${1}" | tr '___' ' '
    fi
}

print_results(){
    echo -e "\n\n${Green}Results:\n"

    for msg in ${oks[@]}; do
        echo "${Green}${okSign} ${msg}" | tr '___' ' '
    done

    for msg in ${hints[@]}; do
        echo "${Blue}${hintSign} ${msg}" | tr '___' ' '
    done

    for msg in ${warnings[@]}; do
        echo "${Yellow}${warningSign} ${msg}" | tr '___' ' '
    done

    for msg in ${errors[@]}; do
        echo "${Red}${errorSign} ${msg}" | tr '___' ' '
    done

    echo -e "\n${Green}Oks: $okCount | ${Blue}Hints: $hintCount | ${Yellow}Warnings: $warningCount | ${Red}Errors: $errorCount"
}

get_configured_satellites(){
    satellitesCount=$(mysql "--defaults-extra-file=$v3MysqlIni" -e "SELECT COUNT(*) FROM satellites;" -B -s 2>/dev/null)
    satellitesEntries=$(mysql "--defaults-extra-file=$v3MysqlIni" -e "SELECT name, '----', address, ';;' FROM satellites;" -B -s 2>/dev/null)
}

########### check definitions

check_free_disk_space(){
    pathToCheckForFreeSpace=$1
    debug_log $(echo "Checking free disk space of: ${pathToCheckForFreeSpace} ..." | tr ' ' '___')
    FREE=`df -k --output=avail "$pathToCheckForFreeSpace" | tail -n1`

    minFreeSpaceByte=$(expr $minFreeSpaceGB \* 1024 \* 1024);
    betterFreeSpaceByte=$(expr $betterFreeSpaceGB \* 1024 \* 1024);
    freeGb=$(expr $FREE / 1024 / 1024);
    betterFreeSpaceGBMsg=$(expr $betterFreeSpaceGB - $freeGb);

    if [[ $FREE -lt $minFreeSpaceByte ]]; then
        # less than (minimum) required space is free!
        errors+=($(echo "Not enough free space: less than ${minFreeSpaceGB} GB free on ${pathToCheckForFreeSpace} !" | tr ' ' '___'))
        ((errorCount++))
    elif [[ $FREE -lt $betterFreeSpaceByte ]]; then
        # less than (better) required space is free!
        warnings+=($(echo "Free space of ${freeGb} GB could be enough, but ${betterFreeSpaceGBMsg} GB more on ${pathToCheckForFreeSpace} would be better!" | tr ' ' '___'))
        ((warningCount++))
    else
        oks+=($(echo "More than ${betterFreeSpaceGB} GB free space on ${pathToCheckForFreeSpace}" | tr ' ' '___'))
        ((okCount++))
    fi
}

check_aptget_working(){
    debug_log $(echo "Checking apt-get ..." | tr ' ' '___')
    aptResult=$( (apt-get update >/dev/null) 2>&1)
    if [ $? != 0 ]; then
        errors+=($(echo "'apt-get update' may not work properly:" | tr ' ' '___'))
        errors+=($(echo "$aptResult" | tr ' ' '___'))
        ((errorCount++))
    else
        oks+=($(echo "'apt-get update' is working fine" | tr ' ' '___'))
        isAptWorking=1
        ((okCount++))
    fi
}

check_package_installed_discovery(){
    if dpkg -s "openitcockpit-module-discovery" >/dev/null 2>&1; then
        warnings+=($(echo "The DiscoveryModule (openitcockpit-module-discovery) is no longer available in openITCOCKPIT Version 4 and will be removed!" | tr ' ' '___'))
        ((warningCount++))
    fi
}

check_package_installed_idoit(){
    if dpkg -s "openitcockpit-module-idoit" >/dev/null 2>&1; then
        warnings+=($(echo "The IdoitModule (openitcockpit-module-idoit) is not yet available in openITCOCKPIT Version 4 and will be removed!" | tr ' ' '___'))
        warnings+=($(echo "Make sure you don't have i-doit hosts configured!" | tr ' ' '___'))
        ((warningCount++))
    fi
}

check_package_installed_mk(){
    if dpkg -s "openitcockpit-module-mk" >/dev/null 2>&1; then
        hints+=($(echo "Checkmk will be upgraded to version 1.6.0. Please consider updating your client agents. (not required)" | tr ' ' '___'))
        ((hintCount++))
    fi
}

check_package_installed_distribute(){
    if dpkg -s "openitcockpit-module-distribute" >/dev/null 2>&1; then
        get_configured_satellites
        if [ $satellitesCount > 0 ]; then
            warnings+=($(echo "Please keep in mind to upgrade all Satellite systems to openITCOCKPIT Version 4." | tr ' ' '___'))
            ((warningCount++))
            if [ "$satellitesEntries" != "" ]; then
                satellitesEntries=$(echo ${satellitesEntries/"\n"/""} | xargs)
                while IFS=';;' read -ra Satellites; do
                    for Satellite in "${Satellites[@]}"; do
                        if [ "$Satellite" != "" ]; then
                            name=$(echo "${Satellite%%" ---- "*}" | xargs)
                            addr=$(echo ${Satellite/"${name} ----"/""} | xargs)
                            warnings+=($(echo " - '${name}' at ${addr}" | tr ' ' '___'))
                        fi
                    done
                done <<< "$satellitesEntries"
            fi
            hints+=($(echo "openITCOCKPIT Version 4 provide a new Satellite interface. Consider installing the satellite extension." | tr ' ' '___'))
            ((hintCount++))
        fi
    fi
}

check_mysql_version(){
    mysqlVersion=$(mysql "--defaults-extra-file=$v3MysqlIni" -e "SELECT VERSION();" -B -s 2>/dev/null)
    rc=$?

    if [[ $rc -ne 0 ]]; then
      errors+=($(echo "Could not connect to MySQL/MariaDB database!" | tr ' ' '___'))
      ((errorCount++))
    else

      if [[ $mysqlVersion == *"MariaDB"* ]]; then
        mysqlVersion=$(echo $mysqlVersion | sed 's/[^0-9]*//g');
        mysqlVersion=$(echo $mysqlVersion | cut -c1-3);

        if [[ "$mysqlVersion" -lt "103" ]]; then
          errors+=($(echo "openITCOCKPIT Version 4 requires at least MariaDB version 10.3" | tr ' ' '___'))
          ((errorCount++))
        else
          oks+=($(echo "'MariaDB Version >= 10.3" | tr ' ' '___'))
          ((okCount++))
        fi

      else
        mysqlVersion=$(echo $mysqlVersion | sed 's/[^0-9]*//g');
        mysqlVersion=$(echo $mysqlVersion | cut -c1-2);

        if [[ "$mysqlVersion" -lt "57" ]]; then
          errors+=($(echo "openITCOCKPIT Version 4 requires at least MySQL version 5.7" | tr ' ' '___'))
          ((errorCount++))
        else
          oks+=($(echo "'MariaDB Version >= 5.7" | tr ' ' '___'))
          ((okCount++))
        fi
      fi
    fi

}

################## run checks

check_free_disk_space "/"
check_free_disk_space "/var"
check_free_disk_space "/opt"

check_aptget_working

if [ $isAptWorking == 1 ]; then
    debug_log $(echo "Checking installed openITCOCKPIT modules ..." | tr ' ' '___')
    check_package_installed_discovery
    check_package_installed_idoit
    check_package_installed_mk
    check_package_installed_distribute
fi

print_results
tput sgr0

