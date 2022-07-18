# MyCommands

This app...

## Installation

Save in your bash alias archive ( `~/.bash_aliases` in Linux ) the following alias:

```bash
alias my_commands='{path_to_project_directory}/my_commands.sh'
```

and execute following command, in oder to "save" alias:

```bash
source ~/.bash_aliases
```

copy `/config/templates/user-dist.yaml` file as `/config/user.yaml` and customize it with your information.

## Run my_commands

```bash
my_commands <command>
```

Example:

```bash
my_commands debug:echo_name pepito
```

## Run tests

```bash
my_commands tests
```

## Force start dockers and run composer 

Note: this command is executed each time your don't have dockers up

```bash
my_commands up
```

## Stop dockers

```bash
my_commands down
```