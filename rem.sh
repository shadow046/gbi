#!/bin/bash
set -x
export MYSQL_PWD=nuserv-demo
get_data(){
    # get = $(date +%Y-%m-%d --date='100 day ago')
    # DAYS="100"
    
    dt=$(date '+%d/%m/%Y %H:%M:%S');
    file=$(date '+%d-%m-%Y');
    onehundreddays="$(date +%m-%d-%Y --date='100 day ago')"
    current="$(date '+%m-%d-%Y')"
    if [ -f "remarks.*" ]; then
        rm remarks.*
    fi
    echo "Select Remark.Id, Author, Message, Timestamp, TaskId From Task
    inner join Remark on Remark.TaskId = Task.Id
    where Task.TaskNumber Like 'GBI%'
    and Format(Task.DateCreated, 'MM-dd-yyyy', 'en-US') >= '${onehundreddays}' 
    AND Format(Task.DateCreated, 'MM-dd-yyyy', 'en-US') <= '${current}';" > remarks.sql
    if [ -f "remarks.csv" ]; then
        rm remarks.csv
        touch remarks.csv
    else
        touch remarks.csv
    fi
    sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "remarks.sql" -o "remarks.csv" -s"," -h-1
    n=`cat remarks.csv |wc -l`
    x=$((n-1))
    { echo "Id,Author,Message,Timestamp,TaskId"; sed "$x,\$d" remarks.csv; } > remarksnew.csv
    
    while IFS="," read -r Id Author Message Timestamp TaskId
    do
        mysql -unuserv-demo -h 122.248.200.34 -D gbi -e "INSERT IGNORE INTO Remark 
                (Id, TaskId, Timestamp, Message, Author)
            VALUES
                ('$(echo ${Id})', \"$(echo ${TaskId})\", \"$(echo ${Timestamp})\", \"$(echo ${Message})\", \"$(echo ${Author})\");"
    done < <(tail -n +2 remarksnew.csv)
    # sed -i 's/\x27Null\x27/NULL/gI' $1.sql
    # mysql -vv -unuserv-demo -pnuserv-demo -h 122.248.200.34 -D gbi < $1.sql
    # rm remarks*.*
}
get_data