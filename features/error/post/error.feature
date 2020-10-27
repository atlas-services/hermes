# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Test error messages
    In order to show errors in à post
    As a user
    I want to see error messages

    @javascript
    Scenario: New Content with error : no content name message
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/contenu"
        And I scroll "post-add" into view
        And I follow "post-add"
        Then I should see "Je crée une nouvelle page."
#       Menu blablabla
        When I fill in "menu_name" with "blablabla"
        And I scroll "menu_save" into view
        And I press "menu_save"
        And I scroll "menu_sections_0_posts_0_name" into view
        Then I should see "Saisissez un nom pour ce contenu"