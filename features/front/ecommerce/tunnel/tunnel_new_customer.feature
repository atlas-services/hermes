# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Tunnel
    In order to buy products
    I add products to my cart and create my customer account
    I sign in with my account
    I select my delivery address
    I buy products with stripe paiement
    @javascript
    Scenario: Add products to my cart, create my custommer account and new order, then pay my order
        Given I am on "/"
        And I add product list to my cart "add-product-1,add-product-4"
#        2 products added to the cart
        Then I should see "2"
        And I wait for 1 seconds
        And I click on the link by id "cart-nav-link"
#        tunnel
        Then I should see "Mon panier"
#        tunnel sign in
        And I click on the link by id "order-account"
        Then I should see "Nouvel utilisateur"
        And I wait for 2 seconds
        And I create my customer account
        Then I should see "Vous pouvez reprendre vos achats en vous identifiant."
        And I wait for 1 seconds
        And I am logged in as a customer "new"
        And I wait for 2 seconds
#        tunnel delivery
        Then I should see "Mode de livraison"
#        And I select "CLICK_AND_COLLECT" from "delivery_deliveryMethod"
#        And I wait for 1 seconds
#        And I select "Hermes, 1 rue Pascal, 94800 Villejuif" from "delivery_address"
        And I fill in select2 input "#delivery_deliveryMethod" with value "Click And Collect" and select "Click And Collect"
        And I wait for 1 seconds
        And I fill in select2 input "#delivery_address" with value "Hermes, 1 rue Pascal, 94800 Villejuif" and select "Hermes, 1 rue Pascal, 94800 Villejuif"
        And I press "Valider"
        And I scroll "paiement" into view
#        tunnel paiement
#        paiement.stripe.checkout
        And I wait for 1 seconds
        And I press "Règler avec Stripe"
        And I wait for 2 seconds
        And I fill stripe credit card informations with card 1
        And I wait for 20 seconds
        Then I should see "Paiement effectué!"
        Then I should see "Vous allez recevoir un email récapitulatif de votre commande."
