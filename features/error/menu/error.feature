# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Test error messages
    In order to show errors in a menu
    As a user
    I want to see error messages

    @javascript
    Scenario: New Content with error : no content name message
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/menu"
        And I follow "menu-add"
#       Menu blablabla
        And I scroll "menu_save" into view
        And I press "menu_save"
        Then I should see "Saisissez un nom pour ce menu"