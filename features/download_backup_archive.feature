@console
Feature: Upload backup archive
    In order to store the backup-archive in a save place
    As a command user
    I want to upload my backup-archives to a save storage

    Background:
        Given the resources directory is clean
        And there exists following "backup.php" file
          """
            storage('test', \Nanbando\Storage\DirectoryStorageAdapter::create(get('%cwd%/var/storage/test')));
          """
        But the backup-archive "20180422-145100" exists in the folder "var/storage/test"
        And the backup-archive "20180422-151000" exists

    Scenario: The files should be uploaded when running the "fetch-from" command
        When I run "bin/nanbando fetch-from test"
        Then I should see "Fetch from "test" started", "Fetch finished"
        And The file "var/backups/20180422-145100.tar.gz" should exists
        And The file "var/backups/20180422-145100.json" should exists
        And The file "var/backups/20180422-151000.tar.gz" should exists
        And The file "var/backups/20180422-151000.json" should exists
