@console
Feature: Run restore command with differential archive
    In order to restore differential backups the restore command should restore all files
    As a command user
    I want to run the restore command with a differential backup-archive

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
        And I stop the time at "2018-04-23 20:06"

    Scenario: Differential backups should restore changed files
        Given I dump following content to the file "uploads/84-0-frankenstein.txt"
          """
          This is a testcontent
          """
        And I run "bin/nanbando backup:differential 20180405-202000"
        And I remove the file "uploads/84-0-frankenstein.txt"
        When I run "bin/nanbando restore 20180423-200600"
        And The following files should exists
            | name                          | hash                                                     | size   |
            | uploads/84-0-frankenstein.txt | 39b8ec494cd56c11bf0d702658ee847c3563aaa34e6a839875992231 | 21     |
            | uploads/pg345-dracula.txt     | c494c52277bcada86aa142fc6d53e149a7e26d2f8aa9d7f2c72835b7 | 867184 |

    Scenario: Differential backup should restore new file
        Given I dump following content to the file "uploads/test.txt"
          """
          This is a testcontent
          """
        And I run "bin/nanbando backup:differential 20180405-202000"
        And I remove the file "uploads/test.txt"
        When I run "bin/nanbando restore 20180423-200600"
        And The following files should exists
            | name                          | hash                                                     | size   |
            | uploads/test.txt              | 39b8ec494cd56c11bf0d702658ee847c3563aaa34e6a839875992231 | 21     |
            | uploads/84-0-frankenstein.txt | 110cf6e796f0f1b7926036369d25499c047798b6c7ba871b24f57119 | 442932 |
            | uploads/pg345-dracula.txt     | c494c52277bcada86aa142fc6d53e149a7e26d2f8aa9d7f2c72835b7 | 867184 |

    Scenario: Differential backup should not restore removed file
        Given I remove the file "uploads/84-0-frankenstein.txt"
        And I run "bin/nanbando backup:differential 20180405-202000"
        And I remove the file "uploads/pg345-dracula.txt"
        When I run "bin/nanbando restore 20180423-200600"
        And The following files should exists
            | name                          | hash                                                     | size   |
            | uploads/pg345-dracula.txt     | c494c52277bcada86aa142fc6d53e149a7e26d2f8aa9d7f2c72835b7 | 867184 |
