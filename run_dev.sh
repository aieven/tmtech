#!/bin/bash

SCRIPT=$1
PLATFORM=test

export SCRIPT
export PLATFORM

/usr/bin/php -f /home/sergej/projects/mobbaround/run_sh_script.php $2 $3 $4
