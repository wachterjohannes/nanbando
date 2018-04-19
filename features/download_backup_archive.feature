@console
Feature: Upload backup archive
    In order to store the backup-archive in a save place
    As a command user
    I want to upload my backup-archives to a save storage

    Background:
        When I am in the resources directory
        And I cleanup the resources directory
        And I extract "backups.zip" to "var/storage/test"
        But There exists following "backup.php" file
          """
            storage('test', \Nanbando\Storage\DirectoryStorageAdapter::create(get('%cwd%/var/storage/test')));
          """

    Scenario: The files should be uploaded when running the "fetch-from" command
        When I run "bin/nanbando fetch-from test"
        Then I should see "Fetch from "test" started", "Fetch finished"
        And The file "var/backups/20180405-202000.tar.gz" should exists
        And The file "var/backups/20180405-202000.json" should exists
        And The file "var/backups/20180412-202000.tar.gz" should exists
        And The file "var/backups/20180412-202000.json" should exists
