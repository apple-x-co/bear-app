#!/usr/bin/env bash

cd "$(dirname "$0")" || exit

count=$(docker compose ps | sed -n '3,50p' | grep -v 'Exit ' | awk 'END{print NR}')

if [ "$count" == 0 ]; then
    COMPOSE_HTTP_TIMEOUT=120 docker compose up -d
else
    echo "Docker is already running !!"
fi
