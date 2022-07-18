#!/usr/bin/env bash

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

# system down without log & exit
if [ "$1" == "down" ];
then
  echo -e "system down\n"
  cd $SCRIPT_DIR/infra && docker-compose down > /dev/null 2> /dev/null
  exit 0
fi

# system up with log & exit
if [ "$1" == "up" ];
then
  echo -e "system up\n"
  cd $SCRIPT_DIR/infra && docker-compose up --build

  echo -e "composer up\n"
  cd $SCRIPT_DIR/infra && docker-compose exec my_commands composer update > /dev/null 2> /dev/null

  exit 0
fi

# system up without log & continue
if [ -z "`docker ps  | grep my_commands`" ];
then
  echo -e "system up\n"
  cd $SCRIPT_DIR/infra && docker-compose up -d --build > /dev/null 2> /dev/null

  echo -e "composer up\n"
  cd $SCRIPT_DIR/infra && docker-compose exec my_commands composer update > /dev/null 2> /dev/null

  sleep 1s
fi

# Some environment vars
PROJECT_CURRENT_DIR="$PWD"

COMMAND_IN_CONTAINER="bin/console $*"
if [ "$1" == "tests" ];
then
  shift 1

  COMMAND_IN_CONTAINER="vendor/bin/phpunit --bootstrap /app/bootstrap.php --testdox $*  /app/tests"
fi

# Exec direct commands
if [ "$1" == "exec" ];
then
  shift 1
fi


docker exec -ti \
-e CURRENT_BRANCH="$PROJECT_CURRENT_BRANCH" \
-e CURRENT_VERSION="$PROJECT_CURRENT_VERSION" \
-e VERSION_FILE_CONTENT="$VERSION_FILE_CONTENT" \
-e PROJECT_CURRENT_DIR="$PROJECT_CURRENT_DIR" \
my_commands php ${COMMAND_IN_CONTAINER}