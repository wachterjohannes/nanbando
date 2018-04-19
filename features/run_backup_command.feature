@console
Feature: Run backup command
    In order to backup data the backup command should run
    As a command user
    I want to run a backup when I call the application with the "backup" command

    Background:
        When I am in the resources directory
        And I set stop the time at "2018-04-05 20:20"
        And I cleanup the resources directory
        And I extract "uploads.zip" to "uploads"
        And There exists following "backup.php" file
          """
            attach('uploads', \Nanbando\Script\DirectoryScript::create(get('%cwd%/uploads')));
          """

    Scenario: The backup file should contain all the files and some additional parameter
        When I run "bin/nanbando backup"
        Then I should see "Backup started", "Backup finished"
        And The file "var/backups/20180405-202000.tar.gz" should exists
        And The file "var/backups/20180405-202000.json" should exists
        And The backup-archive "var/backups/20180405-202000.tar.gz" should include following files
          | name                          | hash                                                     | size   |
          | uploads/84-0-frankenstein.txt | 110cf6e796f0f1b7926036369d25499c047798b6c7ba871b24f57119 | 442932 |
          | uploads/pg345-dracula.txt     | c494c52277bcada86aa142fc6d53e149a7e26d2f8aa9d7f2c72835b7 | 867184 |
        And The database should contain following parameters
          | name     | type     | value            |
          | label    | string   |                  |
          | message  | string   |                  |
          | started  | datetime | 2018-04-05T20:20 |
          | finished | datetime | 2018-04-05T20:20 |

    Scenario: The backup filename should contain the tag
        When I run "bin/nanbando backup testtag"
        Then The file "var/backups/20180405-202000_testtag.tar.gz" should exists
        And The file "var/backups/20180405-202000_testtag.json" should exists
        And The backup-archive "var/backups/20180405-202000_testtag.tar.gz" should contain following parameters
          | name  | type   | value   |
          | label | string | testtag |

    Scenario: The backup file should contain the message as parameter
        When I run "bin/nanbando backup -m mymessage"
        Then The file "var/backups/20180405-202000.tar.gz" should exists
        And The file "var/backups/20180405-202000.json" should exists
        And The backup-archive "var/backups/20180405-202000.tar.gz" should contain following parameters
          | name    | type   | value     |
          | message | string | mymessage |
