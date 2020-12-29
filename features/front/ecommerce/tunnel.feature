# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Tunnel
    In order to buy products
    As a customer
    I want to buy products
    @javascript
    Scenario: Add 3 products to my cart
        Given I am on "/"
        And I wait for 1 seconds
        And I press "add-product-1"
        And I wait for 1 seconds
        And I press "add-product-4"
        And I wait for 1 seconds
        And I press "add-product-5"
        And I wait for 1 seconds
#        3 products added to the cart
        Then I should see "3"
        And I wait for 2 seconds
        And I click on the link by id "cart-nav-link"
        Then I should see "Mon panier"
        And I click on the link by id "order-account"
        Then I should see "Se connecter"
        And I wait for 2 seconds
        And I am logged in as a customer
        Then I should see "Mode de livraison"
        And I select "HOME" from "delivery_deliveryMethod"
        And I press "Livraison"
        And I scroll "paiement" into view
#        paiement.stripe.checkout
        And I wait for 4 seconds
        And I press "Règler avec Stripe"
        And I wait for 2 seconds
        And I fill stripe credit card informations with card 1
        And I wait for 12 seconds
        Then I should see "Paiement effectué!"
#        And I press "add-product-1"
#        And I wait for 2 seconds
#        And I press "add-product-4"
#        And I wait for 2 seconds
#        And I press "add-product-5"
#        3 products added to the cart
