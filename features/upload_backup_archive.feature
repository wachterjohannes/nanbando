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
        But the backup-archive "20180422-145100" exists
        And the backup-archive "20180422-151000" exists in the folder "var/storage/test"

    Scenario: The files should be uploaded when running the "push-to" command
        When I run "bin/nanbando push-to test"
        Then I should see "Push to "test" started", "Push finished"
        And The file "var/storage/test/20180422-145100.tar.gz" should exists
        And The file "var/storage/test/20180422-145100.json" should exists
        And The file "var/storage/test/20180422-151000.tar.gz" should exists
        And The file "var/storage/test/20180422-151000.json" should exists
