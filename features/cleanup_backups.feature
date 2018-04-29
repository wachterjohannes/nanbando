@console
Feature: Run cleanup command
    In order to cleanup the local and remote space the cleanup command should remove old backup archives
    As a command user
    I want to run the cleanup command to cleanup my local and remote space

    Background:
        Given the resources directory is clean

    Scenario: The latest-file strategy should remove the older files
        Given there exists following "backup.php" file
          """
          cleanupStrategy(new \Nanbando\Cleanup\LatestFileStrategy(2));
          """
        And the backup-archive "20180428-133200" exists
        And the backup-archive "20180428-143200" exists
        And the backup-archive "20180428-153200" exists
        When I run "bin/nanbando cleanup"
        Then The file "var/backups/20180428-133200.tar.gz" should not exists
        Then The file "var/backups/20180428-143200.tar.gz" should exists
        Then The file "var/backups/20180428-153200.tar.gz" should exists

    Scenario: The latest-file strategy should remove the older files but keep the parent archives
        Given there exists following "backup.php" file
          """
          cleanupStrategy(new \Nanbando\Cleanup\LatestFileStrategy(2));
          """
        And the backup-archive "20180428-133200" exists
        And the backup-archive "20180428-143200" exists
        And the backup-archive "20180428-153200" exists
        And the differential backup-archive "20180428-163200" ontop of "20180428-133200" exists
        When I run "bin/nanbando cleanup"
        Then The file "var/backups/20180428-133200.tar.gz" should exists
        Then The file "var/backups/20180428-143200.tar.gz" should not exists
        Then The file "var/backups/20180428-153200.tar.gz" should exists
        Then The file "var/backups/20180428-163200.tar.gz" should exists
