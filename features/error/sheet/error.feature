# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Test error messages
    In order to show errors in a sheet
    As a user
    I want to see error messages

    @javascript
    Scenario: New Content with error : no content name message
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/page"
        And I follow "sheet-add"
        Then I should see "Menus"
#       Menu blablabla
        And I scroll "sheet_save" into view
        And I press "sheet_save"
        Then I should see "Saisissez un nom pour cette page"