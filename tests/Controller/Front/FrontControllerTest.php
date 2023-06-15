<?php
namespace Tests\Controller\Front;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class FrontControllerTest extends WebTestCase
{
    public static $client;
    public static $translator;


    protected function setUp(): void
    {
        static::$translator = static::getContainer()->get('translator');
        static::$client = static::createClient();

    }

    public static function createClient(array $options = [], array $server = [])
    {
        self::ensureKernelShutdown();
        $client =  parent::createClient($options, $server); // $client

        return $client;
    }

    public function testContact()
    {
        
       $client = static::$client;

       $client->request('GET','/fr/contact');

       $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testContactSendMail( )
    {
    	$client = static::$client;
    	$translator = static::$translator;

        $crawler = $client->request('GET', '/fr/contact');

        $send = $translator->trans('formulaire.send');

        $form = $crawler->selectButton($send)->form();
        $form["contact[name]"] = 'test nom';
        $form["contact[email]"] = 'testemail@hermes-cms.org';
        $form["contact[telephone]"] = '0607080910';
        $form["contact[message]"] = 'test message';

        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

}