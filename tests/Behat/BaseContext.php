<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class BaseContext extends MinkContext implements Context, SnippetAcceptingContext
{

    const CARDS = [1 => '42424242424242'];

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
        $this->fillField('email', 'customer@yopmail.com');
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

    function replace_with_plus($str)
    {
        $str_array = explode ( "\n" , $str );

        $len = count( $str_array );

        $str_2 = '';
        for($i = 0; $i < $len; $i ++)
        {
            $line = $str_array[$i];

            if($i > 0 )
            {
                $str_2 .= "'";
            }

            $str_2 .= $line;

            if($i < $len - 1  )
            {
                $str_2 .= "' + ";
            }

        }

        return $str_2;
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
    public function iClickOnOnTheLinkById($linkId)
    {
        /** @var $row \Behat\Mink\Element\NodeElement */
        $linkEl = $this->getSession()->getPage()->find('css', 'a#'.$linkId);
        $href = $linkEl->getAttribute('href');
        $linkEl->click();

    }

    /**
     * @Then I fill stripe credit card informations with card :card
     */
    public function iFillStripeCreditCardInformationsWithCard(int $card = 0)
    {
//        $this->switchToIFrame('iframe[name^="__privateStripeFrame"]');
//        $this->switchToIFrame('iframe[name^="stripe_checkout_app"]');
        $this->switchToIFrame('iframe');
        $this->fillField('cardnumber', self::CARDS[$card]);
    }

    /**
     * @Given /^I switch to iframe "([^"]*)"$/
     */
    public function switchToIFrame(string $locator)
    {
        $found = false;
        $selector = '/' === $locator[0] ? 'xpath' : 'css';
        $iframes = $this->getSession()->getPage()->findAll($selector, $locator);

        foreach ($iframes as $iframe) {
            try {
                if ($name = $iframe->getAttribute('name')) {
                    $this->getSession()->getDriver()->switchToIFrame($name);
                    $found = true;
                    break;
                }
            } catch (Exception $e) {
                //ignoring
            }
        }

        if (!$found) {
            throw new InvalidArgumentExceptio(sprintf('Could not evaluate CSS Selector: "%s"', $locator));
        }
    }

}
