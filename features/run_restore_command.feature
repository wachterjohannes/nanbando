@console
Feature: Run restore command
    In order to restore data the restore command should run
    As a command user
    I want to run a restore when I call the application with the "restore" command

    Background:
        When I am in the resources directory
        And I cleanup the resources directory
        And I extract "backups.zip" to "var/backups"
        And There exists following "backup.php" file
          """
            attach('uploads', \Nanbando\Script\DirectoryScript::create(get('%cwd%/uploads')));
          """

    Scenario: The restored directory file should contain all the files
        When I run "bin/nanbando restore 20180405-202000"
        Then I should see "Restore started", "Restore finished"
        And The file "uploads/84-0-frankenstein.txt" should exists
        And should have following attributes
            | hash                                                     | size   |
            | 110cf6e796f0f1b7926036369d25499c047798b6c7ba871b24f57119 | 442932 |
        And The file "uploads/pg345-dracula.txt" should exists
        And should have following attributes
            | hash                                                     | size   |
            | c494c52277bcada86aa142fc6d53e149a7e26d2f8aa9d7f2c72835b7 | 867184 |
