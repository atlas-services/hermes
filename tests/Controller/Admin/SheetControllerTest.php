<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Translation\TranslatorInterface;

class SheetControllerTest extends WebTestCase
{
	protected $translator;

	protected $client;

    protected function setUp()
    {
        $this->client = static::createClient();

    	$this->translator = $this->getTranslator();
    }


    public function testLogin( )
    {
       $this->login();

       $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

    }


    public function testAddSheet( )
    {
    	$this->login();

        $crawler = $this->client->request('GET', '/admin/sheet/new');

        $save = $this->translator->trans('global.update');

        $form = $crawler->selectButton($save)->form();

        $form["sheet[name]"] = 'Page test';

	$crawler = $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }


    private function login( )
    {
        $crawler = $this->client->request('GET', '/login');

        $form_login = $crawler->selectButton('Se connecter')->form();
        $form_login['email'] = 'tayebc@yahoo.fr';
        $form_login['password'] = 'atlasatlas';

        $this->client->submit($form_login);

    }


    private function getTranslator()
    {
        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();


        return  self::$container->get('translator');

	}
}