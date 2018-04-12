@console
Feature: List available commands
    In order to see which commands are available
    As a command user
    I want to see a list of commands when I run the application without any parameter

    Background:
        When I am in the resources directory
        And There exists following "backup.php" file
          """
          """
    Scenario: The application should display a list of available commands
        When I run "bin/nanbando"
        Then I should see "Nanbando", "help", "list", "backup"
