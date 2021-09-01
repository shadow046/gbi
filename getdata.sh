#!/bin/bash
set -x
export MYSQL_PWD=nuserv-demo
echo "good" >> "testing.txt"
get_data(){
    # get = $(date +%Y-%m-%d --date='100 day ago')
    # DAYS="100"
    if ! grep -R "$1" exclude.csv
    then
        dt=$(date '+%d/%m/%Y %H:%M:%S');
        file=$(date '+%d-%m-%Y');
        echo "${1} Started at $dt" >> "$file.txt"
        onehundreddays="$(date +%m-%d-%Y --date='100 day ago')"
        current="$(date '+%m-%d-%Y')"
        if [ -f "$1.*" ]; then
            rm $1.*
        fi
        echo "Select Task.Id, Task.TaskNumber , Task.DateCreated, Task.TaskStatus, FormId, FormField.value, Task.CreatedBy From Task
        inner join Form on Form.TaskId = Task.Id
        inner join FormField on FormField.FormId = Form.Id
        where Task.TaskNumber Like 'GBI%'
        and FormField.FieldId LIKE '$1'
        AND FormField.Value is NOT null
        and Format(Task.DateCreated, 'MM-dd-yyyy', 'en-US') >= '${onehundreddays}' 
        AND Format(Task.DateCreated, 'MM-dd-yyyy', 'en-US') <= '${current}';" > $1.sql
        if [ -f "$1.csv" ]; then
            rm $1.csv
            touch $1.csv
        else
            touch $1.csv
        fi
        sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "$1.sql" -o "$1.csv" -s"," -h-1
        n=`cat $1.csv |wc -l`
        x=$((n-1))
        { echo "Taskid,TaskNumber,DateCreated,TaskStatus,FormId,$1,CreatedBy"; sed "$x,\$d" $1.csv; } > $1new.csv
        # if [ -f "$1.sql" ]; then
        #     rm $1.sql
        #     touch $1.sql
        # else
        #     touch $1.sql
        # fi
        # echo "ALTER TABLE Ticket ADD $1 VARCHAR(255) COLLATE utf8_general_ci;" >$1.sql

        mysql -unuserv-demo -h 122.248.200.34 -D gbi -e "SET @preparedStatement = (SELECT IF(
            (
                SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
                WHERE
                (table_name = \"Ticket\")
                AND (table_schema = \"gbi\")
                AND (column_name = \"${1:3}\")
            ) > 0,
            \"SELECT 1\",
            CONCAT(\"ALTER TABLE \", \"Ticket\", \" ADD \", \"${1:3}\", \" VARCHAR(255) COLLATE utf8_general_ci;\")
            ));
            PREPARE alterIfNotExists FROM @preparedStatement;
            EXECUTE alterIfNotExists;
            DEALLOCATE PREPARE alterIfNotExists;"
        
        while IFS="," read -r TaskId TaskNumber DateCreated TaskStatus FormId Value CreatedBy
        do
            mysql -unuserv-demo -h 122.248.200.34 -D gbi -e "INSERT INTO Ticket 
                    (TaskId, TaskNumber, DateCreated, TaskStatus, FormId, ${1:3}, CreatedBy)
                VALUES
                    ('$(echo ${TaskId})', '$(echo ${TaskNumber})', \"$(echo ${DateCreated%%.*})\", \"$(echo ${TaskStatus})\", \"$(echo ${FormId})\", \"$(echo ${Value})\", \"$(echo ${CreatedBy})\")
                ON DUPLICATE KEY UPDATE
                    ${1:3} = \"$(echo ${Value})\",
                    TaskStatus = '$(echo ${TaskStatus})';"
        done < <(tail -n +2 $1new.csv)
        # sed -i 's/\x27Null\x27/NULL/gI' $1.sql
        # mysql -vv -unuserv-demo -pnuserv-demo -h 122.248.200.34 -D gbi < $1.sql
        rm $1*.*
        dt=$(date '+%d/%m/%Y %H:%M:%S');
        echo "${field} done at $dt" >> "$file.txt"
    fi
}
getfields(){
    dt=$(date '+%d/%m/%Y %H:%M:%S');
    file=$(date '+%d-%m-%Y');
    echo "Backup Started at $dt" > "$file.txt"
    #get gbi all fields form powerform database
    echo "Select FieldId From FormField
    Where FieldId Like 'GBI%' Group by FieldId;" > fields.sql
    if [ -f "fields.csv" ]; then
        rm fields.csv
        touch fields.csv
    else
        touch fields.csv
    fi
    sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "fields.sql" -o "fields.csv" -s"," -h-1
    n=`cat fields.csv |wc -l`
    x=$((n-1))
    { echo "FieldId"; sed "$x,\$d" fields.csv; } > fieldsnew.csv
    while IFS="," read -r column1
    do
        field=$(echo ${column1})
        dt=$(date '+%d/%m/%Y %H:%M:%S');
        get_data ${field}
    done < <(tail -n +2 fieldsnew.csv)
    dt=$(date '+%d/%m/%Y %H:%M:%S');
    echo "Script Stopped at $dt" >> "$file.txt"
}

checktime(){
    dt=`date +%H%M`
    dat= echo $dt
    if [[ `date +%H` -ge 0 && `date +%H` -lt 1 ]];then
        getfields
        checktime
    else
        sleep 2m
        checktime
        rm nohup.out
    fi
}
checktime
# getfields