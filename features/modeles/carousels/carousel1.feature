# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Create Hermes carousels
    In order to create "Carousel slide avec modale"
    As a user
    I want to create hermes carousels

    @javascript
    Scenario: New Sheet "Nos Carousels", Menu "Carousel slide avec modale" with 28 uploded images
        Given I am logged in as a superadmin
#       Nouveau menu "Nos Caroussels"
        Given I am on "/fr/admin/nouvelle-page"
        When I fill in "sheet_name" with "Nos Caroussels"
        And I press "sheet_saveAndAdd"
        #  Sous Menu "Carousel slide avec modale"
#       image1
        And I wait for 1 seconds
        When I fill in "menu_name" with "Carousel slide avec modale"
        And I select "Carousel slide avec modale" from "menu_sections_0_template"
        And I upload the image "list/1.jpg"
        And I wait for 1 seconds
        And I fill in "menu_sections_0_posts_0_name" with "Carousel1 image 1"
        And I fill in wysiwyg on field "menu_sections_0_posts_0_content" with "Carousel1 image 1"
        And I scroll "menu_saveAndAddPost" into view
        And I press "menu_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image2
        And I upload the image "list/2.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 2"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 1"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image3
        And I upload the image "list/3.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 3"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 3"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image4
        And I upload the image "list/4.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 4"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 4"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image5
        And I upload the image "list/5.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 5"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 5"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image6
        And I upload the image "list/6.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 6"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 6"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image7
        And I upload the image "list/7.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 7"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 7"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image8
        And I upload the image "list/8.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 8"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 8"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image9
        And I upload the image "list/9.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 9"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 9"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image10
        And I upload the image "list/10.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 10"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 10"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image11
        And I upload the image "list/11.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 11"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 11"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image12
        And I upload the image "list/12.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 12"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 12"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image13
        And I upload the image "list/13.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 13"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 13"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image14
        And I upload the image "list/14.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 14"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 14"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image15
        And I upload the image "list/15.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 15"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 15"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image16
        And I upload the image "list/16.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 16"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 16"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image17
        And I upload the image "list/17.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 17"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 17"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image18
        And I upload the image "list/18.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 18"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 18"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image19
        And I upload the image "list/19.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 19"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 19"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image20
        And I upload the image "list/20.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 20"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 20"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image21
        And I upload the image "list/21.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 21"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 21"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image22
        And I upload the image "list/22.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 22"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 22"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image23
        And I upload the image "list/23.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 23"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 23"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image24
        And I upload the image "list/24.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 24"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 24"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image25
        And I upload the image "list/25.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 25"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 25"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image26
        And I upload the image "list/26.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 26"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 26"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image27
        And I upload the image "list/27.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 27"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 27"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"
#       image28
        And I upload the image "list/28.jpg"
        And I wait for 1 seconds
        And I fill in "post_name" with "Carousel1 image 28"
        And I wait for 1 seconds
        And I fill in wysiwyg on field "post_content" with "Carousel1 image 28"
        And I scroll "post_saveAndAddPost" into view
        And I press "post_saveAndAddPost"
        Then I should see "J'ajoute un contenu à mon sous-menu"
        Then I should see "HERMES HERMES"