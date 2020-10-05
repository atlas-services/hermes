# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: Contact
    In order to contact
    As a user
    I want to send a message
    @javascript
    Scenario: Contact
        Given I am on "/contact"
        And I fill in "contact_name" with "Thomas"
        And I fill in "contact_email" with "toto@yopmail.com"
        And I fill in "contact_telephone" with "0203040506"
        And I fill in "contact_message" with "Bonjour, ça va?"
        And I scroll "sendMessageButton" into view
        And I press "sendMessageButton"
        And I wait for 5 seconds
        Then I should see "Votre message a bien été envoyé"
        #Then the response should be received
