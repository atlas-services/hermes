# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: New sheet for atlas
    In order to add a posts to atlas services
    As a user
    I want to create a atlas services posts

    @javascript
    Scenario: New Sheet, Menu from previous sheet and add 2 Content Libre
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "Présentation"
        And I press "sheet_saveAndAdd"
#       Menu Texte et Vidéo
        When I fill in "menu_name" with "projet"
        And I fill in "menu_sections_0_posts_0_name" with "projet"
        And I select "Libre" from "menu_sections_0_template"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-atlas"
        And I wait for 1 seconds
        And I press "js-copy-atlas1"
        And I wait for 1 seconds
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "menu_sections_0_posts_0_content" with "tocopy-atlas1"
        And I scroll "menu_saveAndAddPost" into view
        And I press "menu_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
        When I fill in "post_name" with "expertise"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-atlas"
        And I wait for 1 seconds
        And I scroll "js-copy-atlas2" into view
        And I press "js-copy-atlas2"
        And I wait for 1 seconds
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "post_content" with "tocopy-atlas2"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
        When I fill in "post_name" with "apropos"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-atlas"
        And I wait for 1 seconds
        And I scroll "js-copy-atlas3" into view
        And I press "js-copy-atlas3"
        And I wait for 1 seconds
        And I scroll "close" into view
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "post_content" with "tocopy-atlas3"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
        When I fill in "post_name" with "slides"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-atlas"
        And I wait for 1 seconds
        And I scroll "js-copy-atlas4" into view
        And I press "js-copy-atlas4"
        And I wait for 1 seconds
        And I scroll "close" into view
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "post_content" with "tocopy-atlas4"
        And I scroll "post_save" into view
        And I press "post_save"
        Then I should see "Sous menus"
        Then I should see "HERMES HERMES"
    @javascript
    Scenario: New Sheet, Menu from previous sheet and add 2 Content Libre
        Given I am logged in as a superadmin
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "Services"
        And I press "sheet_saveAndAdd"
#       Menu Services Projet
        When I fill in "menu_name" with "projet"
        And I fill in "menu_sections_0_posts_0_name" with "projet"
        And I select "Libre" from "menu_sections_0_template"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-atlas"
        And I wait for 1 seconds
        And I press "js-copy-atlas1"
        And I wait for 1 seconds
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "menu_sections_0_posts_0_content" with "tocopy-atlas1"
        And I scroll "menu_save" into view
        And I press "menu_save"
        Then I should see "Sous menus"
        Then I should see "HERMES HERMES"
#       Nouveau menu pour services
        And I follow "Nouveau contenu"
#       Menu services expertise
        And I wait for 2 seconds
        And I select "Services" from "menu_sheet"
        When I fill in "menu_name" with "expertise"
        When I fill in "menu_sections_0_posts_0_name" with "expertise"
        And I select "Libre" from "menu_sections_0_template"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-atlas"
        And I wait for 1 seconds
        And I scroll "js-copy-atlas2" into view
        And I press "js-copy-atlas2"
        And I wait for 1 seconds
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "menu_sections_0_posts_0_content" with "tocopy-atlas2"
        And I scroll "menu_save" into view
        And I press "menu_save"
        Then I should see "Sous menus"
        Then I should see "HERMES HERMES"
#       Nouveau menu pour services
        And I follow "Nouveau contenu"
#       Menu services àpropos
        And I wait for 2 seconds
        And I select "Services" from "menu_sheet"
        When I fill in "menu_name" with "à propos"
        When I fill in "menu_sections_0_posts_0_name" with "à propos"
        And I select "Libre" from "menu_sections_0_template"
        And I press "modal-exemple"
        And I wait for 1 seconds
        And I press "modal-exemple-atlas"
        And I wait for 1 seconds
        And I scroll "js-copy-atlas3" into view
        And I press "js-copy-atlas3"
        And I wait for 1 seconds
        And I press "close"
        And I wait for 1 seconds
        And I paste in wysiwyg on field "menu_sections_0_posts_0_content" with "tocopy-atlas3"
        And I scroll "menu_save" into view
        And I press "menu_save"
        Then I should see "Sous menus"
        Then I should see "HERMES HERMES"
#       Nouveau menu pour services
        And I follow "Nouveau contenu"
#       Menu services slides
#       image1
        And I wait for 1 seconds
        And I select "Services" from "menu_sheet"
        When I fill in "menu_name" with "slides"
        When I fill in "menu_sections_0_posts_0_name" with "slides"
        And I select "Carousel slide avec modale" from "menu_sections_0_template"
        And I wait for 1 seconds
        And I upload the image "atlas/team/TayebChikhi.jpg"
        And I wait for 1 seconds
        And I fill in "menu_sections_0_posts_0_name" with "Carousel1 image 1"
        And I fill in wysiwyg on field "menu_sections_0_posts_0_content" with "Carousel1 image 1"
        And I scroll "menu_saveAndAddPost" into view
        And I press "menu_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image2
        And I upload the image "atlas/team/TayebChikhi.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 2"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 2"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image3
        And I upload the image "atlas/team/TayebChikhi.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 3"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 3"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"