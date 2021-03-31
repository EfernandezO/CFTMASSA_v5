#!/bin/bash
#
#03/04/2017
# eliasfernandezo@gmail.com
#
#Editado por acxpoison@gmail.com
#revisa estado financiero de los alumnos, para ser usado por cron. en sistema okalis
#


#-----------------------------------------#
RUTA_RESPALDOS=/home/cftmassa/cron/
FECHA=`date +"%d-%m-%y"`
#----------------------------------------#
cd $RUTA_RESPALDOS
php -f cron_revisa_estado_financiero.php
printf "cron_revisa_estado_financiero_v1.sh  Ejecutado... XD \n\n"
