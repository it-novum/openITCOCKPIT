#!/bin/bash
#
# Copyright (C) <2015-present>  <it-novum GmbH>
#
# This file is dual licensed
#
# 1.
#     This program is free software: you can redistribute it and/or modify
#     it under the terms of the GNU General Public License as published by
#     the Free Software Foundation, version 3 of the License.
#
#     This program is distributed in the hope that it will be useful,
#     but WITHOUT ANY WARRANTY; without even the implied warranty of
#     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#     GNU General Public License for more details.
#
#     You should have received a copy of the GNU General Public License
#     along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# 2.
#     If you purchased an openITCOCKPIT Enterprise Edition you can use this file
#     under the terms of the openITCOCKPIT Enterprise Edition license agreement.
#     License agreement and license key will be shipped with the order
#     confirmation.
#


############### configuration

minFreeSpaceGB=10
betterFreeSpaceGB=20
enableLog=1

INIFILE=/opt/openitc/etc/mysql/mysql.cnf

IS_RHEL=0
if [ -e "/etc/redhat-release" ]; then
    IS_RHEL=1
fi

#irgnore root rights and mysql configuration file
testing=0

############## initialization

declare -a oks
declare -a warnings
declare -a errors
declare -a hints

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
Cyan=$'\e[1;96m'
BgBlack=$'\e[40m';
Reset=$'\e[0m';

isAptWorking=0
isDnfWorking=0

. /etc/os-release

############# helping methods

if [[ "$(id -u)" != "0" && $testing == 0 ]]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi

if [[ ! -f "$INIFILE" && $testing == 0 ]]; then
    echo "Error: Could not find openITCOCKPIT Version 4.x database configuration $INIFILE"
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

    echo -e "\n${Green}Oks: $okCount | ${Blue}Hints: $hintCount | ${BgBlack}${Yellow}Warnings: ${warningCount}${Reset} | ${Red}Errors: $errorCount"
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

check_dnf_working(){
    debug_log $(echo "Checking dnf check-update ..." | tr ' ' '___')
    dnfResult=$( (dnf --refresh check-update >/dev/null) 2>&1)
    if [[ $dnfResult -eq 0 || $dnfResult -eq 100 ]]; then
        oks+=($(echo "'dnf --refresh check-update' is working fine" | tr ' ' '___'))
        isDnfWorking=1
        ((okCount++))
    else
        errors+=($(echo "'dnf --refresh check-update' may not work properly:" | tr ' ' '___'))
        errors+=($(echo "dnfResult" | tr ' ' '___'))
        ((errorCount++))
    fi
}

check_mysql_version(){
    debug_log $(echo "Checking MySQL/MariaDB version ..." | tr ' ' '___')
    mysqlVersion=$(mysql "--defaults-extra-file=$INIFILE" -e "SELECT VERSION();" -B -s 2>/dev/null)
    rc=$?

    if [[ $rc -ne 0 ]]; then
      errors+=($(echo "Could not connect to MySQL/MariaDB database!" | tr ' ' '___'))
      ((errorCount++))
    else

      if [[ $mysqlVersion == *"MariaDB"* ]]; then
        mysqlVersion=$(echo $mysqlVersion | sed 's/[^0-9]*//g');
        mysqlVersion=$(echo $mysqlVersion | cut -c1-4);

        if [[ "$mysqlVersion" -lt "1011" ]]; then
            errors+=($(echo "openITCOCKPIT Version 5 requires at least MariaDB version 10.11" | tr ' ' '___'))
            ((errorCount++))
        else
          oks+=($(echo "MariaDB Version >= 10.11" | tr ' ' '___'))
          ((okCount++))
        fi

      else
        mysqlVersion=$(echo $mysqlVersion | sed 's/[^0-9]*//g');
        mysqlVersion=$(echo $mysqlVersion | cut -c1-2);

        if [[ "$mysqlVersion" -lt "80" ]]; then
          errors+=($(echo "openITCOCKPIT Version 5 requires at least MySQL version 8.0" | tr ' ' '___'))
          ((errorCount++))
        else
          oks+=($(echo "MySQL Version >= 8.0" | tr ' ' '___'))
          ((okCount++))
        fi
      fi
    fi
}

check_mysql_table_sizes(){
    debug_log $(echo "Checking if any MySQL/MariaDB table is larger than free space is available on /var ..." | tr ' ' '___')

    hasError=0
    datadir=$(readlink -f $(mysql "--defaults-extra-file=$INIFILE" -e 'show variables like "datadir"' -B | tail -n 1 | awk '{print $2}'))
    FREEinMb=`df --block-size=1M --output=avail "$datadir" | tail -n1`


    while read TABLE_NAME; do
        tableSizeInMb=$(mysql "--defaults-extra-file=$INIFILE" -e "SELECT round(((data_length + index_length) / 1024 / 1024), 0) AS size FROM \`information_schema\`.\`TABLES\` WHERE \`TABLE_SCHEMA\`='${MYSQL_DATABASE}' AND \`TABLE_NAME\`='${TABLE_NAME}';" -B -s 2>/dev/null)

        if [[ "$FREEinMb" -lt "$tableSizeInMb" ]]; then
            errors+=($(echo "Table ${TABLE_NAME} is ${tableSizeInMb} MB but $datadir only has ${FREEinMb} MB free disk space." | tr ' ' '___'))
            ((errorCount++))
            hasError=1
        fi
    done< <(mysql --defaults-extra-file=${INIFILE} --batch --skip-column-names -e "SELECT TABLE_NAME FROM \`information_schema\`.\`TABLES\` WHERE \`TABLE_SCHEMA\`='${MYSQL_DATABASE}';")

    if [[ "$hasError" -eq "0" ]]; then
        oks+=($(echo "Enough disk space available to convert tables to utf8mb4_0900_ai_ci (MySQL) or utf8mb4 (MariaDB)" | tr ' ' '___'))
        ((okCount++))
    fi

}

check_openitcockpit_version(){
    debug_log $(echo "Checking openITCOCKPIT version ..." | tr ' ' '___')


    if [[ -f "/opt/openitc/frontend/config/version.php" ]]; then
        $(php -r "require_once '/opt/openitc/frontend/config/version.php'; if(defined('OPENITCOCKPIT_VERSION') === false){ exit(1); }if(version_compare(OPENITCOCKPIT_VERSION, '4.8.7') >= 0){exit(0);}exit(1);")
        rc=$?

        if [[ $rc -ne 0 ]]; then
            errors+=($(echo "You version of openITCOCKPIT is < 4.8.7!" | tr ' ' '___'))
            ((errorCount++))
        else
            oks+=($(echo "Installed version of openITCOCKPIT >= 4.8.7" | tr ' ' '___'))
            ((okCount++))
        fi

    else
        errors+=($(echo "openITCOCKPIT 4.x version.php not found. Is openITCOCKPIT 4.x installed on this system?" | tr ' ' '___'))
        ((errorCount++))
    fi
}

check_for_administrator_user_role(){
    debug_log $(echo "Checking for openITCOCKPIT user role 'Administrator' ..." | tr ' ' '___')
    COUNT=$(mysql "--defaults-extra-file=$INIFILE" -e "SELECT COUNT(*) FROM openitcockpit.usergroups WHERE name='Administrator';" -B -s 2>/dev/null)
    rc=$?

    if [ "$rc" != 0 -o "$COUNT" == 0 ] ; then
      errors+=($(echo "No user role 'Administrator' found! You have to create it via the openITCOCKPIT interface first!" | tr ' ' '___'))
      ((errorCount++))
    else
      oks+=($(echo "User group 'Administrator' found." | tr ' ' '___'))
      ((okCount++))
    fi
}

check_is_supported_debian_version(){
    debug_log $(echo "Checking if Debian Version is supported by openITCOCKPIT ..." | tr ' ' '___')
    if [[ -n "$VERSION_CODENAME" && ( "$VERSION_CODENAME" == "bookworm" || "$VERSION_CODENAME" == "noble" || "$VERSION_CODENAME" == "jammy" ) ]]; then
        oks+=($(echo "Debian/Ubuntu Version is supported by openITCOCKPIT 5." | tr ' ' '___'))
        ((okCount++))
    else
        echo "Debian/Ubuntu Version $VERSION_CODENAME is NOT supported by openITCOCKPIT 5."
        errors+=($(echo "Debian/Ubuntu Version is NOT supported by openITCOCKPIT 5." | tr ' ' '___'))
        errors+=($(echo "Please upgrade your operating system first." | tr ' ' '___'))
        ((errorCount++))
    fi
}

check_is_supported_rhel_version(){
    debug_log $(echo "Checking if RHEL Version is supported by openITCOCKPIT ..." | tr ' ' '___')
    if [[ -n "$PLATFORM_ID" && ( "$PLATFORM_ID" == "platform:el8" || "$PLATFORM_ID" == "platform:el9" ) ]]; then
        oks+=($(echo "RHEL Version is supported by openITCOCKPIT 5." | tr ' ' '___'))
        ((okCount++))
    else
        echo "RHEL Version $PLATFORM_ID is NOT supported by openITCOCKPIT 5."
        errors+=($(echo "RHEL Version is NOT supported by openITCOCKPIT 5." | tr ' ' '___'))
        errors+=($(echo "Please upgrade your operating system first." | tr ' ' '___'))
        ((errorCount++))
    fi
}

print_logo(){
echo -e "\033[38;5;093m
                                        ///
                              ///////////////////////
                          ///////////////////////////////
                        ///////////////////////////////////
                     /////////////////////////////////////////
                    ///////////////////////////////////////////
                   ////////////////   .////////////////////    ,
                  //////////                  //////             .
                 ////////         //////        /         ///////.
                 ///////       ///////////.   /////// ////////////
                ///////       /////////////      /   /////////////
                ///////       /////////////*  /* /   /////////////
                ///////       /////////////   /*     /////////////
                ////////        //////////    /*       //////////
                //////////                     //
                /////////////               ////////
                /////////////////////////////////////////////
                ///////////////////////////////////////////
                ////////////////////////////////////////
                ////////////////////////////////////"

    echo ""
    echo "                   openITCOCKPIT Version 5 checklist"
    echo ""
    echo -e "\033[0m"

}

################## run checks

print_logo

check_openitcockpit_version

check_free_disk_space "/"
check_free_disk_space "/var"
check_free_disk_space "/opt"

check_mysql_table_sizes

if [[ $IS_RHEL -eq 1 ]]; then
    echo "Running RedHat based system specific checks."
    check_dnf_working
    check_is_supported_rhel_version
else
    echo "Running Debian based system specific checks."
    check_aptget_working
    check_is_supported_debian_version
fi

check_mysql_version

check_for_administrator_user_role

print_results

echo "${BgBlack}${Yellow}"
echo "#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! WARNING !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
echo "# Please ensure that you have a valid backup of the system BEFORE your continue!"
echo "${Reset}"
echo ""


tput sgr0

