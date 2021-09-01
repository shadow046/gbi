#!/bin/bash
sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "gettask.sql" -o "task.csv" -s"," -h-1
{ echo 'TaskNumber'; cat task.csv; } > task.csv
sh csv-sql.sh task.csv
# sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "getticket.sql" -o "ticket.csv" -s"," -h-1
# { echo 'TaskNumber,GBISBU'; cat ticket.csv; } > ticketwithheader.csv
# mv ticketwithheader.csv Ticket.csv
# sh csv-sql.sh Ticket.csv