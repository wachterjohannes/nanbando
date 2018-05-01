@console
Feature: Run backup command with differential mode
    In order to create differential backups the backup command should only handle file changes
    As a command user
    I want to run the backup command with the differential mode to ensure memory efficient and faster backup creations

    Background:
        Given the resources directory contains following files
            | name                          |
            | uploads/84-0-frankenstein.txt |
            | uploads/pg345-dracula.txt     |
        And there exists following "backup.php" file
          """
            attach('uploads', \Nanbando\Script\DirectoryScript::create(get('%cwd%/uploads')));
          """
        And the backup-archive "20180405-202000" exists
        And I stop the time at "2018-04-19 20:52"

    Scenario: Differential backups should only store changed file
        When I dump following content to the file "uploads/84-0-frankenstein.txt"
          """
          This is a testcontent
          """
        And I run "bin/nanbando backup:differential 20180405-202000"
        Then The backup-archive "var/backups/20180419-205200.tar.gz" should include following files
            | name                          | hash                                                     | size   |
            | uploads/84-0-frankenstein.txt | 39b8ec494cd56c11bf0d702658ee847c3563aaa34e6a839875992231 | 21     |
        And should contain following file-metadata
            | name                          | hash                                                     | size   |
            | uploads/84-0-frankenstein.txt | 39b8ec494cd56c11bf0d702658ee847c3563aaa34e6a839875992231 | 21     |
            | uploads/pg345-dracula.txt     | c494c52277bcada86aa142fc6d53e149a7e26d2f8aa9d7f2c72835b7 | 867184 |
        And  should contain following parameters
            | name    | type   | value           |
            | parent  | string | 20180405-202000 |
            | mode    | string | differential    |

    Scenario: Differential backup should only store the new file
        When I dump following content to the file "uploads/test.txt"
          """
          This is a testcontent
          """
        And I run "bin/nanbando backup:differential 20180405-202000"
        Then The backup-archive "var/backups/20180419-205200.tar.gz" should include following files
            | name                          | hash                                                     | size   |
            | uploads/test.txt              | 39b8ec494cd56c11bf0d702658ee847c3563aaa34e6a839875992231 | 21     |
        And should not contain following file
            | name                          |
            | uploads/84-0-frankenstein.txt |
            | uploads/pg345-dracula.txt     |
        And should contain following file-metadata
            | name                          | hash                                                     | size   |
            | uploads/test.txt              | 39b8ec494cd56c11bf0d702658ee847c3563aaa34e6a839875992231 | 21     |
            | uploads/84-0-frankenstein.txt | 110cf6e796f0f1b7926036369d25499c047798b6c7ba871b24f57119 | 442932 |
            | uploads/pg345-dracula.txt     | c494c52277bcada86aa142fc6d53e149a7e26d2f8aa9d7f2c72835b7 | 867184 |
        And should contain following parameters
            | name    | type   | value           |
            | parent  | string | 20180405-202000 |
            | mode    | string | differential    |

    Scenario: Differential backup should remove file from metadata
        When I remove the file "uploads/84-0-frankenstein.txt"
        And I run "bin/nanbando backup:differential 20180405-202000"
        Then The backup-archive "var/backups/20180419-205200.tar.gz" should not contain following file
            | name                          |
            | uploads/84-0-frankenstein.txt |
            | uploads/pg345-dracula.txt     |
        And should contain following file-metadata
            | name                          | hash                                                     | size   |
            | uploads/pg345-dracula.txt     | c494c52277bcada86aa142fc6d53e149a7e26d2f8aa9d7f2c72835b7 | 867184 |
        And should contain following parameters
            | name    | type   | value           |
            | parent  | string | 20180405-202000 |
            | mode    | string | differential    |

    Scenario: Differential backup should remove file from metadata
        Given the differential backup-archive "20180428-163200" ontop of "20180405-202000" exists
        When I run "bin/nanbando backup:differential 20180428-163200"
        Then I should see an error containing "full backup"
        And The file "var/backups/20180419-205200.tar.gz" should not exists
