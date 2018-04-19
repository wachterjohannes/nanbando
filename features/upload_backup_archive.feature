@console
Feature: Upload backup archive
    In order to store the backup-archive in a save place
    As a command user
    I want to upload my backup-archives to a save storage

    Background:
        When I am in the resources directory
        And I cleanup the resources directory
        And I extract "backups.zip" to "var/backups"
        But There exists following "backup.php" file
          """
            storage('test', \Nanbando\Storage\DirectoryStorageAdapter::create(get('%cwd%/var/storage/test')));
          """

    Scenario: The files should be uploaded when running the "push-to" command
        When I run "bin/nanbando push-to test"
        Then I should see "Push to "test" started", "Push finished"
        And The file "var/storage/test/20180405-202000.tar.gz" should exists
        And The file "var/storage/test/20180405-202000.json" should exists
        And The file "var/storage/test/20180412-202000.tar.gz" should exists
        And The file "var/storage/test/20180412-202000.json" should exists
