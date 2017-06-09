#!/bin/bash

# Licensed to CRATE Technology GmbH ("Crate") under one or more contributor
# license agreements.  See the NOTICE file distributed with this work for
# additional information regarding copyright ownership.  Crate licenses
# this file to you under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.  You may
# obtain a copy of the License at
#
#   http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
# WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the
# License for the specific language governing permissions and limitations
# under the License.
#
# However, if you have executed another commercial license agreement
# with Crate these terms will supersede the license and you may use the
# software solely pursuant to the terms of the relevant commercial agreement.

function print_green() {
  echo -e "\033[2;32m$1\033[0m"
}
function print_red() {
  echo -e "\033[1;31m$1\033[0m"
}

# check if everything is committed
CLEAN=`git status -s`
if [ ! -z "$CLEAN" ]
then
   print_red "Working directory not clean. Please commit all changes before tagging!"
   echo "Aborting."
   exit 1
fi

echo "Fetching origin..."
git fetch origin > /dev/null

# get current branc
BRANCH=`git branch | grep "^*" | cut -d " " -f 2`
echo "Current branch is $BRANCH."

# check if BRANCH == origin/BRANCH
LOCAL_COMMIT=$(git log --pretty=oneline -n 1 ${BRANCH} | cat)
ORIGIN_COMMIT=$(git log --pretty=oneline -n 1 origin/${BRANCH} | cat)

if [ "$LOCAL_COMMIT" != "$ORIGIN_COMMIT" ]
then
   print_red "Local $BRANCH is not up to date."
   echo "Local commit:  $LOCAL_COMMIT"
   echo "Origin commit: $ORIGIN_COMMIT"
   echo "Aborting."
   exit 1
fi

# get version from PDO class
VERSION=$(grep "VERSION =" src/Crate/PDO/PDO.php | tr -d ';' | cut -d' ' -f8 | tr -d "'")
print_green "Version: $VERSION"

# check if tag to create has already been created
EXISTS=$(git tag | grep $VERSION)

if [ "$VERSION" == "$EXISTS" ]
then
   print_red "Revision $VERSION already tagged."
   echo "Aborting."
   exit 1
fi

# check if VERSION is in head of CHANGES.txt
REV_NOTE=`grep "[0-9/]\{10\} $VERSION" CHANGES.txt`
if [ -z "$REV_NOTE" ]
then
    print_red "No notes for revision $VERSION found in CHANGES.txt"
    echo "Aborting."
    exit 1
fi

# check if VERSION is in docs/installation.rst
INST_DEP=`grep "crate/crate-pdo:~$VERSION" docs/installation.rst`
if [ -z "$INST_DEP"]
then
    print_red "Version $VERSION not updated in docs/installation.rst"
    echo "Aborting."
    exit 1
fi

# create and push tag
print_green "Creating tag $VERSION ..."
git tag -a "$VERSION" -m "Crate PDO $VERSION"
git push --tags
print_green "Done"

