# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Test error messages
    In order to show errors in a sheet
    As a user
    I want to see error messages

#    @javascript
#    Scenario: New Content with error : no content name message
#        Given I am logged in as a superadmin
#        Given I am on "/fr/admin/page"
#        And I follow "sheet-add"
#        Then I should see "Menus"
##       Menu blablabla
#        And I scroll "sheet_save" into view
#        And I press "sheet_save"
#        Then I should see "Saisissez un nom pour cette page"

    @javascript
    Scenario: New Content with error : no content error message for template "Libre"
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/page"
        And I follow "sheet-add"
        Then I should see "Menus"
        When I fill in "sheet_name" with "Libres"
        And I press "sheet_saveAndAdd"
#       Menu Libres
        Then I should see "Je crée une nouvelle page."
        When I fill in "menu_name" with "Libre 1"
        And I fill in "menu_sections_0_posts_0_name" with "contenu libre 1"
        And I wait for 2 seconds
#        Then fill in select2 input "menu_sections_0_template" with "Libre" and select "menu_sections_0_template"
        And I select "Libre" from "menu_sections_0_template"
        And I wait for 1 seconds
        And I scroll "menu_save" into view
        And I press "menu_save"
        Then I should see "Saisissez un contenu."