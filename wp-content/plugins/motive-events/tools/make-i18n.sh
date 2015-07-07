#!/bin/bash
export LC_ALL="C"

DIR=/var/virtuals/webmotive/wp-content/plugins/iworks-events
NAME=$(echo ${DIR}|tr '/' ' '|awk '{print $NF}'|tr '-' '_')
POT=${DIR}/languages/${NAME}.pot

if [ ! -d ${DIR}/languages ]; then
    mkdir -p ${DIR}/languages
fi

php -e ${HOME}/docs/wordpress/i18n/makepot.php wp-plugin ${DIR} ${POT}
TMP=`tempfile`
sed -e 's/FULL NAME <EMAIL@ADDRESS>/Marcin Pietrzak <marcin@iworks.pl>/' ${POT} > ${TMP}
cp ${TMP} ${POT}
sed -e 's/FIRST AUTHOR <EMAIL@ADDRESS>/Marcin Pietrzak <marcin@iworks.pl>/' ${POT} > ${TMP}
cp ${TMP} ${POT}
sed -e 's/LANGUAGE <LL@li.org>/Marcin Pietrzak <marcin@iworks.pl>/' ${POT} > ${TMP}
cp ${TMP} ${POT}
rm ${TMP}

cd ${DIR}/languages

for ELEMENT in $(ls -1 *.po|sed -e 's/\.po//')
do
    echo ${DIR}/languages/${ELEMENT}.po
    msgmerge -U ${ELEMENT}.po ${NAME}.pot
    msgfmt --statistics -v ${ELEMENT}.po -o ${ELEMENT}.mo
    echo
done

