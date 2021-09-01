#!/bin/bash
set -x
# Re-spawn as a background process, if we haven't already.
export MYSQL_PWD=nuserv-demo
echo 'im running' >running.txt
cd /var/www/html/mssql1
touch live.txt
live(){
    today="$(date '+%m-%d-%Y')"
    # today="$(date +%m-%d-%Y --date='1 day ago')"
    echo "Select TaskNumber From Task
        WHERE TaskNumber Like 'GBI%'
        AND Format(Task.DateCreated, 'MM-dd-yyyy', 'en-US') >= '${today}';" > today.sql
    if [ -f "today.csv" ]; then
        rm today.csv
        touch today.csv
    else
        touch today.csv
    fi
    sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "today.sql" -o "today.csv" -s"," -h-1
    n=`cat today.csv |wc -l`
    x=$((n-1))
    { echo "TaskNumber"; sed "$x,\$d" today.csv; } > todaynew.csv
    while IFS="," read -r TaskNumber
    do
        mysql -unuserv-demo -h 122.248.200.34 -D gbi -e "INSERT IGNORE INTO Ticket 
                (TaskNumber)
            VALUES
                ('$(echo ${TaskNumber})');"
    done < <(tail -n +2 todaynew.csv)
    check=$(mysql -unuserv-demo -h 122.248.200.34 -D gbi -e "Select count(TaskNumber) FROM Ticket WHERE TaskNumber LIKE 'GBI%' AND FormId IS NULL;" | sed -n 2p)
    if [ $check -gt 0 ] ;then
        ./updatetoday.sh
        echo "Database Updated"
        dt=$(date '+%d/%m/%Y %H:%M:%S');
        file=$(date '+%d-%m-%Y');
        echo "Database Updated at $dt" >> live.txt
        rm today.*
        sleep 1m
        rm nohup.out
        live
    else
        rm today.*
        echo 'No New Ticket'
        dt=$(date '+%d/%m/%Y %H:%M:%S');
        file=$(date '+%d-%m-%Y');
        echo "No new ticket found at $dt" > noticket.txt
        sleep 2m
        rm nohup.out
        live
    fi
}
live