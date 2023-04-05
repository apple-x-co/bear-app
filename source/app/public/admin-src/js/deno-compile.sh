#!/usr/bin/env bash

cd "$(dirname "$0")" || exit

deno run --allow-env --allow-read --allow-write --allow-net compile.ts
