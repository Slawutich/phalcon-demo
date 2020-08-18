#!/bin/bash
set -e

for file in `find /etc/cont-init.d/ -maxdepth 1 -type f -printf '%f\n' | sort`; do
    echo "Run init script: $file"
    source /etc/cont-init.d/$file
done

exec $@
