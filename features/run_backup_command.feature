@console
Feature: Run backup command
    In order to backup data the backup command should run
    As a command user
    I want to run a backup when I call the application with the "backup" command

    Scenario: The application should display a list of available commands
        When I run "bin/nanbando backup"
        Then I should see "Backup started"
