#!/bin/sh

bin/cake i18n extract --paths=src/,plugins/,config/ --merge=no --extract-core=no --output src/Locale/ --overwrite --no-location

# If only the POT-Creation-Date changed in the pot file, revert it
cd ..
result=`git diff --shortstat backend/src/Locale/default.pot`
if [[ $result == *"1 file changed, 1 insertion(+), 1 deletion(-)"* ]]
then
    git checkout backend/src/Locale/default.pot
fi
cd backend

bin/cake utility update_i18n update_from_catalog --overwrite --strip-references
bin/cake utility clear_cache
