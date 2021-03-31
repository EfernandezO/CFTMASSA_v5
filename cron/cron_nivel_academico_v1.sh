#!/bin/bash
#
#29/03/2017
# eliasfernandezo@gmail.com
#
#Editado por acxpoison@gmail.com
#revisa nivel academico de los alumnos, para ser usado por cron. en sistema okalis
#


#-----------------------------------------#
RUTA_RESPALDOS=/home/cftmassa/cron/
FECHA=`date +"%d-%m-%y"`
#----------------------------------------#
cd $RUTA_RESPALDOS
php -f cron_revisa_nivel_academico.php
printf "cron_nivel_academico_v1.sh  Ejecutado... XD \n\n"
