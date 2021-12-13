#!/bin/bash
set -x
getscript() {
  pgrep -lf ".[ /]$1( |\$)"
}

live(){
# test if script 1 is running
clear
dt=$(date '+%d/%m/%Y %H:%M:%S');

if getscript "Getfsr.sh" >/dev/null; then
    echo -e getfsr.sh is "\033[0;32mRUNNING\e[0m $dt"
else
    cd /var/www/html/gbi
    nohup ./Getfsr.sh & disown
fi

sleep 2m
live
}
live