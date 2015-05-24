#!/bin/bash
cd /home/cpfaizen

today=$(date +"%Y-%m-%d-%H")
/usr/local/bin/php.ORIG.5_4 app/console cron:fftt --env=prod > /home/cpfaizen/cron/logs/log-$today.txt