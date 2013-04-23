#!/usr/local/bin/bash

SCRIPT=$1
PLATFORM=prod

export SCRIPT
export PLATFORM

/usr/local/bin/php -f run_sh_script.php