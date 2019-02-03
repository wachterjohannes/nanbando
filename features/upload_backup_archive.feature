@console
Feature: Upload backup archive
    In order to store the backup-archive in a save place
    As a command user
    I want to upload my backup-archives to a save storage

    Background:
        Given the resources directory is clean
        And there exists following "backup.php" file
          """
            storage('test1', \Nanbando\Storage\DirectoryStorageAdapter::create(get('%cwd%/var/storage/test1')));
            storage('test2', \Nanbando\Storage\DirectoryStorageAdapter::create(get('%cwd%/var/storage/test2')));
          """
        But the backup-archive "20180422-145100" exists
        And the backup-archive "20180422-151000" exists in the folder "var/storage/test1"
        And the backup-archive "20180422-151000" exists in the folder "var/storage/test2"

    Scenario: The files should be uploaded when running the "push" command
        When I run "bin/nanbando push"
        Then I should see "Push started", "Pushed backup "20180422-145100", "Push finished"
        And The file "var/storage/test1/20180422-145100.tar.gz" should exists
        And The file "var/storage/test1/20180422-145100.json" should exists
        And The file "var/storage/test1/20180422-151000.tar.gz" should exists
        And The file "var/storage/test1/20180422-151000.json" should exists
        And The file "var/storage/test2/20180422-145100.tar.gz" should exists
        And The file "var/storage/test2/20180422-145100.json" should exists
        And The file "var/storage/test2/20180422-151000.tar.gz" should exists
        And The file "var/storage/test2/20180422-151000.json" should exists
