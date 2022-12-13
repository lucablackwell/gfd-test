#!/bin/bash

source /root/bash-functions.sh
envcheck

DATABASEDIR="/mysql"

function runtimecheck() {
        #$1 - how long to wait in seconds
        #$2 - when we started waiting, usually a copy of $SECONDS at the start of the waiting time
        WAITTIME=$1
        TIMEMARKER=$2
        DELTA=$(( SECONDS - TIMEMARKER ))
        if [ "${DELTA}" -gt "${WAITTIME}" ]; then
                echo 1
        else
                echo 0
        fi
}

function pscheck() {
    OUT=$( ps -ef | grep -v grep | grep -E "$1" )
    RES=$?
    if [ "${RES}" -eq 0 ]; then
        #We got a match, now go get the pids
        echo $( ps -ef | grep -v grep | grep -E "$1" | awk '{print $2}' | tr '\n' ' ' )
    else
        echo ""
    fi
}

createdbuser() {
    #Create our user
    {
        echo "USE mysql;"
        echo "CREATE USER '${DATABASEUSER}'@'%' IDENTIFIED BY 'secret';"
        echo "GRANT ALL ON *.* TO '${DATABASEUSER}'@'%';"
        echo "FLUSH PRIVILEGES;"    
    } | mysql -A
}

createdefaultdatabase() {
    {
        echo "CREATE DATABASE ${DATABASENAME};"
        echo "GRANT ALL PRIVILEGES ON ${DATABASENAME}.* TO '${DATABASEUSER}'@'%';"
    } | mysql -A
}

createtestdatabase() {
    {
        echo "CREATE DATABASE IF NOT EXISTS ${DATABASENAME}_testing;"
        echo "GRANT ALL PRIVILEGES ON ${DATABASENAME}_testing.* TO '${DATABASEUSER}'@'%';"
    } | mysql -A
}

startMySQL() {
    #stop su complaining about /nonexistent
    usermod -d /tmp mysql 

    #
    echo "127.0.0.1 mysql" >> /etc/hosts

    #
    install -m 755 -o mysql -g root -d /var/run/mysqld
    echo "MySQL socket dir is:"
    ls -ld /var/run/mysqld
    bash -c 'nohup su - mysql -s /bin/sh -c "/usr/bin/mysqld_safe 2>&1 &"'

    #Wait for MySQL to wake up
    sleep 5 
}

################################################################


#TODO
#Ensure we have a drive present, expecting a bind mount
#if [ -f "${DATABASEDIR}/BIND-MARKER" ]; then 
#    echo "Bind mount present on ${DATABASEDIR}"
#else
#    echo "Bind mount missing (${DATABASEDIR}); abort."
#    exit 1
#fi

#Bind mount check
#Check bind mounts are in place
MOUNTSPRESENTANDCORRECT=1
for CHKDIR in ${DATABASEDIR}
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

#if [ "${DEBUGMODE}" -gt 0 ]; then 
#    SKIPBINDMOUNT=1
#fi
#
#if [[ ${MOUNTSPRESENTANDCORRECT} -ne 1 && ${SKIPBINDMOUNT} -ne 1 ]]; then
#    exit 1
#fi

#Remove old PID files
#for PIDFILE in $( ls ${DATABASEDIR}/*.pid )
#do
#    rm ${PIDFILE}
#done
echo "Removing stale PID files:"
find ${DATABASEDIR} -type f -name \*.pid -ls -delete

#Override where MySQL looks for databases (to our BIND mount)
#/etc/mysql/mysql.conf.d/mysqld.cnf:# datadir    = /var/lib/mysql
sed -r -i "s/(#\s?datadir\s?=\s?\/var\/lib\/mysql)/\1\ndatadir = \\${DATABASEDIR}/g" /etc/mysql/mysql.conf.d/mysqld.cnf

#If /mysql/mysql (dir, originally /var/lib/mysql/mysql) doesn't exist, we're starting from an empty dir; put what we need into place.
if [ ! -d ${DATABASEDIR}/mysql ]; then 
    echo "DB appears to be empty; setting up default."
    rsync -avP /var/lib/mysql/ ${DATABASEDIR}/
    chown -R mysql:mysql ${DATABASEDIR}
    startMySQL
    createdbuser
    createdefaultdatabase
    createtestdatabase
else
    echo "DB appears to be setup already; continuing."
    chown -R mysql:mysql ${DATABASEDIR}
    startMySQL
fi

#Loop until mysql dies or is killed 
#LOOPIT=1
#while [ ${LOOPIT} -eq 1 ]
#do
#    sleep 30
    #echo "MySQL alive check loop, started"
    ##P=$( ls -1rt ${DATABASEDIR}/*.pid | tail -1 )
    ##if [ ! -f ${P} ]; then LOOPIT=0 ; fi
    ##M=$( cat $P )
    ##C=$( ps -eF | sed -r -n "/^mysql\s+${M}/p" | wc -l ) 
    ##if [ "${C}" -eq 0 ]; then LOOPIT=0 ; fi
    #echo "MySQL alive check loop, finished, LOOPIT = ${LOOPIT}"
#    echo "Process list debug"
#    ps -ef
#done

RUN=1
PROCLIST="mysqld"
echo "Starting loop"
SLEEPTIME=30
TIMEMARKER=$SECONDS
while [ ${RUN} -eq 1 ]; do 
    for PROC in ${PROCLIST} 
    do
        P=$( pidof "${PROC}" )
        R=$?
        if [ $R -ne 0 ]; then
                echo "${PROC} not found; process list now"
                ps -eF
                echo "Aborting..."
                exit 1
        fi
        #mysqldump
        RES=$( runtimecheck 3600 ${TIMEMARKER} )
        if [ "${RES}" -eq 1 ]; then 
            TIMEMARKER=$SECONDS
            echo "Backing up database"
            /root/mysqlbackup.sh 
        fi
    done
    #echo "Processes (${PROCLIST}) running OK; sleeping ${SLEEPTIME}s"
    sleep ${SLEEPTIME}
done

cat /var/log/mysql/error.log