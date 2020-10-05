<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Translation\TranslatorInterface;

class MenuControllerTest extends WebTestCase
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


    public function testAddSheetAndMenu( )
    {
    	$this->login();

        // add sheet
        $crawler = $this->client->request('GET', '/admin/sheet/new');

        $saveAndAdd = $this->translator->trans('sheet.update_next');

        $form_sheet = $crawler->selectButton($saveAndAdd)->form();

        $form_sheet["sheet[name]"] = 'Page test menu';

	$crawler = $this->client->submit($form_sheet);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        
        //add menu
        $crawler = $this->client->request('GET', '/admin/menu/new');
        $save = $this->translator->trans('global.update');

        $form_menu = $crawler->selectButton($save)->form();
        
        $form_menu["menu[name]"] = 'Menu1';
        $form_menu["menu[section[name]"] = 'Post1';
        $form_menu["menu[post[name]"] = 'Post1';

        
        
	$crawler = $this->client->submit($form_sheet);

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