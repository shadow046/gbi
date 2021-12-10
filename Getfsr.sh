#!/bin/bash
set -x
getfsr() {
    export MYSQL_PWD=nuserv-demo
    mysqldump -h 192.168.0.9 -uroot -papplied FSReport --tables fsr --where="typeofwork = 'P' and custcode = 'MD' " > fsr.sql
    mysql -unuserv-demo -h 122.248.200.34 -D stock < fsr.sql
    sleep 20
    getfsr
}
getfsr