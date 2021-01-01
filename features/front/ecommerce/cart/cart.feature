# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Tunnel
    In order to manage my cart
    I add, delete and update quantities of products
    @javascript
    Scenario: Add 5 products to my cart
        Given I am on "/"
        And I wait for 1 seconds
        And I add product list to my cart "add-product-1,add-product-4,add-product-5,add-product-6,add-product-8"
#        3 products added to the cart
        Then I should see "5"
        And I wait for 1 seconds
        And I click on the link by id "cart-nav-link"
        Then I should see "Mon panier"
        And I delete product list to my cart "delete-product-1,delete-product-6"
        And I wait for 2 seconds
        Then I should see "3"
        And I update quantity list "8,3" to my product list cart "select-product-4,select-product-5"
        Then I should see "224,89"
