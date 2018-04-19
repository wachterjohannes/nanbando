@console
Feature: Import files
    In order to integrate nanando into the application it should be possible to import files
    As a command user
    I want to import files

    Background:
        When I am in the resources directory
        And I cleanup the resources directory

    Scenario: When importing parameter the the parameter should be present
        When There exists following "backup.php" file
          """
            import('parameters.yml');
          """
        And I run "bin/nanbando debug:parameter"
        And I should see following parameters
            | name  | value       |
            | test1 | "my-test-1" |
            | test2 | "my-test-2" |
            | test2 | "my-test-2" |
