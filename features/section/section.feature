# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: New sections
    In order to add a section
    As a user
    I want to create a new section

    @javascript
    Scenario: New Sheet add Menu and Content
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "Les boissons"
        And I press "sheet_saveAndAdd"
        Then I should see "Mon Tableau de Bord Les boissons Nouveau menu"
        Then I should see "HERMES HERMES"
        When I fill in "menu_name" with "Sans alcool"
        And I select "Libre" from "menu_sections_0_template"
        And I fill in "menu_sections_0_posts_0_name" with "post sans alcool"
        And I fill in wysiwyg on field "menu_sections_0_posts_0_content" with "content sans alcool"
        And I scroll "menu_save" into view
        And I press "menu_save"
        Then I should see "Sous menus"
        Then I should see "HERMES HERMES"
    @javascript
    Scenario: New Sheet, Menu from previous sheet and add 2 Content Libre
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "Les plats"
        And I press "sheet_saveAndAdd"
        When I fill in "menu_name" with "les pizzas"
        And I select "Libre" from "menu_sections_0_template"
        And I fill in "menu_sections_0_posts_0_name" with "post pizza"
        And I wait for 2 seconds
        And I fill in wysiwyg on field "menu_sections_0_posts_0_content" with "content pizzas"
        And I scroll "menu_saveAndAddPost" into view
        And I press "menu_saveAndAddPost"
        Then I should see "Mon Tableau de Bord Les plats les pizzas"
        Then I should see "HERMES HERMES"
        When I fill in "post_name" with "post2 pizza"
        And I wait for 2 seconds
        And I fill in wysiwyg on field "post_content" with "content2 pizzas"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "Mon Tableau de Bord Les plats les pizzas Nouveau contenu"
        Then I should see "HERMES HERMES"
        When I fill in "post_name" with "post3 pizza"
        And I wait for 2 seconds
        And I fill in wysiwyg on field "post_content" with "content3 pizzas"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "Mon Tableau de Bord Les plats les pizzas Nouveau contenu"
        Then I should see "HERMES HERMES"
    @javascript
    Scenario: New Sheet, Menu from previous sheet and add 2 Content Libre
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "Les desserts"
        And I press "sheet_saveAndAdd"
        When I fill in "menu_name" with "les gateaux"
        And I select "Libre" from "menu_sections_0_template"
        And I fill in "menu_sections_0_posts_0_name" with "post l flanc"
        And I fill in wysiwyg on field "menu_sections_0_posts_0_content" with "content l flanc"
        And I wait for 10 seconds
        And I scroll "menu_saveAndAddSectionPost" into view
        And I press "menu_saveAndAddSectionPost"
#        And I wait for 5 seconds
        Then I should see "Mon Tableau de Bord Les desserts les gateaux Nouveau contenu"
        Then I should see "HERMES HERMES"
        When I select "Libre" from "section_template_template"
        When I fill in "section_template_posts_0_name" with "post f1 religieuse"
        And I fill in wysiwyg on field "section_template_posts_0_content" with "contentf1 religieuse"
        And I scroll "section_template_saveAndAddPost" into view
        And I press "section_template_saveAndAddPost"
        Then I should see "Mon Tableau de Bord Les desserts les gateaux Nouveau contenu"
        Then I should see "HERMES HERMES"
        When I fill in "post_name" with "post f2 tarte"
        And I fill in wysiwyg on field "post_content" with "contentf2 tartes"
        And I wait for 2 seconds
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "Mon Tableau de Bord Les desserts les gateaux Nouveau contenu"
        Then I should see "HERMES HERMES"