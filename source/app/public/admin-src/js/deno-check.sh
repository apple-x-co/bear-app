#!/usr/bin/env bash

cd "$(dirname "$0")" || exit

deno check main.js
