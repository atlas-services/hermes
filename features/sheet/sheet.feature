# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: New sheet
    In order to add a sheet
    As a user
    I want to create a new sheet
    @javascript
    Scenario: New Sheet and save
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "page seule"
        And I press "sheet_save"
        Then I should see "HERMES HERMES"
        Then I should see "Menus"
    @javascript
    Scenario: New Sheet and continue
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "page menu"
        And I press "sheet_saveAndAdd"
        Then I should see "HERMES HERMES"
        Then I should see "Je crée une nouvelle page."
    @javascript
    Scenario: Update Sheet name
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/page/"
        And I follow "page-menu"
        When I fill in "sheet_name" with "page menu modifiée"
        And I press "sheet_save"
        Then I should see "HERMES HERMES"
        Then I should see "Menus"
    Scenario: Create new Sheet
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/page/"
        And I follow "Créer"
        When I fill in "sheet_name" with "nouvelle page"
        And I press "sheet_save"
        Then I should see "HERMES HERMES"
        Then I should see "Menus"