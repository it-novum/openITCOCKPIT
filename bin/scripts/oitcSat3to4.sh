#!/bin/bash

############### configuration

minFreeSpaceGB=5
betterFreeSpaceGB=7
enableLog=1
DEBIANCNF=/etc/mysql/debian.cnf

#ignore root rights and mysql configuration file
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
satellitesCount=0
satellitesEntries=""

. /etc/os-release

############# helping methods

if [[ "$(id -u)" != "0" && $testing == 0 ]]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi

debug_log(){
    if [ $enableLog == 1 ]; then
        echo "${Blue}${1}" | tr '___' ' '
    fi
}

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

check_mysql_table_sizes(){
    debug_log $(echo "Checking if any MySQL/MariaDB table is larger than free space is available on /var ..." | tr ' ' '___')

    hasError=0
    datadir=$(readlink -f $(mysql "--defaults-extra-file=$DEBIANCNF" -e 'show variables like "datadir"' -B | tail -n 1 | awk '{print $2}'))
    FREEinMb=`df --block-size=1M --output=avail "$datadir" | tail -n1`


    while read TABLE_NAME; do
        tableSizeInMb=$(mysql "--defaults-extra-file=$DEBIANCNF" -e "SELECT round(((data_length + index_length) / 1024 / 1024), 0) AS size FROM \`information_schema\`.\`TABLES\` WHERE \`TABLE_SCHEMA\`='${MYSQL_DATABASE}' AND \`TABLE_NAME\`='${TABLE_NAME}';" -B -s 2>/dev/null)

        if [[ "$FREEinMb" -lt "$tableSizeInMb" ]]; then
            errors+=($(echo "Table ${TABLE_NAME} is ${tableSizeInMb} MB but $datadir only has ${FREEinMb} MB free disk space." | tr ' ' '___'))
            ((errorCount++))
            hasError=1
        fi
    done< <(mysql --defaults-extra-file=${v3MysqlIni} --batch --skip-column-names -e "SELECT TABLE_NAME FROM \`information_schema\`.\`TABLES\` WHERE \`TABLE_SCHEMA\`='${MYSQL_DATABASE}';")

    if [[ "$hasError" -eq "0" ]]; then
        oks+=($(echo "Enough disk space available to convert tables to utf8mb4" | tr ' ' '___'))
        ((okCount++))
    fi

}

check_mysql_version(){
    debug_log $(echo "Checking MySQL/MariaDB version ..." | tr ' ' '___')
    mysqlVersion=$(mysql "--defaults-extra-file=$DEBIANCNF" -e "SELECT VERSION();" -B -s 2>/dev/null)
    rc=$?

    if [[ $rc -ne 0 ]]; then
      errors+=($(echo "Could not connect to MySQL/MariaDB database!" | tr ' ' '___'))
      ((errorCount++))
    else

      if [[ $mysqlVersion == *"MariaDB"* ]]; then
        mysqlVersion=$(echo $mysqlVersion | sed 's/[^0-9]*//g');
        mysqlVersion=$(echo $mysqlVersion | cut -c1-3);

        if [[ "$mysqlVersion" -lt "103" ]]; then
          if [ "$VERSION_CODENAME" == "stretch" ]; then
            oks+=($(echo "Your version of MariaDB gets upgraded by the upgrade from Debian Stretch to Debian Buster" | tr ' ' '___'))
            ((okCount++))
          else
            errors+=($(echo "openITCOCKPIT Version 4 requires at least MariaDB version 10.3" | tr ' ' '___'))
            ((errorCount++))
          fi
        else
          oks+=($(echo "MariaDB Version >= 10.3" | tr ' ' '___'))
          ((okCount++))
        fi

      else
        mysqlVersion=$(echo $mysqlVersion | sed 's/[^0-9]*//g');
        mysqlVersion=$(echo $mysqlVersion | cut -c1-2);

        if [[ "$mysqlVersion" -lt "57" ]]; then
          errors+=($(echo "openITCOCKPIT Version 4 requires at least MySQL version 5.7" | tr ' ' '___'))
          ((errorCount++))
        else
          oks+=($(echo "MySQL Version >= 5.7" | tr ' ' '___'))
          ((okCount++))
        fi
      fi
    fi
}

check_package_installed_sat_frontend(){
    if dpkg -s "openitcockpit-satellite-frontend" >/dev/null 2>&1; then
      echo ""
        #echo "mkdir -p /opt/openitc/etc/frontend && touch /opt/openitc/etc/frontend/enable_web_interface"
    else
        echo ""
        echo "# To enable the new Satellite Web Interface run these commands AFTER you have done the upgrade:"
        echo "mkdir -p /opt/openitc/etc/frontend && touch /opt/openitc/etc/frontend/enable_web_interface"
        echo "apt-get install openitcockpit-satellite-frontend"
    fi
}

print_logo(){
echo -e "\033[38;5;068m
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
    echo "                   openITCOCKPIT Version 4 Satellite checklist"
    echo ""
    echo -e "\033[0m"

}

################## run checks

print_logo

check_free_disk_space "/"
check_free_disk_space "/var"
check_free_disk_space "/opt"

check_aptget_working

if type "mysql" &> /dev/null; then
    check_mysql_table_sizes
    check_mysql_version
fi

if [ "$VERSION_CODENAME" == "stretch" ]; then

    openitcockpit_upd=$(apt-mark showmanual | grep openitcockpit | grep -v -e 'openitcockpit-statusengine3-oitc-mysql' -e 'openitcockpit-nagios-sat' -e 'openitcockpit-naemon-sat' -e 'openitcockpit-checkmk-sat' -e 'openitcockpit-statusengine3-broker-sat-nagios' -e 'openitcockpit-statusengine3-broker-sat-naemon' -e 'openitcockpit-statusengine-broker-sat-nagios' -e 'openitcockpit-statusengine-broker-sat-naemon' | xargs echo)
    openitcockpit_rem=$(while read pkg; do echo "$pkg-"; done< <(dpkg -l | awk '$2 ~ /openitcockpit-/ {print $2}' | grep -e 'openitcockpit-statusengine3-oitc-mysql' -e 'openitcockpit-nagios-sat' -e 'openitcockpit-naemon-sat' -e 'openitcockpit-checkmk-sat' -e 'openitcockpit-statusengine3-broker-sat-nagios' -e 'openitcockpit-statusengine3-broker-sat-naemon' -e 'openitcockpit-statusengine-broker-sat-nagios' -e 'openitcockpit-statusengine-broker-sat-naemon') | xargs echo)
    php_upd=$(while read pkg; do echo "$pkg-"; if [ "$pkg" != "php7.0-mcrypt" ]; then echo "$pkg"|sed 's/php7.0/php7.3/'; fi; done< <(dpkg -l | awk '$2 ~ /php7.0/ {print $2}') | xargs echo)
    always="openitcockpit-satellite"

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-checkmk-sat/')" ]; then
        always="$always openitcockpit-checkmk"
    fi

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-nagios-sat/')" ]; then
        always="$always openitcockpit-nagios"
    fi

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-naemon-sat/')" ]; then
        always="$always openitcockpit-naemon"
    fi

    if [ ! -z "$(dpkg -l | awk '$1 ~ /ii/ && $2 ~ /mariadb-server-10.1/')" ]; then
        always="$always mariadb-server-10.3 mariadb-client-10.3 mariadb-client-core-10.3 mariadb-server-core-10.3"
    fi

    echo "${BgBlack}${Cyan}"
    echo "###############################################################################"
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "We recommend a combined distribution and openITCOCKPIT upgrade"
    echo "You can run the upgrade with the following commands:"
    echo ""
    echo "# Update sources.list to Debian 10"
    echo "sed -i 's/stretch/buster/g' /etc/apt/sources.list"
    echo ""

    echo "echo 'deb https://packages.openitcockpit.io/openitcockpit/buster/stable buster main' > /etc/apt/sources.list.d/openitcockpit.list"
    echo "curl https://packages.openitcockpit.io/repokey.txt | apt-key add -"

    echo ""
    echo "# Upgrade the distribution and openITCOCKPIT"
    echo "apt-get update"
    if dpkg -s "openitcockpit-satellite-frontend" >/dev/null 2>&1; then
        echo "mkdir -p /opt/openitc/etc/frontend && touch /opt/openitc/etc/frontend/enable_web_interface"
    fi
    echo "apt-get dist-upgrade $php_upd $openitcockpit_upd $openitcockpit_rem $always"

    echo ""
    check_package_installed_sat_frontend
    echo ""
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "###############################################################################"
    echo "${Reset}"
fi

if [ "$VERSION_CODENAME" == "xenial" ]; then

    openitcockpit_upd=$(apt-mark showmanual | grep openitcockpit | grep -v -e 'openitcockpit-statusengine3-oitc-mysql' -e 'openitcockpit-nagios-sat' -e 'openitcockpit-naemon-sat' -e 'openitcockpit-checkmk-sat' -e 'openitcockpit-statusengine3-broker-sat-nagios' -e 'openitcockpit-statusengine3-broker-sat-naemon' -e 'openitcockpit-statusengine-broker-sat-nagios' -e 'openitcockpit-statusengine-broker-sat-naemon' | xargs echo)
    openitcockpit_rem=$(while read pkg; do echo "$pkg-"; done< <(dpkg -l | awk '$2 ~ /openitcockpit-/ {print $2}' | grep -e 'openitcockpit-statusengine3-oitc-mysql' -e 'openitcockpit-nagios-sat' -e 'openitcockpit-naemon-sat' -e 'openitcockpit-checkmk-sat' -e 'openitcockpit-statusengine3-broker-sat-nagios' -e 'openitcockpit-statusengine3-broker-sat-naemon' -e 'openitcockpit-statusengine-broker-sat-nagios' -e 'openitcockpit-statusengine-broker-sat-naemon') | xargs echo)
    php_upd=$(while read pkg; do echo "$pkg-"; if [ "$pkg" != "php7.0-mcrypt" ]; then echo "$pkg"|sed 's/php7.0/php7.2/'; fi; done< <(dpkg -l | awk '$2 ~ /php7.0/ {print $2}') | xargs echo)
    always="openitcockpit-satellite"

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-checkmk-sat/')" ]; then
        always="$always openitcockpit-checkmk"
    fi

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-nagios-sat/')" ]; then
        always="$always openitcockpit-nagios"
    fi

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-naemon-sat/')" ]; then
        always="$always openitcockpit-naemon"
    fi

    echo "${BgBlack}${Cyan}"
    echo "###############################################################################"
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "We recommend a combined distribution and openITCOCKPIT upgrade"
    echo "You can run the upgrade with the following commands:"
    echo ""
    echo "# Update sources.list to Ubuntu 18.04"
    echo "sed -i 's/xenial/bionic/g' /etc/apt/sources.list"
    echo ""

    echo "echo 'deb https://packages.openitcockpit.io/openitcockpit/bionic/stable bionic main' > /etc/apt/sources.list.d/openitcockpit.list"
    echo "curl https://packages.openitcockpit.io/repokey.txt | apt-key add -"

    echo ""
    echo "# Upgrade the distribution and openITCOCKPIT"
    echo "apt-get update"
    if dpkg -s "openitcockpit-satellite-frontend" >/dev/null 2>&1; then
        echo "mkdir -p /opt/openitc/etc/frontend && touch /opt/openitc/etc/frontend/enable_web_interface"
    fi
    echo "apt-get dist-upgrade $php_upd $openitcockpit_upd $openitcockpit_rem $always"

    echo ""
    check_package_installed_sat_frontend
    echo ""
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "###############################################################################"
    echo "${Reset}"
fi

if [ "$VERSION_CODENAME" == "bionic" ]; then

    openitcockpit_upd=$(apt-mark showmanual | grep openitcockpit | grep -v -e 'openitcockpit-statusengine3-oitc-mysql' -e 'openitcockpit-nagios-sat' -e 'openitcockpit-naemon-sat' -e 'openitcockpit-checkmk-sat' -e 'openitcockpit-statusengine3-broker-sat-nagios' -e 'openitcockpit-statusengine3-broker-sat-naemon' -e 'openitcockpit-statusengine-broker-sat-nagios' -e 'openitcockpit-statusengine-broker-sat-naemon' | xargs echo)
    openitcockpit_rem=$(while read pkg; do echo "$pkg-"; done< <(dpkg -l | awk '$2 ~ /openitcockpit-/ {print $2}' | grep -e 'openitcockpit-statusengine3-oitc-mysql' -e 'openitcockpit-nagios-sat' -e 'openitcockpit-naemon-sat' -e 'openitcockpit-checkmk-sat' -e 'openitcockpit-statusengine3-broker-sat-nagios' -e 'openitcockpit-statusengine3-broker-sat-naemon' -e 'openitcockpit-statusengine-broker-sat-nagios' -e 'openitcockpit-statusengine-broker-sat-naemon') | xargs echo)
    always="openitcockpit-satellite"

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-checkmk-sat/')" ]; then
        always="$always openitcockpit-checkmk"
    fi

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-nagios-sat/')" ]; then
        always="$always openitcockpit-nagios"
    fi

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-naemon-sat/')" ]; then
        always="$always openitcockpit-naemon"
    fi

    echo "${BgBlack}${Cyan}"
    echo "###############################################################################"
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "You can run the upgrade with the following commands:"
    echo ""

    echo "echo 'deb https://packages.openitcockpit.io/openitcockpit/bionic/stable bionic main' > /etc/apt/sources.list.d/openitcockpit.list"
    echo "curl https://packages.openitcockpit.io/repokey.txt | apt-key add -"

    echo ""
    echo "# Upgrade the distribution and openITCOCKPIT"
    echo "apt-get update"
    if dpkg -s "openitcockpit-satellite-frontend" >/dev/null 2>&1; then
        echo "mkdir -p /opt/openitc/etc/frontend && touch /opt/openitc/etc/frontend/enable_web_interface"
    fi
    echo "apt-get dist-upgrade $openitcockpit_upd $openitcockpit_rem $always"

    echo ""
    check_package_installed_sat_frontend
    echo ""
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "###############################################################################"
    echo "${Reset}"
fi

tput sgr0
