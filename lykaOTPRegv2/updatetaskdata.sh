#!/bin/bash
#getsbu
echo "Select Task.TaskNumber, formfield.value from Task
inner join form on form.taskid = task.id
inner join formfield on formfield.formid = form.id
where task.TaskNumber Like 'GBI%'
and formfield.fieldid like 'GBISBU';" > getsbu.sql
touch updatedata.csv
sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "getsbu.sql" -o "getsbu.csv" -s"," -h-1
n=`cat getsbu.csv |wc -l`
x=$((n-1))
{ echo 'TaskNumber,GBISBU'; sed "$x,\$d" getsbu.csv; } > getsbunew.csv
if [ -f "getsbu.sql" ]; then
    rm getsbu.sql
    touch getsbu.sql
else
    touch getsbu.sql
fi
while IFS="," read -r rec_column1 rec_column2
do
    numerical_var1=$(echo ${rec_column1})
    numerical_var2=$(echo ${rec_column2})
    
    echo "INSERT INTO Ticket 
            (TaskNumber, GBISBU)
        VALUES
            ('${numerical_var1}', '${numerical_var2}')
        ON DUPLICATE KEY UPDATE
            GBISBU = '${numerical_var2}';" >> getsbu.sql
done < <(tail -n +2 getsbunew.csv)
mysql -vv -unuserv-demo -pnuserv-demo -h 122.248.200.34 -D gbi < getsbu.sql
#get storecode
echo "Select Task.TaskNumber, formfield.value from Task
inner join form on form.taskid = task.id
inner join formfield on formfield.formid = form.id
where task.TaskNumber Like 'GBI%'
and formfield.fieldid like 'GBIStoreCode';" > GBIStoreCode.sql
touch updatedata.csv
sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "GBIStoreCode.sql" -o "GBIStoreCode.csv" -s"," -h-1
n=`cat GBIStoreCode.csv |wc -l`
x=$((n-1))
{ echo 'TaskNumber,GBIStoreCode'; sed "$x,\$d" GBIStoreCode.csv; } > GBIStoreCodenew.csv
if [ -f "GBIStoreCode.sql" ]; then
    rm GBIStoreCode.sql
    touch GBIStoreCode.sql
else
    touch GBIStoreCode.sql
fi
while IFS="," read -r rec_column1 rec_column2
do
    numerical_var1=$(echo ${rec_column1})
    numerical_var2=$(echo ${rec_column2})
    
    echo "INSERT INTO Ticket 
            (TaskNumber, GBIStoreCode)
        VALUES
            ('${numerical_var1}', '${numerical_var2}')
        ON DUPLICATE KEY UPDATE
            GBIStoreCode = '${numerical_var2}';" >> GBIStoreCode.sql
done < <(tail -n +2 GBIStoreCodenew.csv)
mysql -vv -unuserv-demo -pnuserv-demo -h 122.248.200.34 -D gbi < GBIStoreCode.sql
#GBIStoreName
echo "Select Task.TaskNumber, formfield.value from Task
inner join form on form.taskid = task.id
inner join formfield on formfield.formid = form.id
where task.TaskNumber Like 'GBI%'
and formfield.fieldid like 'GBIStoreName';" > GBIStoreName.sql
touch updatedata.csv
sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "GBIStoreName.sql" -o "GBIStoreName.csv" -s"," -h-1
n=`cat GBIStoreName.csv |wc -l`
x=$((n-1))
{ echo 'TaskNumber,GBIStoreName'; sed "$x,\$d" GBIStoreName.csv; } > GBIStoreNamenew.csv
if [ -f "GBIStoreName.sql" ]; then
    rm GBIStoreName.sql
    touch GBIStoreName.sql
else
    touch GBIStoreName.sql
fi
while IFS="," read -r rec_column1 rec_column2
do
    numerical_var1=$(echo ${rec_column1})
    numerical_var2=$(echo ${rec_column2})
    
    echo "INSERT INTO Ticket 
            (TaskNumber, GBIStoreName)
        VALUES
            ('${numerical_var1}', '${numerical_var2}')
        ON DUPLICATE KEY UPDATE
            GBIStoreName = '${numerical_var2}';" >> GBIStoreName.sql
done < <(tail -n +2 GBIStoreNamenew.csv)
mysql -vv -unuserv-demo -pnuserv-demo -h 122.248.200.34 -D gbi < GBIStoreName.sql
#GBISubCategory
echo "Select Task.TaskNumber, formfield.value from Task
inner join form on form.taskid = task.id
inner join formfield on formfield.formid = form.id
where task.TaskNumber Like 'GBI%'
and formfield.fieldid like 'GBISubCategory';" > GBISubCategory.sql
touch updatedata.csv
sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "GBISubCategory.sql" -o "GBISubCategory.csv" -s"," -h-1
n=`cat GBISubCategory.csv |wc -l`
x=$((n-1))
{ echo 'TaskNumber,GBISubCategory'; sed "$x,\$d" GBISubCategory.csv; } > GBISubCategorynew.csv
if [ -f "GBISubCategory.sql" ]; then
    rm GBISubCategory.sql
    touch GBISubCategory.sql
else
    touch GBISubCategory.sql
fi
while IFS="," read -r rec_column1 rec_column2
do
    numerical_var1=$(echo ${rec_column1})
    numerical_var2=$(echo ${rec_column2})
    
    echo "INSERT INTO Ticket 
            (TaskNumber, GBISubCategory)
        VALUES
            ('${numerical_var1}', '${numerical_var2}')
        ON DUPLICATE KEY UPDATE
            GBISubCategory = '${numerical_var2}';" >> GBISubCategory.sql
done < <(tail -n +2 GBISubCategorynew.csv)
mysql -vv -unuserv-demo -pnuserv-demo -h 122.248.200.34 -D gbi < GBISubCategory.sql
#GBIIncidentStatus
echo "Select Task.TaskNumber, formfield.value from Task
inner join form on form.taskid = task.id
inner join formfield on formfield.formid = form.id
where task.TaskNumber Like 'GBI%'
and formfield.fieldid like 'GBIIncidentStatus';" > GBIIncidentStatus.sql
touch updatedata.csv
sqlcmd -S 122.54.171.141 -U dashboard -Ppowerformdb1 -d powerform-db1 -i "GBIIncidentStatus.sql" -o "GBIIncidentStatus.csv" -s"," -h-1
n=`cat GBIIncidentStatus.csv |wc -l`
x=$((n-1))
{ echo 'TaskNumber,GBIIncidentStatus'; sed "$x,\$d" GBIIncidentStatus.csv; } > GBIIncidentStatusnew.csv
if [ -f "GBIIncidentStatus.sql" ]; then
    rm GBIIncidentStatus.sql
    touch GBIIncidentStatus.sql
else
    touch GBIIncidentStatus.sql
fi
while IFS="," read -r rec_column1 rec_column2
do
    numerical_var1=$(echo ${rec_column1})
    numerical_var2=$(echo ${rec_column2})
    
    echo "INSERT INTO Ticket 
            (TaskNumber, GBIIncidentStatus)
        VALUES
            ('${numerical_var1}', '${numerical_var2}')
        ON DUPLICATE KEY UPDATE
            GBIIncidentStatus = '${numerical_var2}';" >> GBIIncidentStatus.sql
done < <(tail -n +2 GBIIncidentStatusnew.csv)
mysql -vv -unuserv-demo -pnuserv-demo -h 122.248.200.34 -D gbi < GBIIncidentStatus.sql
#GBILatestNotes