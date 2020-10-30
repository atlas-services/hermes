# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Create Hermes Cartes
  In order to create "Cards with pdf"
  As a user
  I want to create hermes cards

  @javascript
  Scenario: New Sheet "Nos Cartes", Menu "Cartes avec pdf à télécharger" with 4 uploaded pdf
    Given I am logged in as a superadmin
#       Nouveau menu "Nos Cartes"
    Given I am on "/fr/admin/nouvelle-page"
    When I fill in "sheet_name" with "Nos Cartes"
    And I press "sheet_saveAndAdd"
    Then I should see "Je crée une nouvelle page."
#          Sous Menu "Cartes avec pdf à télécharger"
#       pdf1
    And I wait for 2 seconds
    When I fill in "menu_name" with "Cartes avec pdf à télécharger"
    And I select "Cartes avec pdf à télécharger" from "menu_sections_0_template"
    And I upload the image "pdf/1.pdf"
    And I wait for 2 seconds
    And I fill in "menu_sections_0_posts_0_name" with "Carte1 pdf 1"
    And I fill in wysiwyg on field "menu_sections_0_posts_0_content" with "Carte1 pdf 1"
    And I scroll "menu_saveAndAddPost" into view
    And I press "menu_saveAndAddPost"
    Then I should see "J'ajoute un contenu à mon sous-menu"
    Then I should see "HERMES HERMES"
#       pdf2
    And I upload the image "pdf/2.pdf"
    And I wait for 1 seconds
    And I fill in "post_name" with "Carte1 pdf 2"
    And I wait for 1 seconds
    And I fill in wysiwyg on field "post_content" with "Carte1 pdf 2"
    And I scroll "post_saveAndAddPost" into view
    And I press "post_saveAndAddPost"
    Then I should see "J'ajoute un contenu à mon sous-menu"
    Then I should see "HERMES HERMES"
#       pdf3
    And I upload the image "pdf/3.pdf"
    And I wait for 1 seconds
    And I fill in "post_name" with "Carte1 pdf 3"
    And I wait for 1 seconds
    And I fill in wysiwyg on field "post_content" with "Carte1 pdf 3"
    And I scroll "post_saveAndAddPost" into view
    And I press "post_saveAndAddPost"
    Then I should see "J'ajoute un contenu à mon sous-menu"
    Then I should see "HERMES HERMES"
#       pdf4
    And I upload the image "pdf/4.pdf"
    And I wait for 1 seconds
    And I fill in "post_name" with "Carte1 pdf 4"
    And I wait for 1 seconds
    And I fill in wysiwyg on field "post_content" with "Carte1 pdf 4"
    And I scroll "post_saveAndAddPost" into view
    And I press "post_saveAndAddPost"
    Then I should see "J'ajoute un contenu à mon sous-menu"
    Then I should see "HERMES HERMES"