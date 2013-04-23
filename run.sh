#!/usr/local/bin/bash

SCRIPT=$1
PLATFORM=prod

export SCRIPT
export PLATFORM

/usr/local/bin/php -f /home/mobbnet/mbtnetwork.git/run_sh_script.php $2 $3 $4 $5
