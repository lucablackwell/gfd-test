#!/bin/bash 

#Check bind mounts are in place
MOUNTSPRESENTANDCORRECT=1
for CHKDIR in /mysqldump
do 
    MOUNTED=$( grep -E -v "(\/proc|\/etc)|^(devpts|tmpfs|cgroup|sysfs|mqueue|shm|overlay)" /etc/mtab )
    echo "${MOUNTED}" | grep -E "${CHKDIR}"
    RES=$?
    if [ "${RES}" -eq 0 ]; then
        echo "Bind mount present on ${CHKDIR}"
    else
        echo "Bind mount missing (${CHKDIR})"
        MOUNTSPRESENTANDCORRECT=0
    fi
done

if [ ${MOUNTSPRESENTANDCORRECT} -ne 1 ]; then
    exit 1
fi

if [ -z "${DATABASENAME}" ]; then 
    echo "\${DATABASENAME} isn't set; this is bad.  Really bad."
    exit 1
fi

ISSUES=0
for DBTODUMP in ${DATABASENAME} ${DATABASENAME}_testing
do
    TSTAMP=$( date "+%Y%m%d-%H%M%S" )
    TARGET=/mysqldump/dump_${TSTAMP}_${DBTODUMP}.sql
    mysqldump "${DBTODUMP}" > ${TARGET}
    RES=$?
    if [ "${RES}" -eq 0 ]; then 
        echo "${DBTODUMP} dump successful --> ${TARGET}"
        gzip -v9 ${TARGET}
        RES=$?
        if [ "${RES}" -eq 0 ]; then 
            echo "${DBTODUMP} compressed ${TARGET}"
        else
            echo "${DBTODUMP} error ${RES} compressing ${TARGET}"
            (( ISSUES = $ISSUES + 1))
        fi
    else
        echo "Error dumping ${DBTODUMP}"
        (( ISSUES = $ISSUES + 1))
    fi
done

if [ "${ISSUES}" -gt 0 ]; then 
    echo "WARNING: ${ISSUES} during backup."
fi



exit 0
