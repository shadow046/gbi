#!/bin/bash
today=$(date +'%Y%m%d')
echo 'Select TaskNumber from Task' > gettask.sql
echo "where TaskNumber Like 'GBI-${today}%';" >> gettask.sql
touch task.csv
sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "gettask.sql" -o "task.csv" -s"," -h-1
n=`cat task.csv |wc -l`
x=$((n-1))
{ echo 'TaskNumber'; sed "$x,\$d" task.csv; } > newtask.csv
sh gettask.sh newtask.csv
mysql -vv -unuserv-demo -pnuserv-demo -h 122.248.200.34 -D gbi < newtask.sql
# sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "getticket.sql" -o "ticket.csv" -s"," -h-1
# { echo 'TaskNumber,GBISBU'; cat ticket.csv; } > ticketwithheader.csv
# mv ticketwithheader.csv Ticket.csv
# sh csv-sql.sh Ticket.csv