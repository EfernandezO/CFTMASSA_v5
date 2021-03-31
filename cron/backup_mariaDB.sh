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
RUTA_RESPALDOS=/home/cftmassa/Respaldos
FECHA=`date +"%d-%m-%y"`
usuario_mariaDB=root
pass_user='wemA$2EgasAS7'
nombre_BBDD=maesstro
nombreArchivoRespaldo=$nombre_BBDD"_"$FECHA.sql.gz
#----------------------------------------#
cd $RUTA_RESPALDOS
mysqldump -u $usuario_mariaDB -p$pass_user $nombre_BBDD | gzip -9 > $nombre_BBDD"_"$FECHA.sql.gz
printf "Base de Datos $nombre_BBDD se respaldo exitosamente... :D\n\n"
#Envio archivo a otro Servidor via FTP con curl
#07/06/2018
#printf "Nombre de Archivo: $nombreArchivoRespaldo \n"
curl -T $nombreArchivoRespaldo ftp://cftmass.cl/RESPALDOS_BBDD/  --user cft414cl:rectoriax7

#-----------------------------------------#
RUTA_RESPALDOS=/home/cftmassa/Respaldos
FECHA=`date +"%d-%m-%y"`
nombre_BBDD2=moodle
nombreArchivoRespaldo2=$nombre_BBDD2"_"$FECHA.sql.gz
#----------------------------------------#
cd $RUTA_RESPALDOS
mysqldump -u $usuario_mariaDB -p$pass_user $nombre_BBDD2 | gzip -9 > $nombre_BBDD2"_"$FECHA.sql.gz
printf "Base de Datos $nombre_BBDD2 se respaldo exitosamente... :D\n\n"
#Envio archivo a otro Servidor via FTP con curl
#07/06/2018
#printf "Nombre de Archivo: $nombreArchivoRespaldo \n"
curl -T $nombreArchivoRespaldo2 ftp://cftmass.cl/RESPALDOS_BBDD/  --user cft414cl:rectoriax7
