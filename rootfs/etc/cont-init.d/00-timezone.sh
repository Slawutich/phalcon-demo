#!/bin/bash

[ -z $TZ] && TZ="Etc/UTC"

if [ $TZ ]; then
    [ -f /usr/share/zoneinfo/$TZ ] && ln -sf /usr/share/zoneinfo/$TZ /etc/localtime || echo "WARNING: $TZ is not a valid time zone."
    [ -f /usr/share/zoneinfo/$TZ ] && echo "$TZ" >  /etc/timezone
fi
