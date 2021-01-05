# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Tunnel
    In order to buy products
    As a customer
    I want to buy products with stripe paiement
    @javascript
    Scenario: Add 3 products to my cart
        Given I am on "/"
        And I add product list to my cart "add-product-1,add-product-4,add-product-5"
#        3 products added to the cart
        Then I should see "3"
        And I wait for 2 seconds
        And I click on the link by id "cart-nav-link"
#        tunnel
        Then I should see "Mon panier"
#        tunnel sign in
        And I click on the link by id "order-account"
        Then I should see "Se connecter"
        And I wait for 2 seconds
        And I am logged in as a customer ""
#        tunnel delivery
        Then I should see "Mode de livraison"
        And I select "HOME" from "delivery_deliveryMethod"
        And I wait for 1 seconds
        And I select "Adresse 1" from "delivery_address"
        And I press "Valider"
        And I scroll "paiement" into view
#        tunnel paiement
#        paiement.stripe.checkout
        And I wait for 1 seconds
        And I press "Règler avec Stripe"
        And I wait for 2 seconds
        And I fill stripe credit card informations with card 1
        And I wait for 10 seconds
        Then I should see "Paiement effectué!"
