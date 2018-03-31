@console
Feature: Run restore command
    In order to restore data the restore command should run
    As a command user
    I want to run a restore when I call the application with the "backup" command

    Scenario: The application should display a list of available commands
        When I run "bin/nanbando restore"
        Then I should see "Restore started"
