#!/bin/bash

for file in `find /defaults/ | awk -F '/defaults/' '{print $2}'`; do
    if [ -f /defaults/$file ]; then
        dockerize -template /defaults/$file:/$file
    elif [ -d /defaults/$file ]; then
        # create destination directory
        mkdir -p /$file
    fi
done
