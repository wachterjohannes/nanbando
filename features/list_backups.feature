@console
Feature: Run list:backups command
    In order to see which backups are available the list:backups command should list available files
    As a command user
    I want to run the list:backups command to see available backups

    Background:
        Given the resources directory is clean
        And there exists following "backup.php" file
          """
            storage('test', \Nanbando\Storage\DirectoryStorageAdapter::create(get('%cwd%/var/storage/test')));
          """
        But the backup-archive "20180422-145100" exists in the folder "var/storage/test"
        And the backup-archive "20180422-151000" exists

    Scenario: The local storage should be used by default
        When I run "bin/nanbando list:backups"
        Then I should see "20180422-151000"
        And I should not see "20180422-145100"

    Scenario: The file of local storage should be displayed
        When I run "bin/nanbando list:backup local"
        Then I should see "20180422-151000"
        And I should not see "20180422-145100"

    Scenario: The file of remote storage should be displayed
        When I run "bin/nanbando list:backup test"
        Then I should see "20180422-145100"
        And I should not see "20180422-151000"
