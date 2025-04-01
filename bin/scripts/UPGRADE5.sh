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

if ! [ $(id -u) = 0 ]; then
    echo "You need to run this script as root user or via sudo!"
    exit 1
fi


INIFILE=/opt/openitc/etc/mysql/mysql.cnf

echo "Upgrade openITCOCKPIT 4.x to 5.x"

# ITC-3461

mysql --defaults-extra-file=${INIFILE} -e "TRUNCATE TABLE filter_bookmarks;"

echo ""
echo ""
echo "Upgrade to openITCOCKPIT 5 done."
echo ""


if [[ "${IS_CONTAINER:-}" == "1" ]]; then
    # Upgrade is running inside a container
    # use /opt/openitc/var/.upgrade5_done instead of /opt/openitc/etc/.upgrade5_done as /opt/openitc/etc is not persistent in the container
    date > /opt/openitc/var/.upgrade5_done
fi

date > /opt/openitc/etc/.upgrade5_done
