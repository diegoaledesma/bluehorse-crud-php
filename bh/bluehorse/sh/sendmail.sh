#!/bin/bash
# chmod 0777 sendmail.sh

RECEIVER=$1
SUBJECT=$2
TEXT=$3
SERVER_PORT=$4
SENDER=$5
USER=$6
PASSWORD=$7

swaks -t $RECEIVER -f $SENDER -s $SERVER_PORT -a LOGIN -au $USER -ap $PASSWORD -tls -d "Date: %DATE%\nTo: %TO_ADDRESS%\nFrom: %FROM_ADDRESS%\nSubject: $SUBJECT\nX-Mailer: domain.com\n%NEW_HEADERS%\n $TEXT \n" --add-header "MIME-Version: 1.0" --add-header 'Content-Type: text/html;  charset="us-ascii"'
