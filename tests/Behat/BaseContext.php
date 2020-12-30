<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;
use Stripe\Exception\InvalidArgumentException;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class BaseContext extends MinkContext implements Context, SnippetAcceptingContext
{

    const CARDS = [
        'stripe_card_number' => '424242424242424242',
        'stripe_mm_yy' => '1224',
        'stripe_cvv' => '122',
        'stripe_zip_code' => '94800',
    ];

    private $content = [
        'content_pizzas' => "Content pizza",
        'content_glaces' => 'mon contenu glaces',
    ];

    /**
     * @Given I am logged in as a superadmin
     */
    public function iAmLoggedInAsASuperadmin()
    {
        $this->visitPath('/login');
        $this->fillField('email', 'hermes@atlas-services.fr');
        $this->fillField('Password', 'hermeshermes');
        $this->pressButton('Se connecter');
    }

    /**
     * @Given I am logged in as a customer
     */
    public function iAmLoggedInAsACustomer()
    {
        $this->visitPath('/mon/compte');
        $this->fillField('email', 'customer_stripe@yopmail.com');
        $this->fillField('password', 'toto');
        $this->pressButton('Se connecter');
    }

    /**
     * Waits a while, for debugging.
     *
     * @param int $seconds
     *   How long to wait.
     *
     * @When I wait for :seconds second(s)
     */
    public function wait($seconds) {
        sleep(intval($seconds));
    }

    /**
     * @When I scroll :elementId into view
     */
    public function scrollIntoView($elementId) {
        $function = <<<JS
        (function(){
          var elem = document.getElementById("$elementId");
          elem.scrollIntoView(false);
        })()
JS;
        try {
            $this->getSession()->executeScript($function);
        }
        catch(Exception $e) {
            throw new \Exception("ScrollIntoView failed");
        }
    }

    /**
     * @Then I fill in wysiwyg on field :locator with :value
     */
    public function iFillInWysiwygOnFieldWith($locator, $value) {

        $el = $this->getSession()->getPage()->findField($locator);

        if (empty($el)) {
            throw new ExpectationException('Could not find WYSIWYG with locator: ' . $locator, $this->getSession());
        }

        $fieldId = $el->getAttribute('id');

        if (empty($fieldId)) {
            throw new Exception('Could not find an id for field with locator: ' . $locator);
        }

        $content = $value;
        if(array_key_exists($value, $this->content)){
            $content = htmlentities($this->content[$value]);
        }

        $this->getSession()
            ->executeScript("CKEDITOR.instances[\"$fieldId\"].setData(\"$content\");");
    }

    /**
     * @Then I paste in wysiwyg on field :locator with :tocopy
     */
    public function iPasteInInWysiwygOnField($locator, $tocopy)
    {

        $el = $this->getSession()->getPage()->findField($locator);

        if (empty($el)) {
            throw new ExpectationException('Could not find WYSIWYG with locator: ' . $locator, $this->getSession());
        }

        $fieldId = $el->getAttribute('id');

        if (empty($fieldId)) {
            throw new Exception('Could not find an id for field with locator: ' . $locator);
        }

        $script = 'document.getElementById("'.$tocopy.'").outerHTML';
        $content = $this->getSession()->evaluateScript(
            "return (function(){ return $script; })()"
        );
        $data = json_encode($content);

//        $scriptpaste = 'document.execCommand("paste")';
//        $paste = $this->getSession()->evaluateScript(
//            "return (function(){ return $scriptpaste; })()"
//        );
//        $data = json_encode($paste);

        $this->getSession()->executeScript("CKEDITOR.instances[\"$fieldId\"].focus();");
        $this->getSession()->executeScript("CKEDITOR.instances[\"$fieldId\"].setData($data, function(){this.checkDirty();});");

    }

    /**
     * @Given /^I upload the image "([^"]*)"$/
     */
    public function iUploadTheImage($path) {
        // Cannot use the build in MinkExtension function
        // because the id of the file input field constantly changes and the input field is hidden
        if ($this->getMinkParameter('files_path')) {
            $fullPath = rtrim(realpath($this->getMinkParameter('files_path')), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$path;

            if (is_file($fullPath)) {
                $fileInput = 'input[type="file"]';
                $field = $this->getSession()->getPage()->find('css', $fileInput);

                if (null === $field) {
                    throw new Exception("File input is not found");
                }
                $field->attachFile($fullPath);
            }
        }
        else throw new Exception("File is not found at the given location");
    }

    /**
     * @When /^I click on the link by id "([^"]*)"$/
     */
    public function iClickOnTheLinkById($linkId)
    {
        /** @var $row \Behat\Mink\Element\NodeElement */
        $linkEl = $this->getSession()->getPage()->find('css', 'a#'.$linkId);
        $href = $linkEl->getAttribute('href');
        $linkEl->click();

    }

    /**
     * @When I delete product list to my cart :list_product_ids
     */
    public function iDeleteProductListToMyCart($list_product_ids)
    {
        $this->iClickToTheFollowingLinkList($list_product_ids);
    }

    /**
     * @When I add product list to my cart :list_product_ids
     */
    public function iAddProductListToMyCart($list_product_ids)
    {
        $this->iPressToTheFollowingButtonList($list_product_ids);
    }

    /**
     * @When I update quantity list :list_quantity to my product list cart :list_product
     */
    public function iUpdateQuantityListToMyProductListCart($list_quantity,$list_product)
    {
        $this->iSetTheListValueToTheFollowingSelectedList($list_quantity,$list_product);
    }

    /**
     * @When I press to the following button list :list_button
     */
    public function iPressToTheFollowingButtonList($list_button)
    {
        $buttons = explode(',',$list_button);
        foreach ($buttons as $button) {
            $this->pressButton($button);
            $this->wait(2);
        }
    }

    /**
     * @When I click to the following link list :list_link
     */
    public function iClickToTheFollowingLinkList($list_link)
    {
        $links = explode(',',$list_link);
        foreach ($links as $link) {
            $this->iClickOnTheLinkById($link);
            $this->wait(1);
        }
    }

    /**
     * @When I set the list value :list_value to the following selected list :list_selected
     */
    public function iSetTheListValueToTheFollowingSelectedList($list_value, $list_selected)
    {
        $option = explode(',',$list_value);
        $selected = explode(',',$list_selected);
        foreach ($selected as $key=>$select) {
            $this->selectOption($select, $option[$key]);
            $this->wait(1);
        }
    }

    /**
     * @Then I fill stripe credit card informations with card :card
     */
    public function iFillStripeCreditCardInformationsWithCard(int $card = 0)
    {
        $this->getSession()->getDriver()->switchToIFrame(0);
        $page = $this->getSession()->getPage();
        $stripeInputCardNumber = $page->findField('Numéro de carte'); // where $field can be: 'Email, CVC, Card Number, MM / YY'
        $stripeInputCardNumber->setValue(self::CARDS['stripe_card_number']);
        $stripeInputMMAA = $page->findField('MM / AA'); // where $field can be: 'Email, CVC, Card Number, MM / YY'
        $stripeInputMMAA->setValue(self::CARDS['stripe_mm_yy']);
        $stripeInputCVV = $page->findField('CVV'); // where $field can be: 'Email, CVC, Card Number, MM / YY'
        $stripeInputCVV->setValue(self::CARDS['stripe_cvv']);
        $stripeInputZipCode = $page->findField('ZIP Code'); // where $field can be: 'Email, CVC, Card Number, MM / YY'
        $stripeInputZipCode->setValue(self::CARDS['stripe_zip_code']);

        $stripeInputButton = $page->find('css', 'button');
        $stripeInputButton->click();

        // Switch Back to Main Window
        $this->getSession()->getDriver()->switchToIFrame(null);

    }

}
