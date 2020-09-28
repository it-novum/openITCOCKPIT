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

if [[ ! -f "$v3MysqlIni" && $testing == 0 ]]; then
    echo "Error: Could not find openITCOCKPIT Version 3.x database configuration $v3MysqlIni"
    exit 1;
fi

MYSQL_DATABASE=$(php -r "echo parse_ini_file('/etc/openitcockpit/mysql.cnf', false , INI_SCANNER_RAW)['database'];")

LICENSE=$(mysql "--defaults-extra-file=$v3MysqlIni" -e "SELECT license FROM registers LIMIT 1;" -B -s 2>/dev/null)

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
        if [ $satellitesCount -gt 0 ]; then
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
    debug_log $(echo "Checking MySQL/MariaDB version ..." | tr ' ' '___')
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

check_mysql_table_sizes(){
    debug_log $(echo "Checking if any MySQL/MariaDB table is larger than free space is available on /var ..." | tr ' ' '___')

    hasError=0
    datadir=$(readlink -f $(mysql "--defaults-extra-file=$v3MysqlIni" -e 'show variables like "datadir"' -B | tail -n 1 | awk '{print $2}'))
    FREEinMb=`df --block-size=1M --output=avail "$datadir" | tail -n1`


    while read TABLE_NAME; do
        tableSizeInMb=$(mysql "--defaults-extra-file=$v3MysqlIni" -e "SELECT round(((data_length + index_length) / 1024 / 1024), 0) AS size FROM \`information_schema\`.\`TABLES\` WHERE \`TABLE_SCHEMA\`='${MYSQL_DATABASE}' AND \`TABLE_NAME\`='${TABLE_NAME}';" -B -s 2>/dev/null)

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

check_openitcockpit_version(){
    debug_log $(echo "Checking openITCOCKPIT version ..." | tr ' ' '___')


    if [[ -f "/usr/share/openitcockpit/app/Config/version.php" ]]; then
        $(php -r "require_once '/usr/share/openitcockpit/app/Config/version.php'; if(isset(\$config['version']) === false){ exit(1); }if(version_compare(\$config['version'], '3.7.3') >= 0){exit(0);}exit(1);")
        rc=$?

        if [[ $rc -ne 0 ]]; then
            errors+=($(echo "You version of openITCOCKPIT is < 3.7.3!" | tr ' ' '___'))
            ((errorCount++))
        else
            oks+=($(echo "Installed version of openITCOCKPIT >= 3.7.3" | tr ' ' '___'))
            ((okCount++))
        fi

    else
        errors+=($(echo "openITCOCKPIT 3.x version.php not found. Is openITCOCKPIT 3.x installed on this system?" | tr ' ' '___'))
        ((errorCount++))
    fi
}

check_for_administrator_user_role(){
    debug_log $(echo "Checking for openITCOCKPIT user role 'Administrator' ..." | tr ' ' '___')
    COUNT=$(mysql "--defaults-extra-file=$v3MysqlIni" -e "SELECT COUNT(*) FROM openitcockpit.usergroups WHERE name='Administrator';" -B -s 2>/dev/null)
    rc=$?

    if [ "$rc" != 0 -o "$COUNT" == 0 ] ; then
      errors+=($(echo "No user role 'Administrator' found! You have to create it via the openITCOCKPIT interface first!" | tr ' ' '___'))
      ((errorCount++))
    else
      oks+=($(echo "User group 'Administrator' found." | tr ' ' '___'))
      ((okCount++))
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
    echo "                   openITCOCKPIT Version 4 checklist"
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

check_aptget_working

check_mysql_version

check_for_administrator_user_role

if [ $isAptWorking == 1 ]; then
    debug_log $(echo "Checking installed openITCOCKPIT modules ..." | tr ' ' '___')
    check_package_installed_discovery
    check_package_installed_idoit
    check_package_installed_mk
    check_package_installed_distribute
fi

print_results

echo "${BgBlack}${Yellow}"
echo "#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! WARNING !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
echo "# Please ensure that you have a valid backup of the system BEFORE your continue!"
echo "${Reset}"
echo ""

if [ "$VERSION_CODENAME" == "stretch" ]; then

    openitcockpit_upd=$(apt-mark showmanual | grep openitcockpit | grep -v -e openitcockpit-message -e openitcockpit-statusengine-naemon -e openitcockpit-module-nrpe -e openitcockpit-module-mk | xargs echo)
    openitcockpit_rem=$(while read pkg; do echo "$pkg-"; done< <(dpkg -l | awk '$2 ~ /openitcockpit-/ {print $2} $2 ~ /phpnsta/ {print $2}' | grep -e 'openitcockpit-wkhtmltopdf' -e 'phpnsta') | xargs echo)
    php_upd=$(while read pkg; do echo "$pkg-"; if [ "$pkg" != "php7.0-mcrypt" ]; then echo "$pkg"|sed 's/php7.0/php7.3/'; fi; done< <(dpkg -l | awk '$2 ~ /php7.0/ {print $2}') | xargs echo)
    always="openitcockpit openitcockpit-graphing wkhtmltox"

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-module-distribute/')" ]; then
        always="$always openitcockpit-nsta"
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
    echo "# Add openITCOCKPIT 4 sources"

    if [ -z "$LICENSE" ] ; then
        echo "echo 'deb https://packages.openitcockpit.io/openitcockpit/buster/stable buster main' > /etc/apt/sources.list.d/openitcockpit.list"
    else
        echo "mkdir -p /etc/apt/auth.conf.d"
        echo "echo 'machine packages.openitcockpit.io login secret password ${LICENSE}' > /etc/apt/auth.conf.d/openitcockpit.conf"

        echo "echo 'deb https://packages.openitcockpit.io/openitcockpit/buster/stable buster main' > /etc/apt/sources.list.d/openitcockpit.list"
    fi

    echo "curl https://packages.openitcockpit.io/repokey.txt | apt-key add -"
    echo ""
    echo "# Upgrade the distribution and openITCOCKPIT"
    echo "apt-get update"
    echo "apt-get dist-upgrade $php_upd $openitcockpit_upd $openitcockpit_rem $always"
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "###############################################################################"
    echo "${Reset}"
fi

if [ "$VERSION_CODENAME" == "xenial" ]; then

    openitcockpit_upd=$(apt-mark showmanual | grep openitcockpit | grep -v -e openitcockpit-message -e openitcockpit-statusengine-naemon -e openitcockpit-module-nrpe -e openitcockpit-module-mk | xargs echo)
    openitcockpit_rem=$(while read pkg; do echo "$pkg-"; done< <(dpkg -l | awk '$2 ~ /openitcockpit-/ {print $2} $2 ~ /phpnsta/ {print $2}' | grep -e 'openitcockpit-wkhtmltopdf' -e 'phpnsta') | xargs echo)
    php_upd=$(while read pkg; do echo "$pkg-"; if [ "$pkg" != "php7.0-mcrypt" ]; then echo "$pkg"|sed 's/php7.0/php7.2/'; fi; done< <(dpkg -l | awk '$2 ~ /php7.0/ {print $2}') | xargs echo)
    always="openitcockpit openitcockpit-graphing wkhtmltox"

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-module-distribute/')" ]; then
        always="$always openitcockpit-nsta"
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
    echo "# Add openITCOCKPIT 4 sources"

    if [ -z "$LICENSE" ] ; then
        echo "echo 'deb https://packages.openitcockpit.io/openitcockpit/bionic/stable bionic main' > /etc/apt/sources.list.d/openitcockpit.list"
    else
        echo "mkdir -p /etc/apt/auth.conf.d"
        echo "echo 'machine packages.openitcockpit.io login secret password ${LICENSE}' > /etc/apt/auth.conf.d/openitcockpit.conf"

        echo "echo 'deb https://packages.openitcockpit.io/openitcockpit/bionic/stable bionic main' > /etc/apt/sources.list.d/openitcockpit.list"
    fi

    echo "curl https://packages.openitcockpit.io/repokey.txt | apt-key add -"
    echo ""
    echo "# Upgrade the distribution and openITCOCKPIT"
    echo "apt-get update"
    echo "apt-get dist-upgrade $php_upd $openitcockpit_upd $openitcockpit_rem $always"
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "###############################################################################"
    echo "${Reset}"
fi

if [ "$VERSION_CODENAME" == "bionic" ]; then

    openitcockpit_upd=$(apt-mark showmanual | grep openitcockpit | grep -v -e openitcockpit-message -e openitcockpit-statusengine-naemon -e openitcockpit-module-nrpe -e openitcockpit-module-mk | xargs echo)
    openitcockpit_rem=$(while read pkg; do echo "$pkg-"; done< <(dpkg -l | awk '$2 ~ /openitcockpit-/ {print $2} $2 ~ /phpnsta/ {print $2}' | grep -e 'openitcockpit-wkhtmltopdf' -e 'phpnsta') | xargs echo)
    always="openitcockpit openitcockpit-graphing wkhtmltox"

    if [ ! -z "$(dpkg -l | awk '$2 ~ /openitcockpit-module-distribute/')" ]; then
        always="$always openitcockpit-nsta"
    fi

    echo "${BgBlack}${Cyan}"
    echo "###############################################################################"
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "You can run the upgrade with the following commands:"
    echo ""
    echo "# Add openITCOCKPIT 4 sources"

    if [ -z "$LICENSE" ] ; then
        echo "echo 'deb https://packages.openitcockpit.io/openitcockpit/bionic/stable bionic main' > /etc/apt/sources.list.d/openitcockpit.list"
    else
        echo "mkdir -p /etc/apt/auth.conf.d"
        echo "echo 'machine packages.openitcockpit.io login secret password ${LICENSE}' > /etc/apt/auth.conf.d/openitcockpit.conf"

        echo "echo 'deb https://packages.openitcockpit.io/openitcockpit/bionic/stable bionic main' > /etc/apt/sources.list.d/openitcockpit.list"
    fi

    echo "curl https://packages.openitcockpit.io/repokey.txt | apt-key add -"
    echo ""
    echo "# Upgrade the distribution and openITCOCKPIT"
    echo "apt-get update"
    echo "apt-get dist-upgrade $openitcockpit_upd $openitcockpit_rem $always"
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
    echo "###############################################################################"
    echo "${Reset}"
fi

tput sgr0

