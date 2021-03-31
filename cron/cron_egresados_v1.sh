#!/bin/bash
#
#MSave version 1.1 - MariaDB/MySQL database backup script
# Written by Ish Sookun <http://hacklog.in/about>
# Email : ishwon/@\openSUSE\./org
#
# This work is licensed under a Creative Commons Attribution 3.0 Unported License.
# License details at http://creativecommons.org/licenses/by/3.0.
#
#Editado por acxpoison@gmail.com
#crea respaldo de BBDD mariaDB para ser ejecutado por cron


#-----------------------------------------#
RUTA_RESPALDOS=/home/cftmassa/cron/
FECHA=`date +"%d-%m-%y"`
#----------------------------------------#
clear
cd $RUTA_RESPALDOS
php -f cron_revisa_egresados.php
printf "cron_egresados_v1.sh  Ejecutado... XD \n\n"
