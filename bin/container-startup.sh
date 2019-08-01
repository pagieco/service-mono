#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}

if [ "$role" = "scheduler" ]; then
    while [ true ]
    do
        php /app/artisan schedule:run --verbose --no-interaction &
        sleep 60
    done
fi
