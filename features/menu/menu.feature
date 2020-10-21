# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: New Menu
    In order to add a menu
    As a user
    I want to create a new menu
    @javascript
    Scenario: New Sheet, new Menu from previous sheet and add 1 Content Libre and save content
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "Les plats"
        And I press "sheet_saveAndAdd"
        When I fill in "menu_name" with "les pizzas"
        And I fill in "menu_sections_0_posts_0_name" with "post pizza"
        And I select "Libre" from "menu_sections_0_template"
        And I fill in wysiwyg on field "menu_sections_0_posts_0_content" with "content_pizzas"
        And I scroll "menu_save" into view
        And I press "menu_save"
        Then I should see "Sous menus"
        Then I should see "Les plats"
        Then I should see "Les pizzas"
        Then I should see "HERMES HERMES"
    @javascript
    Scenario: New Sheet, new Menu from previous sheet and add 2 Content Libre. save one and coninue with the second content
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "Les desserts"
        And I press "sheet_saveAndAdd"
        When I fill in "menu_name" with "les glaces"
        And I fill in "menu_sections_0_posts_0_name" with "post glaces"
        And I select "Libre" from "menu_sections_0_template"
        And I fill in wysiwyg on field "menu_sections_0_posts_0_content" with "content_glaces"
        And I wait for 2 seconds
        And I scroll "menu_saveAndAddPost" into view
        And I press "menu_saveAndAddPost"
        Then I should see "Mon Tableau de Bord Les desserts les glaces Nouveau contenu"
        Then I should see "Template"
        Then I should see "Continuer"
        Then I should see "Nouveau"
        Then I should see "Sauvegarder"
        Then I should see "HERMES HERMES"
    @javascript
    Scenario: I can see the list of Sous menus, update Sous menus and add new
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/menu/"
        Then I should see "Sous menus"
        Then I should see "Les desserts"
        Then I should see "Les glaces"
        Then I should see "Les plats"
        Then I should see "Les pizzas"
        Then I should see "Nouveau contenu"
    @javascript
    Scenario: I can add a new content
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/menu/"
        Then I follow "Nouveau contenu"
        Then I should see "Mon Tableau de Bord Nouveau menu"
        Then I should see "Continuer"
        Then I should see "Nouveau"
        Then I should see "Sauvegarder"
        Then I should see "HERMES HERMES"
    @javascript
    Scenario: I can update a menu
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/menu/"
        Then I follow "les glaces-edit"
        Then I should see "Sauvegarder"
        Then I should see "HERMES HERMES"
        Then I should not see "Continuer"
        Then I should not see "Nouveau"
        When I fill in "base_menu_name" with "les nouvelles glaces"
        And I scroll "base_menu_save" into view
        And I press "base_menu_save"
        Then I should see "Menu"
    @javascript
    Scenario: I can add a content to an exisiting menu
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/menu/"
        Then I follow "les nouvelles glaces-add"
        Then I should see "Mon Tableau de Bord Les desserts les nouvelles glaces Nouveau contenu"
        Then I should see "Template"
        Then I should see "Continuer"
        Then I should see "Nouveau"
        Then I should see "Sauvegarder"
        Then I should see "HERMES HERMES"