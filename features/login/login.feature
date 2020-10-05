# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Login
    In order to login
    As a user
    I want to have a admin login scenario
    @javascript
    Scenario: Authentification
        Given I am logged in as a superadmin
        Then I should see "Afin d'enrichir votre site"
        Then I should see "HERMES HERMES"
        #Then the response should be received
