# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: New sheet
    In order to add a posts
    As a user
    I want to create a new posts

    @javascript
    Scenario: New Sheet, Menu from previous sheet and add 2 Content Libre
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "Présentation"
        And I press "sheet_saveAndAdd"
#       Menu Texte et Vidéo
        When I fill in "menu_name" with "Texte et vidéo"
        And I select "Libre" from "menu_sections_0_template"
        And I fill in "menu_sections_0_posts_0_name" with "texte1"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-texte"
        And I wait for 1 seconds
        And I press "js-copy-texte1"
        And I wait for 1 seconds
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "menu_sections_0_posts_0_content" with "tocopy-texte1"
        And I scroll "menu_saveAndAddPost" into view
        And I press "menu_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#        When I fill in "post_name" with "video1"
#        And I press "modal-exemple"
#        And I wait for 1 seconds
#        And I press "modal-exemple-video"
#        And I wait for 1 seconds
#        And I press "js-copy-video1"
#        And I wait for 1 seconds
#        And I press "close"
#        And I wait for 1 seconds
#        And I paste in wysiwyg on field "post_content" with "tocopy-video1"
#        And I scroll "post_saveAndAddPost" into view
#        And I press "post_saveAndAddPost"
#        Then I should see "J'ajoute un contenu à mon sous-menu"
#        Then I should see "HERMES HERMES"
        When I fill in "post_name" with "texte2"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-texte"
        And I wait for 1 seconds
        And I scroll "js-copy-texte3" into view
        And I press "js-copy-texte3"
        And I wait for 1 seconds
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "post_content" with "tocopy-texte3"
        And I scroll "post_save" into view
        And I press "post_save"
        Then I should see "Sous menus"
        Then I should see "HERMES HERMES"
#       Nouveau menu pour présentation
        And I follow "Nouveau contenu"

    @javascript
    Scenario: New Sheet, Menu from previous sheet and add 2 Content Libre
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "Cuisine"
        And I press "sheet_saveAndAdd"
#       Menu Cuisine tradi
        When I fill in "menu_name" with "Tradi"
        And I select "Libre" from "menu_sections_0_template"
        And I fill in "menu_sections_0_posts_0_name" with "cuisine1"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-texte-cuisine"
        And I wait for 1 seconds
        And I press "js-copy-texte-cuisine1"
        And I wait for 1 seconds
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "menu_sections_0_posts_0_content" with "tocopy-texte-cuisine1"
        And I scroll "menu_save" into view
        And I press "menu_save"
        Then I should see "Sous menus"
        Then I should see "HERMES HERMES"
#       Nouveau menu pour cuisine
        And I follow "Nouveau contenu"
#       Menu Cuisine du monde1
        And I wait for 2 seconds
        And I select "Cuisine" from "menu_sheet"
        When I fill in "menu_name" with "Monde 1"
        And I select "Libre" from "menu_sections_0_template"
        When I fill in "menu_sections_0_posts_0_name" with "Monde 1"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-texte-cuisine"
        And I wait for 1 seconds
        And I scroll "js-copy-texte-cuisine2" into view
        And I press "js-copy-texte-cuisine2"
        And I wait for 1 seconds
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "menu_sections_0_posts_0_content" with "tocopy-texte-cuisine2"
        And I scroll "menu_save" into view
        And I press "menu_save"
        Then I should see "Sous menus"
        Then I should see "HERMES HERMES"
#       Nouveau menu pour cuisine
        And I follow "Nouveau contenu"
#       Menu Cuisine du monde2
        And I select "Cuisine" from "menu_sheet"
        When I fill in "menu_name" with "Monde 2"
        And I select "Libre" from "menu_sections_0_template"
        When I fill in "menu_sections_0_posts_0_name" with "Monde 2"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-texte-cuisine"
        And I wait for 1 seconds
        And I scroll "js-copy-texte-cuisine3" into view
        And I press "js-copy-texte-cuisine3"
        And I wait for 1 seconds
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "menu_sections_0_posts_0_content" with "tocopy-texte-cuisine3"
        And I scroll "menu_save" into view
        And I press "menu_save"
        Then I should see "Menu"
        Then I should see "HERMES HERMES"