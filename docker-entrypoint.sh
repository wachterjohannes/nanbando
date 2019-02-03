#!/usr/bin/env bash

isCommand() {
  for cmd in \
    "backup" \
    "backup:differential" \
    "restore" \
    "push-to" \
    "fetch-from" \
    "init" \
    "list:backups"
  do
    if [ -z "${cmd#"$1"}" ]; then
      return 0
    fi
  done

  return 1
}

# check if the first argument passed in looks like a flag
if [ "$(printf %c "$1")" = '-' ]; then
  set nanbando "$@"
# check if the first argument passed in is nanbando
elif [ "$1" = 'nanbando' ]; then
  set "$@"
# check if the first argument passed in matches a known command
elif isCommand "$1"; then
  set nanbando "$@"
fi

exec "$@"
